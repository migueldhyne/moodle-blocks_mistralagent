<?php
// phpcs:ignoreFile -- This page intentionally contains mixed PHP and HTML markup.
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Manage RAG resources for a course.
 *
 * @package    block_mistralagent
 * @copyright  2026 Miguël Dhyne <miguel.dhyne@gmail.com>
 * @author     Miguël Dhyne <miguel.dhyne@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->libdir . '/filelib.php');

use block_mistralagent\resource_manager;

/**
 * Magic bytes used to validate uploaded binary resources.
 */
const UPLOAD_MAGIC = [
    'pdf'  => "\x25\x50\x44\x46", // PDF marker.
    'docx' => "\x50\x4B\x03\x04", // PK (ZIP local file header).
];

// Parameters.

$blockinstanceid = required_param('blockinstanceid', PARAM_INT);
$courseid        = required_param('courseid',   PARAM_INT);
$action     = optional_param('action',     '', PARAM_ALPHA);
$resourceid = optional_param('resourceid', 0,  PARAM_INT);

// Access control.

// The $DB global is provided by config.php.
$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
require_login($course);

$context = context_course::instance($courseid);
require_capability('block/mistralagent:configureagent', $context);

// Page setup.

$PAGE->set_url(new moodle_url('/blocks/mistralagent/resources.php',
    ['blockinstanceid' => $blockinstanceid, 'courseid' => $courseid]));
$PAGE->set_context($context);
$PAGE->set_title(get_string('manageresources', 'block_mistralagent'));
$PAGE->set_heading($course->fullname);
$PAGE->set_pagelayout('incourse');

$notice = optional_param('mistralnotice', '', PARAM_ALPHA);

// Helpers.

/**
 * Retrieve chunk count and content validity for a resource.
 *
 * is_valid_content() has been moved into resource_manager as a public static
 * method — no duplicate global function here.
 *
 * @param int $resourceid
 * @return array{chunkcount: int, isvalid: bool}
 */
function mistralagent_get_resource_info(int $resourceid): array {
    global $DB;

    $chunkcount = $DB->count_records('block_mistralagent_chunks', ['resourceid' => $resourceid]);

    $firstchunk = $DB->get_record_sql(
        "SELECT chunk_text
           FROM {block_mistralagent_chunks}
          WHERE resourceid = :rid
          ORDER BY chunk_index ASC
          LIMIT 1",
        ['rid' => $resourceid]
    );

    $isvalid = $firstchunk && resource_manager::is_valid_content($firstchunk->chunk_text);

    return ['chunkcount' => $chunkcount, 'isvalid' => $isvalid];
}

/**
 * Validate that a resource belongs to the current course.
 *
 * Prevents a teacher from manipulating resources of another course by
 * crafting a URL with a foreign resourceid.
 *
 * Redirects with an error notification if the check fails.
 *
 * @param int $resourceid
 * @param int $courseid
 */
function mistralagent_require_own_resource(int $resourceid, int $courseid): void {
    $resource = resource_manager::get_resource($resourceid);
    if (!$resource || (int)$resource->courseid !== $courseid) {
        redirect(
            new moodle_url('/blocks/mistralagent/resources.php', ['blockinstanceid' => $blockinstanceid, 'courseid' => $courseid]),
            get_string('resourcenotfound', 'block_mistralagent'),
            null,
            \core\output\notification::NOTIFY_ERROR
        );
    }
}

/**
 * Verify the magic bytes of an uploaded binary file.
 *
 * Consistent with extract_file.php::verify_magic_bytes().
 * Only called for types that have a known binary signature (pdf, docx).
 * txt and json are plain text — no magic bytes check needed.
 *
 * @param string $type     File type ('pdf', 'docx', …).
 * @param string $tmppath  Path to the uploaded temp file.
 * @throws \moodle_exception If the magic bytes do not match.
 */
function mistralagent_verify_upload_magic(string $type, string $tmppath): void {
    if (!isset(UPLOAD_MAGIC[$type])) {
        return; // Txt and json files do not need a magic-bytes check.
    }

    $magic    = UPLOAD_MAGIC[$type];
    $handle   = fopen($tmppath, 'rb');
    $header   = fread($handle, strlen($magic));
    fclose($handle);

    if ($header !== $magic) {
        throw new \moodle_exception(
            'err_file_magic',
            'block_mistralagent',
            '',
            $type
        );
    }
}

/**
 * Chunk a text, persist the chunks, and mark the resource as indexed.
 *
 * Factored out of the 'text' and 'manualtext' upload actions which previously
 * duplicated this logic verbatim.
 *
 * @param int    $resourceid
 * @param string $text       Already cleaned plain text.
 * @return int   Number of chunks created.
 * @throws \Exception On chunking failure.
 */
function mistralagent_index_text_chunks(int $resourceid, string $text): int {
    global $DB;

    $DB->set_field('block_mistralagent_resources', 'status', 'processing', ['id' => $resourceid]);
    $DB->set_field('block_mistralagent_resources', 'error_message', null,   ['id' => $resourceid]);

    $chunks = resource_manager::split_into_chunks($text);
    if (empty($chunks)) {
        throw new \Exception('Cannot split text into chunks.');
    }

    // Replace any existing chunks atomically.
    $DB->delete_records('block_mistralagent_chunks', ['resourceid' => $resourceid]);

    $now = time();
    foreach ($chunks as $index => $chunktext) {
        $rec               = new \stdClass();
        $rec->resourceid   = $resourceid;
        $rec->chunk_index  = $index;
        $rec->chunk_text   = resource_manager::clean_text_for_db($chunktext);
        $rec->embedding    = null;
        $rec->token_count  = (int)ceil(strlen($chunktext) / 4);
        $rec->timecreated  = $now;
        $DB->insert_record('block_mistralagent_chunks', $rec);
    }

    $DB->set_field('block_mistralagent_resources', 'status',       'indexed', ['id' => $resourceid]);
    $DB->set_field('block_mistralagent_resources', 'timemodified', $now,      ['id' => $resourceid]);

    return count($chunks);
}

// Actions.

// Delete.
if ($action === 'delete' && $resourceid && confirm_sesskey()) {
    mistralagent_require_own_resource($resourceid, $courseid);
    resource_manager::delete_resource($resourceid);
    redirect($PAGE->url, get_string('resourcedeleted', 'block_mistralagent'));
}

// Reindex.
if ($action === 'reindex' && $resourceid && confirm_sesskey()) {
    mistralagent_require_own_resource($resourceid, $courseid);
    $result  = resource_manager::process_resource($resourceid);
    $message = $result
        ? get_string('resourcereindexed', 'block_mistralagent')
        : get_string('resourcereindexfailed', 'block_mistralagent');
    $type    = $result
        ? \core\output\notification::NOTIFY_SUCCESS
        : \core\output\notification::NOTIFY_WARNING;
    redirect($PAGE->url, $message, null, $type);
}

// Cleanup.
if ($action === 'cleanup' && confirm_sesskey()) {
    $resources = resource_manager::get_instance_resources($blockinstanceid);
    $cleaned   = 0;

    foreach ($resources as $resource) {
        $info = mistralagent_get_resource_info($resource->id);

        if ($info['chunkcount'] > 0 && !$info['isvalid']) {
            $DB->delete_records('block_mistralagent_chunks', ['resourceid' => $resource->id]);
            $DB->set_field('block_mistralagent_resources', 'status',
                'error', ['id' => $resource->id]);
            $DB->set_field('block_mistralagent_resources', 'error_message',
                get_string('cleanup_invalid_content', 'block_mistralagent'), ['id' => $resource->id]);
            $cleaned++;
        } else if ($resource->status === 'indexed' && $info['chunkcount'] === 0) {
            $DB->set_field('block_mistralagent_resources', 'status',
                'error', ['id' => $resource->id]);
            $DB->set_field('block_mistralagent_resources', 'error_message',
                get_string('cleanup_no_content', 'block_mistralagent'), ['id' => $resource->id]);
            $cleaned++;
        }
    }

    $msg = $cleaned > 0
        ? get_string('cleanup_done', 'block_mistralagent', $cleaned)
        : get_string('cleanup_nothing', 'block_mistralagent');
    redirect($PAGE->url, $msg);
}

// View content (debug).
if ($action === 'viewcontent' && $resourceid) {
    mistralagent_require_own_resource($resourceid, $courseid);
    $resource = resource_manager::get_resource($resourceid);
    $info     = mistralagent_get_resource_info($resourceid);

    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('resourcecontent', 'block_mistralagent') . ' : ' . s($resource->name));

    if ($info['isvalid']) {
        echo '<div class="alert alert-success"><i class="fa fa-check" aria-hidden="true"></i> '
            . get_string('content_valid', 'block_mistralagent') . '</div>';
    } else {
        echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> '
            . get_string('content_invalid', 'block_mistralagent') . '</div>';
    }

    echo '<p><strong>' . get_string('chunks', 'block_mistralagent') . ' :</strong> ' . $info['chunkcount'] . '</p>';

    $chunks = $DB->get_records(
        'block_mistralagent_chunks',
        ['resourceid' => $resourceid],
        'chunk_index ASC'
    );

    if ($chunks) {
        foreach ($chunks as $chunk) {
            $valid  = resource_manager::is_valid_content($chunk->chunk_text);
            $border = $valid ? 'border-success' : 'border-danger';
            echo '<div class="card mb-2 ' . $border . '" style="border-width:2px;">';
            echo '<div class="card-header"><strong>#' . (int)$chunk->chunk_index . '</strong> ('
                . strlen($chunk->chunk_text) . ' chars)';
            if (!$valid) {
                echo ' <span class="badge badge-danger">'
                    . get_string('content_invalid', 'block_mistralagent') . '</span>';
            }
            echo '</div>';
            echo '<div class="card-body"><pre style="white-space:pre-wrap;max-height:200px;overflow-y:auto;">'
                . s(substr($chunk->chunk_text, 0, 1000));
            if (strlen($chunk->chunk_text) > 1000) {
                echo "\n[…]";
            }
            echo '</pre></div></div>';
        }
    } else {
        echo '<div class="alert alert-warning">' . get_string('nochunks', 'block_mistralagent') . '</div>';
    }

    echo html_writer::link($PAGE->url, get_string('back'), ['class' => 'btn btn-secondary mt-3']);
    echo $OUTPUT->footer();
    exit;
}

// Manual text paste.
if ($action === 'manualtext' && $resourceid && confirm_sesskey()) {
    mistralagent_require_own_resource($resourceid, $courseid);

    // PARAM_RAW required: free-form document text may contain any Unicode character.
    // Strict sanitisation is performed by resource_manager::clean_text_for_db().
    $manualtext = resource_manager::clean_text_for_db(
        trim(required_param('manualtext', PARAM_RAW))
    );

    if (empty($manualtext)) {
        redirect($PAGE->url, get_string('emptytext', 'block_mistralagent'),
            null, \core\output\notification::NOTIFY_ERROR);
    }

    if (!resource_manager::is_valid_content($manualtext)) {
        redirect($PAGE->url, get_string('invalidtext', 'block_mistralagent'),
            null, \core\output\notification::NOTIFY_ERROR);
    }

    // Hard cap: 500 000 chars (~125 000 tokens).
    if (strlen($manualtext) > 500000) {
        $manualtext = substr($manualtext, 0, 500000) . "\n[truncated]";
    }

    try {
        $count = mistralagent_index_text_chunks($resourceid, $manualtext);
        redirect($PAGE->url, get_string('indexed_success', 'block_mistralagent', $count));
    } catch (\Exception $e) {
        $DB->set_field('block_mistralagent_resources', 'status',        'error',      ['id' => $resourceid]);
        $DB->set_field('block_mistralagent_resources', 'error_message', $e->getMessage(), ['id' => $resourceid]);
        redirect($PAGE->url,
            get_string('error') . ' : ' . $e->getMessage(),
            null,
            \core\output\notification::NOTIFY_ERROR
        );
    }
}

// Upload.
if ($action === 'upload' && confirm_sesskey()) {
    $type = required_param('type', PARAM_ALPHA);

    // URL.
    if ($type === 'url') {
        $url  = required_param('url',  PARAM_URL);
        $name = optional_param('name', '', PARAM_TEXT) ?: parse_url($url, PHP_URL_HOST);

        $newresourceid = resource_manager::add_resource($blockinstanceid, $courseid, 'url', $name, $url);
        $result        = resource_manager::process_resource($newresourceid);
        redirect($PAGE->url,
            $result
                ? get_string('resource_added', 'block_mistralagent')
                : get_string('extraction_failed_use_json', 'block_mistralagent')
        );

        // YouTube.
    } else if ($type === 'youtube') {
        $url  = required_param('youtube_url', PARAM_URL);
        $name = optional_param('name', '', PARAM_TEXT) ?: 'YouTube – ' . substr($url, 0, 50);

        $newresourceid = resource_manager::add_resource($blockinstanceid, $courseid, 'youtube', $name, $url);
        resource_manager::process_resource($newresourceid);
        redirect($PAGE->url, get_string('youtube_added', 'block_mistralagent'));

        // Plain text paste.
    } else if ($type === 'text') {
        $name        = required_param('name', PARAM_TEXT);
        // PARAM_RAW required: pasted document text may contain any Unicode character.
        // Strict sanitisation is performed by resource_manager::clean_text_for_db().
        $textcontent = resource_manager::clean_text_for_db(
            trim(required_param('textcontent', PARAM_RAW))
        );

        if (empty($textcontent) || empty($name)) {
            redirect($PAGE->url, get_string('name_and_content_required', 'block_mistralagent'),
                null, \core\output\notification::NOTIFY_ERROR);
        }

        if (!resource_manager::is_valid_content($textcontent)) {
            redirect($PAGE->url, get_string('invalidtext', 'block_mistralagent'),
                null, \core\output\notification::NOTIFY_ERROR);
        }

        $newresourceid = resource_manager::add_resource($blockinstanceid, $courseid, 'txt', $name, 'manual');
        $count         = mistralagent_index_text_chunks($newresourceid, $textcontent);
        redirect($PAGE->url, get_string('added_chunks', 'block_mistralagent', $count));

        // Binary file (pdf, docx, txt, json).
    } else if (in_array($type, ['pdf', 'docx', 'txt', 'json'], true)) {
        $filename = $_FILES['file']['name'] ?? '';
        $tmpname  = $_FILES['file']['tmp_name'] ?? '';

        if (empty($tmpname) || !is_uploaded_file($tmpname)) {
            redirect($PAGE->url, get_string('upload_failed', 'block_mistralagent'),
                null, \core\output\notification::NOTIFY_ERROR);
        }

        // Verify magic bytes for binary types (pdf, docx).
        try {
            mistralagent_verify_upload_magic($type, $tmpname);
        } catch (\moodle_exception $e) {
            redirect($PAGE->url, $e->getMessage(), null, \core\output\notification::NOTIFY_ERROR);
        }

        $name      = optional_param('name', $filename, PARAM_TEXT);
        $filesize  = filesize($tmpname);

        // PDF files are OCR-processed by default. The marker is kept for older code paths and traceability.
        $source = ($type === 'pdf') ? 'use_ocr:1|' . $filename : $filename;
        $newresourceid = resource_manager::add_resource($blockinstanceid, $courseid, $type, $name, $source, $filesize);

        // Store in Moodle file API.
        $fs = get_file_storage();
        $fs->create_file_from_pathname([
            'contextid' => $context->id,
            'component' => 'block_mistralagent',
            'filearea'  => 'resource',
            'itemid'    => $newresourceid,
            'filepath'  => '/',
            'filename'  => $filename,
        ], $tmpname);

        $redirecturl = new moodle_url('/blocks/mistralagent/resources.php', [
            'blockinstanceid' => $blockinstanceid,
            'courseid' => $courseid,
        ]);

        // Processing PDFs can take time, especially with OCR. Close the session before the long task.
        // The final notification is passed through the URL to avoid mutating $SESSION after it was closed.
        \core\session\manager::write_close();

        $result = resource_manager::process_resource($newresourceid);

        // Validate content quality after extraction.
        if ($result) {
            $info = mistralagent_get_resource_info($newresourceid);
            if (!$info['isvalid'] && $info['chunkcount'] > 0) {
                $DB->delete_records('block_mistralagent_chunks', ['resourceid' => $newresourceid]);
                $DB->set_field('block_mistralagent_resources', 'status',
                    'error', ['id' => $newresourceid]);
                $DB->set_field('block_mistralagent_resources', 'error_message',
                    get_string('extraction_invalid_content', 'block_mistralagent'), ['id' => $newresourceid]);
                $result = false;
            }
        }

        redirect($PAGE->url,
            $result
                ? get_string('resource_added_success', 'block_mistralagent')
                : get_string('extraction_failed_use_json', 'block_mistralagent'),
            null,
            $result
                ? \core\output\notification::NOTIFY_SUCCESS
                : \core\output\notification::NOTIFY_WARNING
        );
    }
}

// Display.

echo $OUTPUT->header();
if ($notice === 'added') {
    echo $OUTPUT->notification(get_string('resource_added_success', 'block_mistralagent'),
        \core\output\notification::NOTIFY_SUCCESS);
} else if ($notice === 'failed') {
    echo $OUTPUT->notification(get_string('extraction_failed_use_json', 'block_mistralagent'),
        \core\output\notification::NOTIFY_WARNING);
}
echo $OUTPUT->heading(get_string('manageresources', 'block_mistralagent'));

// Cleanup button.
$cleanupurl = new moodle_url($PAGE->url, ['action' => 'cleanup', 'sesskey' => sesskey()]);
echo '<div class="mb-3">';
echo html_writer::link($cleanupurl,
    '<i class="fa fa-broom" aria-hidden="true"></i> '
        . get_string('cleanup_button', 'block_mistralagent'),
    [
        'class'   => 'btn btn-warning',
        'onclick' => 'return confirm(' . json_encode(get_string('cleanup_confirm', 'block_mistralagent')) . ');',
    ]
);
echo '</div>';

// Copyright warning box — displayed at the top of the page.
echo '<div class="alert alert-warning mb-3">'
    . '<strong><i class="fa fa-copyright" aria-hidden="true"></i> '
    . get_string('copyright_reminder_title', 'block_mistralagent') . ' :</strong> '
    . get_string('copyright_reminder', 'block_mistralagent')
    . '</div>';

// Info box.
echo '<div class="alert alert-info mb-3">'
    . '<strong>' . get_string('recommendation', 'block_mistralagent') . ' :</strong> '
    . get_string('json_recommended', 'block_mistralagent')
    . '</div>';

// Upload forms.

echo '<div class="card mb-4">'
    . '<div class="card-header"><h5 class="mb-0">'
    . get_string('addresource', 'block_mistralagent')
    . '</h5></div>'
    . '<div class="card-body">';

// Tabs navigation.
echo '<ul class="nav nav-tabs mb-3" id="resourceTabs" role="tablist">';
$tabs = [
    'json' => '<i class="fa fa-star text-warning" aria-hidden="true"></i> JSON',
    'text' => get_string('tab_directtext', 'block_mistralagent'),
    'file' => get_string('tab_file',       'block_mistralagent'),
    'url'  => get_string('tab_url',        'block_mistralagent'),
];
$first = true;
foreach ($tabs as $tabid => $label) {
    $active = $first ? 'active' : '';
    echo '<li class="nav-item" role="presentation">'
        . '<a class="nav-link ' . $active . '" id="tab-' . $tabid . '-link"'
        . ' data-toggle="tab" data-bs-toggle="tab" href="#tab-' . $tabid . '"'
        . ' role="tab" aria-controls="tab-' . $tabid . '"'
        . ' aria-selected="' . ($first ? 'true' : 'false') . '">'
        . $label . '</a></li>';
    $first = false;
}
echo '</ul>';

echo '<div class="tab-content">';

// JSON tab.
echo '<div class="tab-pane fade show active" id="tab-json" role="tabpanel">';
echo '<div class="alert alert-success mb-2">'
    . get_string('json_format_hint', 'block_mistralagent') . '</div>';
echo '<form method="post" enctype="multipart/form-data">';
echo '<input type="hidden" name="sesskey" value="' . sesskey() . '">';
echo '<input type="hidden" name="action"  value="upload">';
echo '<input type="hidden" name="type"    value="json">';
echo '<div class="form-inline flex-wrap gap-2 mb-2">';
echo '<input type="file"  name="file"  class="form-control-file mr-2" required accept=".json">';
echo '<input type="text"  name="name"  class="form-control mr-2"'
    . ' placeholder="' . get_string('name_optional', 'block_mistralagent') . '">';
echo '<button type="submit" class="btn btn-success">'
    . '<i class="fa fa-upload" aria-hidden="true"></i> '
    . get_string('add_json', 'block_mistralagent') . '</button>';
echo '</div></form></div>';

// Text tab.
echo '<div class="tab-pane fade" id="tab-text" role="tabpanel">';
echo '<form method="post">';
echo '<input type="hidden" name="sesskey" value="' . sesskey() . '">';
echo '<input type="hidden" name="action"  value="upload">';
echo '<input type="hidden" name="type"    value="text">';
echo '<input type="text" name="name" class="form-control mb-2" required'
    . ' placeholder="' . get_string('name', 'core') . '" style="max-width:350px">';
echo '<textarea name="textcontent" class="form-control mb-2" rows="6" required'
    . ' placeholder="' . get_string('text_paste_placeholder', 'block_mistralagent') . '"></textarea>';
echo '<button type="submit" class="btn btn-primary">'
    . '<i class="fa fa-file-text" aria-hidden="true"></i> '
    . get_string('add', 'core') . '</button>';
echo '</form></div>';

// File tab.
echo '<div class="tab-pane fade" id="tab-file" role="tabpanel">';
echo '<div class="alert alert-warning mb-2">'
    . get_string('pdf_extraction_warning', 'block_mistralagent') . '</div>';
echo '<form method="post" enctype="multipart/form-data" id="form-file-upload">';
echo '<input type="hidden" name="sesskey" value="' . sesskey() . '">';
echo '<input type="hidden" name="action"  value="upload">';
echo '<div class="form-inline flex-wrap gap-2 mb-2">';
echo '<select name="type" class="form-control mr-2" id="select-file-type" onchange="mistralagentUpdateOcrVisibility()">';
echo '<option value="pdf">PDF</option>';
echo '<option value="docx">DOCX</option>';
echo '<option value="txt">TXT</option>';
echo '</select>';
echo '<input type="file"  name="file"  class="form-control-file mr-2" required accept=".pdf,.docx,.txt">';
echo '<input type="text"  name="name"  class="form-control mr-2"'
    . ' placeholder="' . get_string('name_optional', 'block_mistralagent') . '">';
echo '<button type="submit" class="btn btn-secondary">'
    . '<i class="fa fa-upload" aria-hidden="true"></i> '
    . get_string('add', 'core') . '</button>';
echo '</div>';

// OCR notice — visible only for PDFs. PDF files are OCR-processed automatically.
echo '<div id="ocr-option" class="mt-2 alert alert-info py-2">';
echo '<strong>' . get_string('use_ocr_label', 'block_mistralagent') . '</strong><br>';
echo '<small>' . get_string('use_ocr_hint', 'block_mistralagent') . '</small>';
echo '</div>';

echo '</form>';
echo '</div>';

$ocrjs = 'require([], function() {
    function mistralagentUpdateOcrVisibility() {
        var sel = document.getElementById("select-file-type");
        var div = document.getElementById("ocr-option");
        if (sel && div) {
            div.style.display = sel.value === "pdf" ? "block" : "none";
        }
    }
    window.mistralagentUpdateOcrVisibility = mistralagentUpdateOcrVisibility;
    document.addEventListener("DOMContentLoaded", mistralagentUpdateOcrVisibility);
    document.querySelectorAll("[data-toggle=\'tab\'], [data-bs-toggle=\'tab\']").forEach(function(el) {
        el.addEventListener("shown.bs.tab", mistralagentUpdateOcrVisibility);
    });
});';
$PAGE->requires->js_amd_inline($ocrjs);

// URL tab.
echo '<div class="tab-pane fade" id="tab-url" role="tabpanel">';
echo '<form method="post">';
echo '<input type="hidden" name="sesskey" value="' . sesskey() . '">';
echo '<input type="hidden" name="action"  value="upload">';
echo '<input type="hidden" name="type"    value="url">';
echo '<div class="form-inline flex-wrap gap-2 mb-2">';
echo '<input type="url"  name="url"  class="form-control mr-2" required'
    . ' placeholder="https://…" style="width:350px">';
echo '<input type="text" name="name" class="form-control mr-2"'
    . ' placeholder="' . get_string('name_optional', 'block_mistralagent') . '">';
echo '<button type="submit" class="btn btn-info">'
    . '<i class="fa fa-globe" aria-hidden="true"></i> '
    . get_string('add', 'core') . '</button>';
echo '</div></form></div>';

echo '</div></div></div>'; // End tab-content / card-body / card.

// Resources table.

$resources = resource_manager::get_instance_resources($blockinstanceid);

if (empty($resources)) {
    echo '<div class="alert alert-secondary">' . get_string('noresources', 'block_mistralagent') . '</div>';
} else {
    $table                      = new html_table();
    $table->head                = [
        get_string('name'),
        get_string('type',   'block_mistralagent'),
        get_string('status', 'block_mistralagent'),
        get_string('chunks', 'block_mistralagent'),
        get_string('quality', 'block_mistralagent'),
        get_string('actions'),
    ];
    $table->attributes['class'] = 'table table-striped';

    $icons  = [
        'pdf'     => 'fa-file-pdf-o text-danger',
        'docx'    => 'fa-file-word-o text-primary',
        'txt'     => 'fa-file-text-o',
        'json'    => 'fa-file-code-o text-warning',
        'url'     => 'fa-globe text-info',
        'youtube' => 'fa-youtube-play text-danger',
    ];
    $badges = [
        'pending'    => 'badge-secondary',
        'processing' => 'badge-info',
        'indexed'    => 'badge-success',
        'error'      => 'badge-danger',
    ];

    foreach ($resources as $r) {
        $info   = mistralagent_get_resource_info($r->id);
        $icon   = $icons[$r->type] ?? 'fa-file-o';
        $badge  = $badges[$r->status] ?? 'badge-secondary';

        $statuslabel = get_string('status_' . $r->status, 'block_mistralagent');
        if ($r->status === 'error' && $r->error_message) {
            $statuslabel .= ' <i class="fa fa-info-circle" aria-hidden="true"'
                . ' title="' . s($r->error_message) . '" style="cursor:help"></i>';
        }

        if ($info['chunkcount'] === 0) {
            $quality = '<span class="badge badge-secondary">–</span>';
        } else if ($info['isvalid']) {
            $quality = '<span class="badge badge-success" title="'
                . get_string('content_valid', 'block_mistralagent') . '">✓</span>';
        } else {
            $quality = '<span class="badge badge-danger" title="'
                . get_string('content_invalid', 'block_mistralagent') . '">✗</span>';
        }

        $actions   = [];

        // View chunks.
        $actions[] = html_writer::link(
            new moodle_url($PAGE->url, ['action' => 'viewcontent', 'resourceid' => $r->id]),
            '<i class="fa fa-eye" aria-hidden="true"></i>',
            ['class' => 'btn btn-sm btn-outline-info', 'title' => get_string('viewcontent', 'block_mistralagent')]
        );

        // Paste text (shown when resource is in error or has invalid content).
        if (in_array($r->status, ['error', 'processing'], true)
                || ($r->status === 'indexed' && !$info['isvalid'])) {
            $rname     = s(addslashes($r->name));
            $actions[] = '<button class="btn btn-sm btn-outline-success"'
                . ' onclick="mistralagentShowModal(' . (int)$r->id . ',\'' . $rname . '\')"'
                . ' title="' . get_string('pastetext', 'block_mistralagent') . '">'
                . '<i class="fa fa-paste" aria-hidden="true"></i></button>';
        }

        // Reindex.
        $actions[] = html_writer::link(
            new moodle_url($PAGE->url, ['action' => 'reindex', 'resourceid' => $r->id, 'sesskey' => sesskey()]),
            '<i class="fa fa-refresh" aria-hidden="true"></i>',
            ['class' => 'btn btn-sm btn-outline-secondary', 'title' => get_string('reindex', 'block_mistralagent')]
        );

        // Delete.
        $actions[] = html_writer::link(
            new moodle_url($PAGE->url, ['action' => 'delete', 'resourceid' => $r->id, 'sesskey' => sesskey()]),
            '<i class="fa fa-trash" aria-hidden="true"></i>',
            [
                'class'   => 'btn btn-sm btn-outline-danger',
                'title'   => get_string('delete'),
                'onclick' => 'return confirm(' . json_encode(get_string('confirmdelete', 'block_mistralagent')) . ');',
            ]
        );

        $table->data[] = [
            '<i class="fa ' . $icon . '" aria-hidden="true"></i> ' . s($r->name),
            strtoupper($r->type),
            '<span class="badge ' . $badge . '">' . $statuslabel . '</span>',
            (int)$info['chunkcount'],
            $quality,
            implode(' ', $actions),
        ];
    }

    echo html_writer::table($table);
}

/*
 * Paste modal. Rendered in the DOM; shown/hidden by the inline JS below. Kept inline because resources.php has
 * no AMD module and the modal is tightly coupled to this page only.
 */
?>
<div id="mistralagent-paste-modal"
     style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;
            background:rgba(0,0,0,0.5);z-index:9999;"
     role="dialog"
     aria-modal="true"
     aria-labelledby="mistralagent-modal-title">
    <div style="background:#fff;margin:5% auto;padding:20px;width:80%;max-width:700px;
                border-radius:8px;max-height:80vh;overflow-y:auto;">
        <h5 id="mistralagent-modal-title">
            <?php echo get_string('pastemodaltitle', 'block_mistralagent'); ?> :
            <span id="mistralagent-modal-name"></span>
        </h5>
        <p class="text-muted"><?php echo get_string('pastemodalintro', 'block_mistralagent'); ?></p>
        <form method="post">
            <input type="hidden" name="sesskey"    value="<?php echo sesskey(); ?>">
            <input type="hidden" name="action"     value="manualtext">
            <input type="hidden" name="resourceid" id="mistralagent-modal-resid">
            <textarea name="manualtext" class="form-control mb-2" rows="10"
                      placeholder="<?php echo get_string('pasteplaceholder', 'block_mistralagent'); ?>"
                      required></textarea>
            <button type="button" class="btn btn-secondary"
                    onclick="mistralagentCloseModal()">
                <?php echo get_string('cancel'); ?>
            </button>
            <button type="submit" class="btn btn-primary">
                <?php echo get_string('saveandindex', 'block_mistralagent'); ?>
            </button>
        </form>
    </div>
</div>

<script>
(function () {
    'use strict';

    // Modal helpers.
    function mistralagentShowModal(id, name) {
        document.getElementById('mistralagent-modal-resid').value   = id;
        document.getElementById('mistralagent-modal-name').textContent = name;
        document.getElementById('mistralagent-paste-modal').style.display = 'block';
    }
    function mistralagentCloseModal() {
        document.getElementById('mistralagent-paste-modal').style.display = 'none';
    }

    // Close on backdrop click.
    document.getElementById('mistralagent-paste-modal').addEventListener('click', function (e) {
        if (e.target === this) { mistralagentCloseModal(); }
    });

    // Close on Escape.
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { mistralagentCloseModal(); }
    });

    // Expose to inline onclick attributes.
    window.mistralagentShowModal  = mistralagentShowModal;
    window.mistralagentCloseModal = mistralagentCloseModal;

    /*
     * Tab switching (Bootstrap 4 data-toggle fallback for Moodle themes that do not load Bootstrap JS
     * automatically).
     */
    document.querySelectorAll('#resourceTabs a[data-toggle="tab"], #resourceTabs a[data-bs-toggle="tab"]').forEach(function (tab) {
        tab.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelectorAll('#resourceTabs a').forEach(function (t) {
                t.classList.remove('active');
                t.setAttribute('aria-selected', 'false');
            });
            this.classList.add('active');
            this.setAttribute('aria-selected', 'true');
            document.querySelectorAll('.tab-pane').forEach(function (p) {
                p.classList.remove('show', 'active');
            });
            document.querySelector(this.getAttribute('href')).classList.add('show', 'active');
        });
    });
}());
</script>

<?php
echo html_writer::link(
    new moodle_url('/course/view.php', ['id' => $courseid]),
    get_string('back'),
    ['class' => 'btn btn-secondary mt-3']
);

echo $OUTPUT->footer();
