<?php
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
 * Teacher personal agent selection page.
 *
 * The teacher's personal Mistral API key is stored once in their user profile
 * (table block_mistralagent_user_keys) and reused across all blocks they manage.
 *
 * @package    block_mistralagent
 * @copyright  2026 Miguël Dhyne <miguel.dhyne@gmail.com>
 * @author     Miguël Dhyne <miguel.dhyne@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
use block_mistralagent\manager;
use block_mistralagent\mistral_client;

$blockinstanceid = required_param('blockinstanceid', PARAM_INT);
$courseid        = required_param('courseid', PARAM_INT);

$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
require_login($course);

$context = context_course::instance($courseid);
require_capability('block/mistralagent:configureagent', $context);

$PAGE->set_url(new moodle_url('/blocks/mistralagent/my_agents.php', [
    'blockinstanceid' => $blockinstanceid,
    'courseid'        => $courseid,
]));
$PAGE->set_context($context);
$PAGE->set_title(get_string('my_agents_title', 'block_mistralagent'));
$PAGE->set_heading($course->fullname);
$PAGE->set_pagelayout('incourse');

$configureurl = new moodle_url('/blocks/mistralagent/configure.php', [
    'blockinstanceid' => $blockinstanceid,
    'courseid'        => $courseid,
]);

// Delete saved API key.
if (optional_param('delete_key', false, PARAM_BOOL) && confirm_sesskey()) {
    manager::delete_user_apikey($USER->id);
    redirect($PAGE->url, get_string('user_apikey_deleted', 'block_mistralagent'));
}

// Save user API key.
if (optional_param('save_key', false, PARAM_BOOL) && confirm_sesskey()) {
    // PARAM_RAW required: API keys may contain special characters beyond PARAM_ALPHANUM.
    // Value is stored encrypted and only transmitted to the Mistral API over HTTPS.
    $newkey = trim(required_param('new_apikey', PARAM_RAW));
    if (!empty($newkey)) {
        manager::save_user_apikey($USER->id, $newkey);
        redirect($PAGE->url, get_string('user_apikey_saved', 'block_mistralagent'));
    }
}

// Sauvegarde de l'agent choisi.
if (optional_param('save_agent', false, PARAM_BOOL) && confirm_sesskey()) {
    // Mistral agent IDs are alphanumeric identifiers — PARAM_ALPHANUM is sufficient.
    $agentid   = trim(required_param('custom_agent_id', PARAM_ALPHANUM));
    $agentname = trim(required_param('custom_agent_name', PARAM_TEXT));
    $agentdesc = trim(optional_param('custom_agent_desc', '', PARAM_TEXT));

    // Resolve the key: submitted field OR saved key.
    // PARAM_RAW required: API keys may contain special characters beyond PARAM_ALPHANUM.
    $apikey = trim(optional_param('apikey', '', PARAM_RAW));
    if (empty($apikey)) {
        $apikey = manager::get_user_apikey($USER->id);
    } else {
        // Save the entered key for future use.
        manager::save_user_apikey($USER->id, $apikey);
    }

    if (empty($apikey) || empty($agentid)) {
        redirect(
            $PAGE->url,
            get_string('custom_agent_missing', 'block_mistralagent'),
            null,
            \core\output\notification::NOTIFY_ERROR
        );
    }

    manager::set_instance_custom($blockinstanceid, $courseid, $apikey, $agentid, $agentname, $agentdesc);
    redirect(
        new moodle_url('/course/view.php', ['id' => $courseid]),
        get_string('configsaved', 'block_mistralagent')
    );
}

// Fetch agents.
$fetcherror    = '';
$fetchedagents = [];
$apikeyused    = '';

if (optional_param('fetch_agents', false, PARAM_BOOL) && confirm_sesskey()) {
    // PARAM_RAW required: API keys may contain special characters beyond PARAM_ALPHANUM.
    // Value is only transmitted to the Mistral API over HTTPS, never output to HTML.
    $apikeyinput = trim(optional_param('apikey_input', '', PARAM_RAW));

    // If the field is empty, use the saved key.
    if (empty($apikeyinput)) {
        $apikeyinput = manager::get_user_apikey($USER->id);
    } else {
        // Automatically save the entered key.
        manager::save_user_apikey($USER->id, $apikeyinput);
    }

    if (empty($apikeyinput)) {
        $fetcherror = get_string('apikey_empty', 'block_mistralagent');
    } else {
        try {
            $fetchedagents = mistral_client::list_agents_for_key($apikeyinput);
            $apikeyused    = $apikeyinput;
            if (empty($fetchedagents)) {
                $fetcherror = get_string('no_agents_found', 'block_mistralagent');
            }
        } catch (\Exception $e) {
            $fetcherror = $e->getMessage();
        }
    }
}

// Current data.
$instancerecord    = $DB->get_record('block_mistralagent_course', ['blockinstanceid' => $blockinstanceid]);
$currentagentid   = $instancerecord->custom_agent_id ?? '';
$currentagentname = $instancerecord->custom_agent_name ?? '';
$currentagentdesc = $instancerecord->custom_agent_desc ?? '';
$hassavedkey      = manager::has_user_apikey($USER->id);
$savedkeypreview  = ''; // Never display the full key.
if ($hassavedkey) {
    $fullkey = manager::get_user_apikey($USER->id);
    // Show only the first 8 and last 4 characters.
    $len = strlen($fullkey);
    $savedkeypreview = $len > 12
        ? substr($fullkey, 0, 8) . str_repeat('•', max(4, $len - 12)) . substr($fullkey, -4)
        : str_repeat('•', $len);
}

// Affichage.
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('my_agents_title', 'block_mistralagent'));

echo html_writer::link(
    $configureurl,
    '<i class="fa fa-arrow-left mr-1"></i>' . get_string('back', 'core'),
    ['class' => 'btn btn-secondary mb-3']
);

echo '<p class="text-muted mb-4">' . get_string('my_agents_desc', 'block_mistralagent') . '</p>';

// Agent actuel.
if (!empty($currentagentid)) {
    echo '<div class="alert alert-success mb-4">'
        . '<i class="fa fa-check-circle mr-1"></i>'
        . get_string('current_custom_agent', 'block_mistralagent') . ' : '
        . '<strong>' . htmlspecialchars($currentagentname ?: $currentagentid) . '</strong>'
        . ($currentagentdesc ? ' — <em>' . htmlspecialchars($currentagentdesc) . '</em>' : '')
        . '</div>';
}

// API key section.
echo '<div class="card mb-4">';
echo '<div class="card-header d-flex justify-content-between align-items-center">';
echo '<strong><i class="fa fa-key mr-1"></i>' . get_string('step1_apikey', 'block_mistralagent') . '</strong>';
if ($hassavedkey) {
    echo '<span class="badge badge-success">'
        . '<i class="fa fa-check mr-1"></i>'
        . get_string('user_apikey_saved_badge', 'block_mistralagent')
        . '</span>';
}
echo '</div>';
echo '<div class="card-body">';

if ($hassavedkey) {
    // Key already saved — show a masked preview.
    echo '<div class="alert alert-info mb-3">';
    echo '<i class="fa fa-shield mr-1"></i> ';
    echo get_string('user_apikey_stored', 'block_mistralagent');
    echo ' <code class="ml-1">' . htmlspecialchars($savedkeypreview) . '</code>';
    echo '</div>';

    // Form to fetch agents using the saved key.
    echo html_writer::start_tag('form', ['method' => 'post', 'class' => 'form-inline mb-3']);
    echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()]);
    echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'blockinstanceid', 'value' => $blockinstanceid]);
    echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'courseid', 'value' => $courseid]);
    echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'apikey_input', 'value' => '']);
    echo '<button type="submit" name="fetch_agents" value="1" class="btn btn-primary mr-2">'
        . '<i class="fa fa-search mr-1"></i>'
        . get_string('fetch_agents_saved', 'block_mistralagent') . '</button>';

    // Link to delete the saved key.
    $deleteconfirm = str_replace("'", "\\'", get_string('confirm_delete_user_key', 'block_mistralagent'));
    echo html_writer::start_tag('form', ['method' => 'post', 'class' => 'form-inline d-inline']);
    echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()]);
    echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'blockinstanceid', 'value' => $blockinstanceid]);
    echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'courseid', 'value' => $courseid]);
    echo '<button type="submit" name="delete_key" value="1" class="btn btn-outline-danger btn-sm"'
        . ' onclick="return confirm(\'' . $deleteconfirm . '\')">'
        . '<i class="fa fa-trash mr-1"></i>'
        . get_string('delete_user_apikey', 'block_mistralagent') . '</button>';
    echo html_writer::end_tag('form');

    echo html_writer::end_tag('form');

    // Section to use a different key.
    echo '<hr>';
    echo '<p class="text-muted small mb-2">'
        . get_string('use_different_key', 'block_mistralagent') . '</p>';
}

// Form to enter a (new) API key.
echo html_writer::start_tag('form', ['method' => 'post', 'class' => 'mform']);
echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()]);
echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'blockinstanceid', 'value' => $blockinstanceid]);
echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'courseid', 'value' => $courseid]);

echo html_writer::start_div('form-group row');
echo html_writer::tag(
    'label',
    get_string('own_apikey_label', 'block_mistralagent'),
    ['class' => 'col-sm-3 col-form-label', 'for' => 'apikey-input']
);
echo html_writer::start_div('col-sm-9');
echo '<div class="input-group">';
echo '<input type="password" name="apikey_input" id="apikey-input" class="form-control"'
    . ' autocomplete="new-password" placeholder="" value=""'
    . ($hassavedkey ? '' : ' required') . '>';
echo '<div class="input-group-append">';
echo '<button type="button" class="btn btn-outline-secondary" id="btn-toggle-key">'
    . '<i class="fa fa-eye" id="eye-icon"></i></button>';
echo '</div></div>';
echo '<small class="form-text text-muted">' . get_string('own_apikey_hint', 'block_mistralagent') . '</small>';
echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::start_div('form-group row');
echo html_writer::start_div('col-sm-9 offset-sm-3');
echo '<button type="submit" name="fetch_agents" value="1" class="btn btn-outline-primary">'
    . '<i class="fa fa-search mr-1"></i>'
    . get_string('fetch_agents', 'block_mistralagent') . '</button>';
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_tag('form');
echo '</div></div>'; // End card-body, card.

// Erreur.
if (!empty($fetcherror)) {
    echo '<div class="alert alert-danger">'
        . '<i class="fa fa-exclamation-triangle mr-1"></i>'
        . htmlspecialchars($fetcherror) . '</div>';
}

// Liste des agents.
if (!empty($fetchedagents)) {
    echo '<div class="card border-success mb-4">';
    echo '<div class="card-header bg-success text-white">'
        . '<strong><i class="fa fa-robot mr-1"></i>'
        . get_string('step2_selectagent', 'block_mistralagent') . '</strong>'
        . ' <small>(' . count($fetchedagents) . ' '
        . get_string('agents_found', 'block_mistralagent') . ')</small></div>';
    echo '<div class="card-body">';

    echo html_writer::start_tag('form', ['method' => 'post', 'class' => 'mform']);
    echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()]);
    echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'blockinstanceid', 'value' => $blockinstanceid]);
    echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'courseid', 'value' => $courseid]);
    // The key is NOT transmitted here — it is already saved for the user.
    echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'apikey', 'value' => '']);
    echo html_writer::empty_tag(
        'input',
        ['type' => 'hidden', 'name' => 'custom_agent_id', 'id' => 'hidden-agent-id', 'value' => '']
    );
    echo html_writer::empty_tag(
        'input',
        ['type' => 'hidden', 'name' => 'custom_agent_name', 'id' => 'hidden-agent-name', 'value' => '']
    );
    echo html_writer::empty_tag(
        'input',
        ['type' => 'hidden', 'name' => 'custom_agent_desc', 'id' => 'hidden-agent-desc', 'value' => '']
    );

    echo '<div class="row">';
    foreach ($fetchedagents as $ag) {
        $agid       = $ag['id'] ?? '';
        $agname     = $ag['name'] ?? $agid;
        $agdesc     = $ag['description'] ?? '';
        $iscurrent  = ($agid === $currentagentid);
        $border      = $iscurrent ? 'border-primary' : '';

        echo '<div class="col-md-6 mb-3">';
        echo '<div class="card h-100 agent-card ' . $border . '" style="cursor:pointer;transition:all .15s"'
            . ' data-agent-id="'   . htmlspecialchars($agid)   . '"'
            . ' data-agent-name="' . htmlspecialchars($agname) . '"'
            . ' data-agent-desc="' . htmlspecialchars($agdesc) . '">';
        echo '<div class="card-body">';
        echo '<h6 class="card-title mb-1"><i class="fa fa-robot mr-1 text-primary"></i>'
            . htmlspecialchars($agname) . '</h6>';
        if ($agdesc) {
            echo '<p class="card-text text-muted small mb-2">' . htmlspecialchars($agdesc) . '</p>';
        }
        echo '<code class="small text-muted">' . htmlspecialchars($agid) . '</code>';
        if ($iscurrent) {
            echo ' <span class="badge badge-primary">'
                . get_string('current_custom_agent', 'block_mistralagent') . '</span>';
        }
        echo '</div></div></div>';
    }
    echo '</div>';

    echo '<div id="agent-selected-info" class="alert alert-primary mt-3" style="display:none">'
        . '<i class="fa fa-check mr-1"></i>'
        . get_string('agent_selected', 'block_mistralagent') . ' : '
        . '<strong id="selected-agent-label"></strong></div>';

    echo html_writer::start_div('mt-3');
    echo '<button type="submit" name="save_agent" value="1" class="btn btn-success btn-lg"'
        . ' id="btn-save-agent" disabled>'
        . '<i class="fa fa-save mr-1"></i>' . get_string('savechanges') . '</button>';
    echo ' ';
    echo html_writer::link($configureurl, get_string('cancel'), ['class' => 'btn btn-secondary btn-lg']);
    echo html_writer::end_div();
    echo html_writer::end_tag('form');
    echo '</div></div>';
}

$PAGE->requires->js_amd_inline('
require(["jquery"], function ($) {
    $("#btn-toggle-key").on("click", function() {
        var inp = $("#apikey-input");
        var eye = $("#eye-icon");
        if (inp.attr("type") === "password") {
            inp.attr("type", "text");
            eye.removeClass("fa-eye").addClass("fa-eye-slash");
        } else {
            inp.attr("type", "password");
            eye.removeClass("fa-eye-slash").addClass("fa-eye");
        }
    });

    $(".agent-card").on("click", function() {
        $(".agent-card").removeClass("border-primary bg-light");
        $(this).addClass("border-primary bg-light");
        var id   = $(this).data("agent-id");
        var name = $(this).data("agent-name");
        var desc = $(this).data("agent-desc") || "";
        $("#hidden-agent-id").val(id);
        $("#hidden-agent-name").val(name);
        $("#hidden-agent-desc").val(desc);
        $("#selected-agent-label").text(name);
        $("#agent-selected-info").show();
        $("#btn-save-agent").prop("disabled", false);
    });
});
');

echo $OUTPUT->footer();
