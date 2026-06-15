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
 * View a single conversation (teacher/admin).
 *
 * @package    block_mistralagent
 * @copyright  2026 Miguël Dhyne <miguel.dhyne@gmail.com>
 * @author     Miguël Dhyne <miguel.dhyne@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
use block_mistralagent\manager;

$id = required_param('id', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);

$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
require_login($course);

$context = context_course::instance($courseid);
require_capability('block/mistralagent:viewconversations', $context);

// Get conversation.
$conversation = $DB->get_record('block_mistralagent_convs', ['id' => $id, 'courseid' => $courseid], '*', MUST_EXIST);
$user = $DB->get_record('user', ['id' => $conversation->userid], '*', MUST_EXIST);

$PAGE->set_url(new moodle_url('/blocks/mistralagent/viewconversation.php', ['id' => $id, 'courseid' => $courseid]));
$PAGE->set_context($context);
$PAGE->set_title(get_string('conversationwith', 'block_mistralagent', fullname($user)));
$PAGE->set_heading($course->fullname);
$PAGE->set_pagelayout('incourse');

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('conversationwith', 'block_mistralagent', fullname($user)));

// Get messages.
$messages = manager::get_messages($id);

if (empty($messages)) {
    echo $OUTPUT->notification(get_string('noconversations', 'block_mistralagent'), 'info');
} else {
    echo html_writer::start_div('conversation-viewer');

    foreach ($messages as $msg) {
        $class = $msg->role === 'user' ? 'alert alert-primary' : 'alert alert-secondary';
        $label = $msg->role === 'user' ? fullname($user) : get_string('pluginname', 'block_mistralagent');

        echo html_writer::start_div($class);
        echo html_writer::tag('strong', $label . ' ');
        echo html_writer::tag('small', userdate($msg->timecreated), ['class' => 'text-muted']);
        echo html_writer::tag('p', nl2br(s($msg->content)), ['class' => 'mb-0 mt-2']);
        echo html_writer::end_div();
    }

    echo html_writer::end_div();
}

echo html_writer::link(
    new moodle_url('/blocks/mistralagent/history.php', ['courseid' => $courseid]),
    get_string('back'),
    ['class' => 'btn btn-secondary mt-3']
);

echo $OUTPUT->footer();
