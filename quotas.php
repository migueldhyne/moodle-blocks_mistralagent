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
 * Manage per-user message quotas for a course.
 *
 * @package    block_mistralagent
 * @copyright  2026 Miguël Dhyne <miguel.dhyne@gmail.com>
 * @author     Miguël Dhyne <miguel.dhyne@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
use block_mistralagent\manager;

$courseid = required_param('courseid', PARAM_INT);
$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
require_login($course);

$context = context_course::instance($courseid);
require_capability('block/mistralagent:managequotas', $context);

$PAGE->set_url(new moodle_url('/blocks/mistralagent/quotas.php', ['courseid' => $courseid]));
$PAGE->set_context($context);
$PAGE->set_title(get_string('quotamanagement', 'block_mistralagent'));
$PAGE->set_heading($course->fullname);
$PAGE->set_pagelayout('incourse');

// Handle form submission.
if (optional_param('save', false, PARAM_BOOL) && confirm_sesskey()) {
    $userid = required_param('userid', PARAM_INT);
    // PARAM_ALPHANUMEXT covers '', 'unlimited', and integer strings safely.
    $rawlimit = optional_param('limit', '', PARAM_ALPHANUMEXT);

    if ($rawlimit === '' || $rawlimit === 'unlimited') {
        manager::set_user_quota($userid, $courseid, null);
    } else {
        manager::set_user_quota($userid, $courseid, (int)$rawlimit);
    }

    redirect($PAGE->url, get_string('quotaupdated', 'block_mistralagent'));
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('quotamanagement', 'block_mistralagent'));

// Get enrolled users.
$users = get_enrolled_users($context, 'block/mistralagent:use', 0, 'u.*', 'u.lastname, u.firstname');

if (empty($users)) {
    echo $OUTPUT->notification(get_string('nousers'), 'info');
} else {
    $table = new html_table();
    $table->head = [
        get_string('student', 'block_mistralagent'),
        get_string('quotastatus', 'block_mistralagent', (object)['used' => '', 'limit' => '']),
        get_string('actions'),
    ];
    $table->attributes['class'] = 'table table-striped';

    foreach ($users as $user) {
        $quota = manager::check_quota($user->id, $courseid);

        if ($quota['unlimited']) {
            $status = get_string('unlimited', 'block_mistralagent');
        } else {
            $status = $quota['used'] . ' / ' . $quota['limit'];
        }

        // Quick form for setting quota.
        $form = html_writer::start_tag('form', ['method' => 'post', 'class' => 'form-inline']);
        $form .= html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()]);
        $form .= html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'userid', 'value' => $user->id]);
        $form .= html_writer::empty_tag('input', [
            'type' => 'number',
            'name' => 'limit',
            'class' => 'form-control form-control-sm mr-2',
            'style' => 'width: 80px;',
            'value' => $quota['unlimited'] ? '' : $quota['limit'],
            'placeholder' => '∞',
            'min' => 0,
        ]);
        $form .= html_writer::empty_tag('input', [
            'type' => 'submit',
            'name' => 'save',
            'value' => get_string('setquota', 'block_mistralagent'),
            'class' => 'btn btn-sm btn-primary',
        ]);
        $form .= html_writer::end_tag('form');

        $table->data[] = [
            fullname($user),
            $status,
            $form,
        ];
    }

    echo html_writer::table($table);
}

echo html_writer::link(
    new moodle_url('/course/view.php', ['id' => $courseid]),
    get_string('back'),
    ['class' => 'btn btn-secondary mt-3']
);

echo $OUTPUT->footer();
