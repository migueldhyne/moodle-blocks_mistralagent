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
 * Configure the Mistral agent for a block instance.
 *
 * @package    block_mistralagent
 * @copyright  2026 Miguël Dhyne <miguel.dhyne@gmail.com>
 * @author     Miguël Dhyne <miguel.dhyne@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
use block_mistralagent\manager;

$blockinstanceid = required_param('blockinstanceid', PARAM_INT);
$courseid        = required_param('courseid', PARAM_INT);

$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
require_login($course);

$context = context_course::instance($courseid);
require_capability('block/mistralagent:configureagent', $context);

$PAGE->set_url(new moodle_url('/blocks/mistralagent/configure.php', [
    'blockinstanceid' => $blockinstanceid,
    'courseid'        => $courseid,
]));
$PAGE->set_context($context);
$PAGE->set_title(get_string('courseconfig', 'block_mistralagent'));
$PAGE->set_heading($course->fullname);
$PAGE->set_pagelayout('incourse');

// Deselect the teacher's personal agent.
if (optional_param('deselect_custom', false, PARAM_BOOL) && confirm_sesskey()) {
    manager::set_instance_use_admin_key($blockinstanceid);
    redirect($PAGE->url, get_string('custom_agent_deselected', 'block_mistralagent'));
}

// Sauvegarde mode admin.
if (optional_param('save_admin', false, PARAM_BOOL) && confirm_sesskey()) {
    $agentid = required_param('agentid', PARAM_INT);
    manager::set_instance_agent($blockinstanceid, $courseid, $agentid);
    manager::set_instance_use_admin_key($blockinstanceid);
    redirect(
        new moodle_url('/course/view.php', ['id' => $courseid]),
        get_string('configsaved', 'block_mistralagent')
    );
}

// Current data.
$instancerecord       = $DB->get_record('block_mistralagent_course', ['blockinstanceid' => $blockinstanceid]);
$usecustom            = !empty($instancerecord->use_custom_key);
$currentadminagentid = manager::get_instance_agent($blockinstanceid);
$adminagents          = manager::get_agents(true);
$currentagentid      = $instancerecord->custom_agent_id ?? '';
$currentagentname    = $instancerecord->custom_agent_name ?? '';
$currentagentdesc    = $instancerecord->custom_agent_desc ?? '';

$myagentsurl = new moodle_url('/blocks/mistralagent/my_agents.php', [
    'blockinstanceid' => $blockinstanceid,
    'courseid'        => $courseid,
]);

// Affichage.
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('courseconfig', 'block_mistralagent'));

$tabadmin  = $usecustom ? '' : 'active show';
$tabcustom = $usecustom ? 'active show' : '';

echo '<ul class="nav nav-tabs mb-4" role="tablist">';
echo '<li class="nav-item"><a class="nav-link ' . $tabadmin
    . '" data-toggle="tab" data-bs-toggle="tab" href="#tab-admin" role="tab">'
    . '<i class="fa fa-university mr-1"></i>'
    . get_string('use_admin_agents', 'block_mistralagent') . '</a></li>';
echo '<li class="nav-item"><a class="nav-link ' . $tabcustom
    . '" data-toggle="tab" data-bs-toggle="tab" href="#tab-custom" role="tab">'
    . '<i class="fa fa-key mr-1"></i>'
    . get_string('use_own_apikey', 'block_mistralagent') . '</a></li>';
echo '</ul>';
echo '<div class="tab-content">';

// Onglet Admin.
echo '<div class="tab-pane fade ' . $tabadmin . '" id="tab-admin" role="tabpanel">';
echo '<p class="text-muted mb-3">' . get_string('use_admin_agents_desc', 'block_mistralagent') . '</p>';

// Avertissement si l'agent perso est actuellement actif.
if ($usecustom && !empty($currentagentid)) {
    echo '<div class="alert alert-warning mb-3">'
        . '<i class="fa fa-exclamation-triangle mr-1"></i>'
        . get_string('custom_agent_active_warning', 'block_mistralagent')
        . ' <strong>' . htmlspecialchars($currentagentname ?: $currentagentid) . '</strong>. '
        . get_string('custom_agent_active_warning2', 'block_mistralagent')
        . '</div>';
}

if (empty($adminagents)) {
    echo $OUTPUT->notification(get_string('noagents', 'block_mistralagent'), 'warning');
} else {
    echo html_writer::start_tag('form', ['method' => 'post', 'class' => 'mform']);
    echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()]);
    echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'blockinstanceid', 'value' => $blockinstanceid]);
    echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'courseid', 'value' => $courseid]);

    echo html_writer::tag('label', get_string('selectagent', 'block_mistralagent'),
        ['class' => 'font-weight-bold mb-2 d-block']);
    echo '<div class="row">';
    foreach ($adminagents as $agent) {
        $checked = ((int)$currentadminagentid === (int)$agent->id) ? ' checked' : '';
        $border  = $checked ? ' border-primary' : '';
        echo '<div class="col-md-6 mb-3">';
        echo '<label class="card h-100 mb-0' . $border . '"'
            . ' style="cursor:pointer;transition:all .15s">';
        echo '<div class="card-body">';
        echo '<div class="d-flex align-items-start">';
        echo '<input type="radio" name="agentid" value="' . (int)$agent->id . '"'
            . ' class="mt-1 mr-2"' . $checked . '>';
        echo '<div>';
        echo '<strong>' . htmlspecialchars($agent->name) . '</strong>';
        if (!empty($agent->description)) {
            echo '<p class="text-muted small mb-0 mt-1">'
                . htmlspecialchars($agent->description) . '</p>';
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</label>';
        echo '</div>';
    }
    echo '</div>';

    echo html_writer::start_div('form-group mt-2');
    echo html_writer::empty_tag('input', ['type' => 'submit', 'name' => 'save_admin',
        'value' => get_string('savechanges'), 'class' => 'btn btn-primary']);
    echo ' ';
    echo html_writer::link(new moodle_url('/course/view.php', ['id' => $courseid]),
        get_string('cancel'), ['class' => 'btn btn-secondary']);
    echo html_writer::end_div();
    echo html_writer::end_tag('form');
}
echo '</div>'; // End tab-admin.

// Personal key tab.
echo '<div class="tab-pane fade ' . $tabcustom . '" id="tab-custom" role="tabpanel">';
echo '<p class="text-muted mb-3">' . get_string('use_own_apikey_desc', 'block_mistralagent') . '</p>';

// Currently configured personal agent.
if ($usecustom && !empty($currentagentid)) {
    echo '<div class="alert alert-success mb-3">'
        . '<i class="fa fa-check-circle mr-1"></i>'
        . get_string('current_custom_agent', 'block_mistralagent') . ' : '
        . '<strong>' . htmlspecialchars($currentagentname ?: $currentagentid) . '</strong>'
        . ($currentagentdesc ? '<br><small class="text-muted">'
            . htmlspecialchars($currentagentdesc) . '</small>' : '')
        . '<br><code class="small">' . htmlspecialchars($currentagentid) . '</code>'
        . '</div>';
}

// Button linking to the agent selection page.
echo '<a href="' . $myagentsurl->out(false) . '" class="btn btn-primary mr-2">'
    . '<i class="fa fa-key mr-1"></i>'
    . get_string('configure_own_agent', 'block_mistralagent')
    . '</a>';

// Button to deselect the personal agent and revert to admin agents.
if ($usecustom) {
    echo html_writer::start_tag('form', ['method' => 'post', 'class' => 'd-inline']);
    echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()]);
    echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'blockinstanceid', 'value' => $blockinstanceid]);
    echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'courseid', 'value' => $courseid]);
    $deselectlabel   = get_string('deselect_custom_agent', 'block_mistralagent');
    $deselectconfirm = get_string('confirm_deselect_custom', 'block_mistralagent');
    $deselectconfirm = str_replace("'", "\'", $deselectconfirm);
    echo '<button type="submit" name="deselect_custom" value="1" class="btn btn-outline-danger"'
        . ' onclick="return confirm(\'' . $deselectconfirm . '\')">'
        . '<i class="fa fa-times mr-1"></i> ' . $deselectlabel
        . '</button>';
    echo html_writer::end_tag('form');
}

echo '<p class="text-muted mt-2 small">'
    . get_string('configure_own_agent_hint', 'block_mistralagent') . '</p>';

echo '</div>'; // End tab-custom.
echo '</div>'; // End tab-content.

echo $OUTPUT->footer();
