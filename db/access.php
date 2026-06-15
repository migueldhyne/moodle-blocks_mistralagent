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
 * Capabilities for the Mistral Agent block.
 *
 * Risk bitmask rationale
 * ──────────────────────
 * RISK_SPAM      – addinstance: the block sends messages to an external API;
 *                  an abusive user could use it to generate spam via the quota.
 * RISK_CONFIG    – configureagent / manageagents / managequotas: these change
 *                  plugin behaviour or cost (API calls).
 * RISK_PERSONAL  – viewconversations: exposes private student conversation data.
 *
 * Note: myaddinstance has been removed because applicable_formats() in
 * block_mistralagent.php no longer includes 'my' (dashboard).  Keeping a
 * capability for a format the block cannot appear in would be misleading.
 *
 * @package    block_mistralagent
 * @copyright  2026 Miguël Dhyne <miguel.dhyne@gmail.com>
 * @author     Miguël Dhyne <miguel.dhyne@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = [

    /*
     * Add block to a course page. RISK_SPAM: the block proxies student messages to the Mistral API. An abusive
     * teacher could configure an agent that triggers unwanted API usage, but cannot inject HTML/JS through the
     * block config itself. RISK_XSS is therefore NOT warranted here.
     */
    'block/mistralagent:addinstance' => [
        'riskbitmask'          => RISK_SPAM,
        'captype'              => 'write',
        'contextlevel'         => CONTEXT_BLOCK,
        'archetypes'           => [
            'editingteacher' => CAP_ALLOW,
            'manager'        => CAP_ALLOW,
        ],
        'clonepermissionsfrom' => 'moodle/site:manageblocks',
    ],

    /*
     * Use the AI chatbot. No risk flag: reading/chatting is a standard course activity. Quota enforcement
     * (block_mistralagent_quotas table) prevents abuse.
     */
    'block/mistralagent:use' => [
        'captype'      => 'read',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes'   => [
            'student'        => CAP_ALLOW,
            'teacher'        => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager'        => CAP_ALLOW,
        ],
    ],

    /*
     * Configure the AI agent for a course. RISK_CONFIG: choosing an agent determines which Mistral model is
     * used and which RAG resources are indexed — affects API cost and content. Also controls the context
     * preset (Light / Standard / Full) via the block instance configuration form (edit_form.php).
     */
    'block/mistralagent:configureagent' => [
        'riskbitmask'  => RISK_CONFIG,
        'captype'      => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes'   => [
            'editingteacher' => CAP_ALLOW,
            'manager'        => CAP_ALLOW,
        ],
    ],

    /*
     * View student conversations. RISK_PERSONAL: grants access to private message content between students and
     * the AI assistant.  Restricted to teaching staff.
     */
    'block/mistralagent:viewconversations' => [
        'riskbitmask'  => RISK_PERSONAL,
        'captype'      => 'read',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes'   => [
            'teacher'        => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager'        => CAP_ALLOW,
        ],
    ],

    /*
     * Manage Mistral agents (admin level). RISK_CONFIG: creates/edits/deletes Mistral agents used site-wide.
     * System-level capability — managers only.
     */
    'block/mistralagent:manageagents' => [
        'riskbitmask'  => RISK_CONFIG,
        'captype'      => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes'   => [
            'manager' => CAP_ALLOW,
        ],
    ],

    /*
     * Manage per-user message quotas. RISK_CONFIG: modifying a quota can override the site-wide default set by
     * the administrator, potentially allowing unlimited API usage. Enforced both here and inside
     * manager::set_user_quota() to prevent bypass by future callers that skip the page-level check.
     */
    'block/mistralagent:managequotas' => [
        'riskbitmask'  => RISK_CONFIG,
        'captype'      => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes'   => [
            'editingteacher' => CAP_ALLOW,
            'manager'        => CAP_ALLOW,
        ],
    ],
];
