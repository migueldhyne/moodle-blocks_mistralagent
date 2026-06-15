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
 * External functions and service definitions for block_mistralagent.
 *
 * @package    block_mistralagent
 * @copyright  2026 Miguël Dhyne <miguel.dhyne@gmail.com>
 * @author     Miguël Dhyne <miguel.dhyne@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = [
    'block_mistralagent_send_message' => [
        'classname' => 'block_mistralagent\external\send_message',
        'description' => 'Send a message to the Mistral agent',
        'type' => 'write',
        'ajax' => true,
        'loginrequired' => true,
    ],
    'block_mistralagent_get_conversation' => [
        'classname' => 'block_mistralagent\external\get_conversation',
        'description' => 'Get conversation history',
        'type' => 'read',
        'ajax' => true,
        'loginrequired' => true,
    ],
    'block_mistralagent_get_quota_status' => [
        'classname' => 'block_mistralagent\external\get_quota_status',
        'description' => 'Get current quota status for user',
        'type' => 'read',
        'ajax' => true,
        'loginrequired' => true,
    ],
    'block_mistralagent_new_conversation' => [
        'classname' => 'block_mistralagent\external\new_conversation',
        'description' => 'Start a new conversation',
        'type' => 'write',
        'ajax' => true,
        'loginrequired' => true,
    ],
    'block_mistralagent_extract_file' => [
        'classname' => 'block_mistralagent\external\extract_file',
        'description' => 'Extract text content from uploaded file',
        'type' => 'write',
        'ajax' => true,
        'loginrequired' => true,
    ],
];
