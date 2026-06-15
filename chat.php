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
 * Chat page for block_mistralagent.
 *
 * Responsibilities of this file (PHP only — no inline JS, no inline CSS):
 *  • Authenticate the user and check capabilities.
 *  • Load conversation list and determine which conversation to display.
 *  • Render the HTML skeleton consumed by amd/src/chat.js.
 *  • Pass initialisation data to JS via $PAGE->requires->js_call_amd().
 *
 * All interaction logic lives in amd/src/chat.js.
 * All styles live in styles/chat.css.
 *
 * @package    block_mistralagent
 * @copyright  2026 Miguël Dhyne <miguel.dhyne@gmail.com>
 * @author     Miguël Dhyne <miguel.dhyne@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

use block_mistralagent\manager;

// Parameters.

$blockinstanceid = required_param('blockinstanceid', PARAM_INT);
$courseid        = required_param('courseid', PARAM_INT);
$convid   = optional_param('convid', 0, PARAM_INT);
$newconv  = optional_param('newconv', 0, PARAM_INT);

// Access control.

// The $DB global is provided by config.php.
$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
require_login($course);

$context = context_course::instance($courseid);
require_capability('block/mistralagent:use', $context);
global $DB;

// Page setup.

$PAGE->set_url(new moodle_url('/blocks/mistralagent/chat.php', ['blockinstanceid' => $blockinstanceid, 'courseid' => $courseid]));
$PAGE->set_context($context);
$PAGE->set_title(get_string('chatwithai', 'block_mistralagent'));
$PAGE->set_heading($course->fullname);
$PAGE->set_pagelayout('incourse');

// Load the dedicated stylesheet — no inline <style> blocks.
$PAGE->requires->css('/blocks/mistralagent/styles/chat.css?v=2026052901');

// Agent check.

// Resolve the agent — admin mode or teacher personal mode.
$mistralagentid = manager::get_instance_mistral_agent_id($blockinstanceid);
if (!$mistralagentid) {
    redirect(
        new moodle_url('/course/view.php', ['id' => $courseid]),
        get_string('noagentconfigured', 'block_mistralagent'),
        null,
        \core\output\notification::NOTIFY_WARNING
    );
}

$instancerec = $DB->get_record('block_mistralagent_course', ['blockinstanceid' => $blockinstanceid]);
if (!empty($instancerec->use_custom_key)) {
    // Agent perso — construire depuis les champs custom.
    $agent              = new stdClass();
    $agent->name        = $instancerec->custom_agent_name ?: $instancerec->custom_agent_id;
    $agent->description = $instancerec->custom_agent_desc ?? '';
    $agent->agent_id    = $instancerec->custom_agent_id;
    $agentid            = 0;
} else {
    $agentid = manager::get_instance_agent($blockinstanceid);
    $agent   = $agentid ? manager::get_agent($agentid) : null;
}

if (!$agent) {
    redirect(
        new moodle_url('/course/view.php', ['id' => $courseid]),
        get_string('noagentconfigured', 'block_mistralagent'),
        null,
        \core\output\notification::NOTIFY_WARNING
    );
}

// Conversation list (sidebar).

/*
 * Fetch the 20 most recent conversations with the first user message as preview. The subquery reads only one
 * row per conversation and is safe against injection because all values are integers validated by
 * required_param / $USER->id.
 */
$conversations = $DB->get_records_sql(
    "SELECT c.*,
            (SELECT m.content
               FROM {block_mistralagent_msgs} m
              WHERE m.conversationid = c.id
                AND m.role = 'user'
              ORDER BY m.timecreated ASC
              LIMIT 1) AS firstmessage
       FROM {block_mistralagent_convs} c
      WHERE c.userid   = :userid
        AND c.blockinstanceid = :blockinstanceid
      ORDER BY c.timemodified DESC
      LIMIT 20",
    ['userid' => $USER->id, 'blockinstanceid' => $blockinstanceid]
);

// Active conversation.

$currentconvid = 0;

if ($newconv) {
    $currentconvid = 0;
} else if ($convid > 0) {
    // Validate ownership before trusting the URL parameter.
    $conv = $DB->get_record('block_mistralagent_convs', [
        'id'       => $convid,
        'userid'   => $USER->id,
        'courseid' => $courseid,
    ]);
    if ($conv) {
        $currentconvid = $conv->id;
    }
} else if (!empty($conversations)) {
    $currentconvid = reset($conversations)->id;
}

// Resolve preset limits for display.
$instanceconfig = $DB->get_record('block_mistralagent_course', ['blockinstanceid' => $blockinstanceid]);
$presetlimits   = \block_mistralagent\preset_manager::resolve_preset($instanceconfig ?: null);
$maxfilekb      = round($presetlimits['filecontent_chars'] / 1000);

/*
 * JS initialisation. Data is passed through js_call_amd() — no PHP values are echo'd into <script> tags,
 * which would bypass Moodle's CSP and XSS protections.
 */

// Preload strings into window.M.str (synchronous, safe, no payload bloat).
$PAGE->requires->strings_for_js([
    'newconversation',
    'noconversationsyet',
    'attachfile',
    'typemessage',
    'attachfiletip',
    'quotaunlimited',
    'pastemodaltitle',
    'pastemodalintro',
    'pasteplaceholder',
    'copy',
    'copied',
    'filetoobig',
    'filenotready',
    'extracting',
    'fileready',
    'filefailed',
    'errorcommunication',
    'sessexpired',
    'analysisprefix',
    'file_truncated',
    'unexpected_server_response',
    'generated_image_alt',
], 'block_mistralagent');
// Core strings (different component).
$PAGE->requires->string_for_js('cancel', 'core');
$PAGE->requires->string_for_js('confirm', 'core');
$PAGE->requires->string_for_js('back', 'core');

// Only courseid and conversationid in the AMD payload (~60 chars).
$PAGE->requires->js_call_amd('block_mistralagent/chat', 'init', [[
    'blockinstanceid' => (int)$blockinstanceid,
    'courseid'       => (int)$courseid,
    'conversationid' => (int)$currentconvid,
]]);

// Output.
echo $OUTPUT->header();

?>
<?php if ($agent): ?>
<div class="mistralagent-agent-banner">
    <div class="mistralagent-banner-visible">
        <span class="mistralagent-banner-name">
            <i class="fa fa-robot me-2" aria-hidden="true"></i>
            <?php echo s($agent->name ?? get_string('chatwithai', 'block_mistralagent')); ?>
        </span>
        <span id="mistralagent-quota" class="badge bg-secondary ms-2" aria-live="polite"></span>
        <?php if ($agent->description): ?>
        <button class="mistralagent-banner-toggle btn btn-sm btn-link"
                type="button"
                aria-expanded="false"
                aria-controls="mistralagent-agent-desc"
                id="mistralagent-banner-btn">
            <i class="fa fa-chevron-down" aria-hidden="true"></i>
            <span class="sr-only"><?php echo get_string('showdescription', 'block_mistralagent'); ?></span>
        </button>
        <?php endif; ?>
    </div>
    <?php if ($agent->description): ?>
    <div class="mistralagent-agent-desc" id="mistralagent-agent-desc" hidden>
        <p class="mb-0"><?php echo s($agent->description); ?></p>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<div class="mistralagent-chat-container">

    <!-- Sidebar: conversation history -->
    <div class="mistralagent-sidebar">
        <div class="mistralagent-sidebar-header">
            <h5><?php echo get_string('myconversations', 'block_mistralagent'); ?></h5>
            <button type="button" class="btn btn-sm btn-primary" id="mistralagent-newconv-btn"
                    title="<?php echo get_string('startnewconversation', 'block_mistralagent'); ?>">
                <i class="fa fa-plus" aria-hidden="true"></i>
                <span class="sr-only"><?php echo get_string('startnewconversation', 'block_mistralagent'); ?></span>
            </button>
        </div>
        <div class="mistralagent-conversations-list" id="mistralagent-conversations-list">
            <?php foreach ($conversations as $conv):
                $preview  = shorten_text(strip_tags($conv->firstmessage ?? ''), 50);
                $isactive = ($conv->id == $currentconvid) ? 'active' : '';
            ?>
            <div class="mistralagent-conv-item <?php echo $isactive; ?>"
                 data-convid="<?php echo (int)$conv->id; ?>"
                 role="button"
                 tabindex="0"
                 aria-label="<?php echo s($conv->title ?: $preview ?: get_string('newconversation', 'block_mistralagent')); ?>">
                <div class="conv-title">
                    <?php echo s($conv->title ?: $preview ?: get_string('newconversation', 'block_mistralagent')); ?>
                </div>
                <div class="conv-date">
                    <?php echo userdate($conv->timemodified, get_string('strftimedateshort')); ?>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if (empty($conversations)): ?>
            <div class="text-muted p-3 text-center small">
                <?php echo get_string('noconversationsyet', 'block_mistralagent'); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Main chat area : flex column explicite -->
    <div class="mistralagent-main">

        <!-- Fil de messages (prend tout l'espace disponible) -->
        <div id="mistralagent-messages"
             class="mistralagent-messages"
             role="log"
             aria-live="polite"
             aria-label="<?php echo get_string('chatwithai', 'block_mistralagent'); ?>">
        </div>

        <!-- Attached file bar (hidden by default) -->
        <div id="mistralagent-file-preview" class="mistralagent-file-preview" hidden>
            <div class="file-info">
                <i class="fa fa-file" aria-hidden="true"></i>
                <span id="mistralagent-file-name"></span>
                <span id="mistralagent-file-status" class="badge bg-info ms-2"></span>
                <button type="button" class="btn btn-sm btn-outline-primary ms-2"
                        id="mistralagent-paste-btn" hidden>
                    <i class="fa fa-paste" aria-hidden="true"></i>
                    <?php echo get_string('pastetext', 'block_mistralagent'); ?>
                </button>
                <button type="button" class="btn btn-sm btn-link text-danger ms-auto"
                        id="mistralagent-file-remove"
                        aria-label="<?php echo get_string('removefile', 'block_mistralagent'); ?>">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
            </div>
        </div>

        <!-- Zone de saisie — TOUJOURS en bas -->
        <div class="mistralagent-input-area">
            <!-- Ligne 1 : trombone + textarea -->
            <div class="mistralagent-input-row">
                <label class="mistralagent-attach-btn btn btn-outline-secondary"
                       title="<?php echo get_string('attachfile', 'block_mistralagent'); ?>">
                    <i class="fa fa-paperclip" aria-hidden="true"></i>
                    <span class="sr-only"><?php echo get_string('attachfile', 'block_mistralagent'); ?></span>
                    <input type="file" id="mistralagent-file-input"
                           class="sr-only"
                           accept=".pdf,.docx,.txt,.json,.jpg,.jpeg,.png,.gif,.webp">
                </label>
                <textarea id="mistralagent-input"
                          class="form-control"
                          placeholder="<?php echo get_string('typemessage', 'block_mistralagent'); ?>"
                          rows="3"
                          aria-label="<?php echo get_string('typemessage', 'block_mistralagent'); ?>"></textarea>
            </div>
            <!-- Ligne 2 : conseil + bouton Envoyer -->
            <div class="mistralagent-send-row">
                <small class="text-muted">
                    <?php echo get_string('attachfiletip', 'block_mistralagent'); ?>
                    <span class="mistralagent-file-limit-badge">
                        <?php echo get_string('attachfilelimit', 'block_mistralagent', $maxfilekb); ?>
                    </span>
                </small>
                <button type="button" class="btn btn-primary" id="mistralagent-send"
                        aria-label="<?php echo get_string('send', 'block_mistralagent'); ?>">
                    <i class="fa fa-paper-plane me-1" aria-hidden="true"></i>
                    <?php echo get_string('send', 'block_mistralagent'); ?>
                </button>
            </div>
        </div>

        <!-- Modal coller texte -->
        <div id="mistralagent-paste-modal" class="mistralagent-modal" hidden
             role="dialog" aria-modal="true" aria-labelledby="mistralagent-modal-title">
            <div class="mistralagent-modal-content">
                <div class="mistralagent-modal-header">
                    <h5 id="mistralagent-modal-title">
                        <?php echo get_string('pastemodaltitle', 'block_mistralagent'); ?>
                    </h5>
                    <button type="button" class="close" id="mistralagent-modal-close"
                            aria-label="<?php echo get_string('close', 'core'); ?>">&times;</button>
                </div>
                <div class="mistralagent-modal-body">
                    <p class="text-muted small">
                        <?php echo get_string('pastemodalintro', 'block_mistralagent'); ?>
                    </p>
                    <textarea id="mistralagent-paste-area" class="form-control" rows="10"
                              placeholder="<?php echo get_string('pasteplaceholder', 'block_mistralagent'); ?>"></textarea>
                </div>
                <div class="mistralagent-modal-footer">
                    <button type="button" class="btn btn-secondary" id="mistralagent-modal-cancel">
                        <?php echo get_string('cancel'); ?>
                    </button>
                    <button type="button" class="btn btn-primary" id="mistralagent-modal-confirm">
                        <?php echo get_string('confirm'); ?>
                    </button>
                </div>
            </div>
        </div>

    </div><!-- .mistralagent-main -->

</div><!-- .mistralagent-chat-container -->

<?php
echo html_writer::link(
    new moodle_url('/course/view.php', ['id' => $courseid]),
    get_string('back'),
    ['class' => 'btn btn-secondary mt-3']
);

echo $OUTPUT->footer();
