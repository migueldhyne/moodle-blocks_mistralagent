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
 * English language strings for block_mistralagent.
 *
 * @package    block_mistralagent
 * @copyright  2026 Miguël Dhyne <miguel.dhyne@gmail.com>
 * @author     Miguël Dhyne <miguel.dhyne@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['add_json'] = 'Add JSON';
$string['addagent'] = 'Add new agent';
$string['addagent_manual'] = 'Enter manually';
$string['added_chunks'] = 'Added ({$a} chunks).';
$string['addresource'] = 'Add a resource';
$string['admin_apikey_configured'] = 'The administrator API key is already configured in the plugin settings. Click "Fetch my'
                                       . ' agents" to use it directly.';
$string['agent_selected'] = 'Selected agent';
$string['agentdeleted'] = 'Agent deleted successfully.';
$string['agentdescription'] = 'Description';
$string['agentdescription_help'] = 'A description of what this agent does.';
$string['agentenabled'] = 'Enabled';
$string['agentid'] = 'Mistral Agent ID';
$string['agentid_help'] = 'The agent ID from Mistral (e.g., ag_019af389bf31729094a3da9a3d169afd)';
$string['agentinuse'] = 'This agent cannot be deleted because it is used in one or more courses.';
$string['agentname'] = 'Agent name';
$string['agentname_help'] = 'A friendly name to identify this agent.';
$string['agentnotfound'] = 'Agent not found.';
$string['agents'] = 'Agents';
$string['agents_found'] = 'agents found';
$string['agentsaved'] = 'Agent saved successfully.';
$string['allusers'] = 'All users';
$string['analysisprefix'] = 'Analysis:';
$string['api_error'] = 'Error communicating with Mistral API';
$string['apierror'] = 'Error communicating with Mistral: {$a}';
$string['apikey'] = 'Mistral API Key';
$string['apikey_already_saved'] = 'API key already saved — leave empty to keep it';
$string['apikey_desc'] = 'Enter your Mistral API key. This key will be used for all agent requests.';
$string['apikey_empty'] = 'The API key cannot be empty.';
$string['apikey_invalid'] = 'Invalid API key or not recognised by Mistral.';
$string['attachfile'] = 'Attach a file';
$string['attachfilelimit'] = 'Limit: {$a} KB of extracted text.';
$string['attachfiletip'] = 'Accepted formats: TXT, JSON, DOCX, PDF, JPG, PNG, GIF, WEBP.';
$string['backtosettings'] = 'Back to administrator settings';
$string['chatwithai'] = 'Chat with AI Assistant';
$string['chunks'] = 'chunks';
$string['cleanup_button'] = 'Clean up invalid resources';
$string['cleanup_confirm'] = 'Clean up invalid resources?';
$string['cleanup_done'] = '{$a} resource(s) cleaned up.';
$string['cleanup_invalid_content'] = 'Invalid content — use JSON or paste text instead.';
$string['cleanup_no_content'] = 'No content found — use JSON or paste text instead.';
$string['cleanup_nothing'] = 'Nothing to clean up.';
$string['config_consequence_filecontent_chars'] = '<strong>File content size:</strong> larger = students can attach bigger'
                                                    . ' documents, but very large files slow down the '
                                                  . 'response.';
$string['config_consequence_history_chars'] = '<strong>Conversation history size:</strong> larger = the AI remembers more of the'
                                                . ' conversation, but uses more tokens per '
                                              . 'request (higher cost).';
$string['config_consequence_history_messages'] = '<strong>History message count:</strong> higher = more past messages are kept,'
                                                   . ' helping the AI follow long conversations, '
                                                 . 'but increases token usage.';
$string['config_consequence_rag_chunks'] = '<strong>Document chunks (RAG):</strong> more chunks = the AI has access to more'
                                             . ' passages from indexed course documents, '
                                           . 'improving accuracy on content-heavy courses.';
$string['config_max_chunks'] = 'Chunk limit for this block';
$string['config_max_chunks_help'] = 'Leave 0 to use the global value set by the administrator. Enter a value between 1 and 200 to'
                                      . ' override for this block only.';
$string['config_max_chunks_hint'] = 'Current global value: {\$a} chunks. Enter 0 to use it, or a value between 1 and 200 to'
                                      . ' override.';
$string['config_max_chunks_invalid'] = 'Please enter a positive integer (or 0 to use the global value).';
$string['config_preset'] = 'Context preset';
$string['config_preset_detail_intro'] = 'This preset applies the values shown in the table above. Choose a lighter preset to'
                                          . ' reduce cost and response time, or a fuller '
                                        . 'preset to give the AI more context.';
$string['config_preset_help'] = 'Choose how much context the AI assistant will use for this course. <strong>Light</strong> is'
                                  . ' faster and cheaper. <strong>Standard</strong> '
                                . 'is suitable for most courses. <strong>Full</strong> gives the AI the most context but may'
                                . ' increase response'
                                . ' time.';
$string['config_rag_header'] = 'RAG settings';
$string['config_summary_intro'] = 'Each preset adjusts four parameters. Larger values give the AI more context to work with, but'
                                    . ' increase API cost and response time.';
$string['config_summary_title'] = 'What does each preset change?';
$string['configsaved'] = 'Configuration saved successfully.';
$string['configure_own_agent'] = 'Configure my personal agent';
$string['configure_own_agent_hint'] = 'You will be taken to a page where you can enter your API key and choose from your Mistral'
                                        . ' agents.';
$string['configureagent'] = 'Configure agent';
$string['confirm_delete_user_key'] = 'Are you sure you want to delete your saved API key?';
$string['confirm_deselect_custom'] = 'Are you sure you want to deactivate your personal agent and revert to the administrator'
                                       . ' agents?';
$string['confirmdelete'] = 'Delete this resource?';
$string['confirmdeleteagent'] = 'Are you sure you want to delete this agent?';
$string['confirmdeleteconversation'] = 'Are you sure you want to delete this conversation?';
$string['content_invalid'] = 'Invalid content (binary data) — re-index with JSON or paste text.';
$string['content_valid'] = 'Valid content';
$string['continue'] = 'Continue';
$string['conversationdeleted'] = 'Conversation deleted.';
$string['conversationfrom'] = 'Conversation from {$a}';
$string['conversationhistory'] = 'Conversation History';
$string['conversationwith'] = 'Conversation with {$a}';
$string['copied'] = 'Copied!';
$string['copy'] = 'Copy';
$string['copyright_reminder'] = 'Only upload documents for which you hold the rights or have an appropriate licence. Document'
                                  . ' text is sent to the Mistral API for embedding.';
$string['copyright_reminder_title'] = 'Copyright reminder';
$string['courseconfig'] = 'Configure AI Assistant for this course';
$string['current_custom_agent'] = 'Current personal agent';
$string['custom_agent_active_warning'] = 'A personal agent is currently active:';
$string['custom_agent_active_warning2'] = 'Selecting an agent below will replace it.';
$string['custom_agent_deselected'] = 'Personal agent deselected. Administrator agents are now active again.';
$string['custom_agent_missing'] = 'Please enter an API key and select an agent.';
$string['custom_agentid_invalid'] = 'The agent ID must start with ag: or ag_ followed by at least 8 characters.';
$string['defaultquota'] = 'Default message quota';
$string['defaultquota_desc'] = 'Default number of messages a student can send per period. Enter 0 for unlimited.';
$string['delete_user_apikey'] = 'Delete my key';
$string['deleteagent'] = 'Delete agent';
$string['deselect_custom_agent'] = 'Deselect personal agent';
$string['editagent'] = 'Edit agent';
$string['emptytext'] = 'Text cannot be empty.';
$string['err_api_400'] = 'Invalid request: {$a}';
$string['err_api_401'] = 'Invalid or expired API key.';
$string['err_api_403'] = 'Access denied to the Mistral API.';
$string['err_api_404'] = 'Resource not found (404): {$a}';
$string['err_api_429'] = 'Too many requests — please wait a few seconds.';
$string['err_api_5xx'] = 'Mistral server temporarily unavailable.';
$string['err_api_curl'] = 'Cannot reach the Mistral API. Please check the server network connection.';
$string['err_api_curl_download'] = 'cURL error while downloading generated file: {$a}';
$string['err_api_default'] = 'API error ({$a->code}): {$a->message}';
$string['err_api_empty_response'] = 'The Mistral API returned an empty response.';
$string['err_api_http_download'] = 'File download failed (HTTP {$a}).';
$string['err_api_json'] = 'Invalid response from the Mistral API (malformed JSON).';
$string['err_api_network'] = 'Network connection error (cURL {$a}).';
$string['err_api_no_content'] = 'The Mistral API returned no text or image content.'
    . ' Check the logs (DEBUG_DEVELOPER mode) for the full response.';
$string['err_api_no_image'] = 'The image generation API returned no image.';
$string['err_api_no_response'] = 'Cannot reach the Mistral API (no HTTP response).';
$string['err_api_timeout'] = 'The request timed out. The message may have been too long.';
$string['err_docx_empty'] = 'Cannot extract text from the DOCX file.';
$string['err_docx_xml'] = 'Cannot read word/document.xml in the DOCX file.';
$string['err_docx_zip'] = 'Cannot open DOCX file (invalid ZIP archive).';
$string['err_file_corrupt'] = 'Invalid file content (corrupt base64).';
$string['err_file_empty'] = 'File is empty or unreadable.';
$string['err_file_magic'] = 'The file does not match the declared type \'{\$a}\' (incorrect binary signature). Check that you'
                              . ' have not renamed a file of a different format.';
$string['err_file_too_large'] = 'File content exceeds the allowed limit ({\$a} KB of extracted text). Try a shorter file, or ask'
                                  . ' your teacher to increase the course preset.';
$string['err_file_type'] = 'Unsupported file type: \'{$a}\'. Accepted formats: TXT, JSON, DOCX, PDF, JPG, PNG, GIF, WEBP.';
$string['err_file_upload'] = 'Error uploading file: {$a}';
$string['err_msg_required'] = 'A message or file is required.';
$string['err_pdf_extract'] = 'Cannot extract text from the PDF. Possible causes: scanned PDF without text layer, encrypted PDF,'
                               . ' or incompatible custom font. Tip: copy '
                                  . 'and paste the text directly into the message box.';
$string['err_pdf_extract_ocr'] = 'Cannot extract text from the PDF. The file may be scanned (image) or protected. Tick "Use'
                                   . ' Mistral OCR" when uploading.';
$string['err_upload_cant_write'] = 'Cannot write temporary file.';
$string['err_upload_form_size'] = 'File too large (form limit).';
$string['err_upload_ini_size'] = 'File too large (php.ini limit).';
$string['err_upload_no_file'] = 'No file received.';
$string['err_upload_no_tmp'] = 'Missing temporary folder on the server.';
$string['err_upload_partial'] = 'File only partially uploaded. Please try again.';
$string['errorcommunication'] = 'Communication error with the server.';
$string['exportconversation'] = 'Export';
$string['extracting'] = 'Extracting…';
$string['extraction_failed_use_json'] = 'Extraction failed — use JSON or paste text instead.';
$string['extraction_invalid_content'] = 'Extraction produced invalid content.';
$string['fetch_agents'] = 'Fetch my agents';
$string['fetch_agents_saved'] = 'Fetch my agents';
$string['file_context_analyse'] = 'The user would like you to analyse this file.';
$string['file_context_end'] = '--- FILE END ---';
$string['file_context_image_analyse'] = 'The user has attached an image. Please analyse it.';
$string['file_context_intro'] = 'Here is the content of the file \'{$a}\' attached by the user:';
$string['file_context_question'] = 'User\'s question/request: {$a}';
$string['file_context_start'] = '--- FILE START ---';
$string['file_truncated'] = 'truncated';
$string['file_truncated_marker'] = '[... content truncated here — the limit for this level is {$a} K characters ...]';
$string['filefailed'] = 'Extraction failed';
$string['filenotready'] = 'Please wait for the file to finish loading, or click "Paste text".';
$string['fileready'] = 'Ready';
$string['filetoobig'] = 'The file is too large. Maximum size: 5 MB.';
$string['generated_image_alt'] = 'Generated image';
$string['indexed_success'] = 'Indexed successfully ({$a} chunks).';
$string['instanceconfig_header'] = 'AI Assistant — Context settings';
$string['invalidresponse'] = 'Invalid response from Mistral API.';
$string['invalidtext'] = 'Invalid text — make sure you are pasting readable text.';
$string['json_format_hint'] = 'Format: <code>{"title":"…", "content":"…"}</code>';
$string['json_recommended'] = 'The <strong>JSON</strong> format is the most reliable. For PDFs, use the <strong>"Direct'
                                . ' text"</strong> tab if extraction fails.';
$string['lastactivity'] = 'Last activity';
$string['manageagents'] = 'Manage Agents';
$string['manageagents_link'] = 'Click here to manage Mistral agents';
$string['manageresources'] = 'Manage resources';
$string['max_embedding_chunks'] = 'Global chunk limit per document';
$string['max_embedding_chunks_desc'] = 'Maximum number of chunks sent to the Mistral API for vectorisation when indexing a'
                                         . ' document (between 1 and 200). Default: 50 (~30 '
                                       . 'pages). Increasing this value increases API cost and indexing time.';
$string['max_preset'] = 'Maximum preset for teachers';
$string['max_preset_desc'] = 'Teachers can choose any preset up to and including this level. Lowering this value caps all'
                               . ' existing course configurations silently.';
$string['messages'] = 'Messages';
$string['mistralagent:addinstance'] = 'Add a new Mistral AI Assistant block';
$string['mistralagent:configureagent'] = 'Configure the agent for a course';
$string['mistralagent:manageagents'] = 'Manage Mistral agents';
$string['mistralagent:managequotas'] = 'Manage user quotas';
$string['mistralagent:myaddinstance'] = 'Add a new Mistral AI Assistant block to Dashboard';
$string['mistralagent:use'] = 'Use the Mistral AI Assistant';
$string['mistralagent:viewconversations'] = 'View student conversations';
$string['model_embed'] = 'Embeddings model';
$string['model_embed_desc'] = 'Model used to generate embedding vectors when indexing RAG resources (/v1/embeddings endpoint).'
                                . ' Current recommended value: '
                              . '<code>mistral-embed</code>.';
$string['model_image'] = 'Image generation model';
$string['model_image_desc'] = 'Model used to generate images from text descriptions (/v1/images/generations endpoint). Current'
                                . ' recommended value: <code>pixtral-1-25-01</code>.';
$string['model_ocr'] = 'OCR model';
$string['model_ocr_desc'] = 'Model used to extract text from scanned PDF documents (/v1/ocr endpoint). Current recommended value:'
                              . ' <code>mistral-ocr-latest</code>.';
$string['model_vision'] = 'Image analysis model (vision)';
$string['model_vision_desc'] = 'Model used to analyse images attached by students in the chat (/v1/chat/completions endpoint).'
                                 . ' Must be a multimodal model supporting vision. '
                               . 'Current recommended value: <code>pixtral-12b-2409</code>.';
$string['my_agents_desc'] = 'Enter your personal Mistral API key to retrieve your agents and choose one for this block.';
$string['my_agents_title'] = 'My personal Mistral agents';
$string['myconversations'] = 'My conversations';
$string['name_and_content_required'] = 'Name and content are required.';
$string['name_optional'] = 'Name (optional)';
$string['newconversation'] = 'New conversation';
$string['nmessages'] = '{$a} messages';
$string['no_agents_found'] = 'No agents found for this key. Create an agent at console.mistral.ai.';
$string['noagentavailable'] = 'The AI assistant is not available for this course.';
$string['noagentconfigured'] = 'No AI assistant has been configured for this course.';
$string['noagents'] = 'No agents have been created yet.';
$string['noapikey'] = 'Mistral API key is not configured. Please contact the administrator.';
$string['nochunks'] = 'No chunks found.';
$string['noconversations'] = 'No conversations found.';
$string['noconversationsyet'] = 'No conversations yet. Start chatting!';
$string['noresources'] = 'No resources yet.';
$string['ocr_failed'] = 'OCR extraction failed. The file may be encrypted or corrupted.';
$string['openchat'] = 'Open chat';
$string['own_agentid_hint'] = 'Format: ag:xxxxxxxxxxxxxxxx — find this ID in the Mistral console on your agent\'s page.';
$string['own_agentid_label'] = 'Your Mistral agent ID';
$string['own_agentname_hint'] = 'Name displayed in the block to identify this agent. If empty, the ID will be used.';
$string['own_agentname_label'] = 'Display name (optional)';
$string['own_agentname_placeholder'] = 'E.g. My chemistry assistant';
$string['own_apikey_hint'] = 'Your Mistral API key — get it at console.mistral.ai';
$string['own_apikey_info'] = 'Your API key is encrypted before storage and is only used for Mistral API calls from this block.';
$string['own_apikey_label'] = 'Personal Mistral API key';
$string['pastemodalintro'] = 'Automatic extraction failed. Open your file, select all (Ctrl+A), copy (Ctrl+C) and paste below:';
$string['pastemodaltitle'] = 'Paste file content';
$string['pasteplaceholder'] = 'Paste the text here…';
$string['pastetext'] = 'Paste text';
$string['pdf_extraction_warning'] = 'PDF extraction may fail. Prefer JSON or Direct text.';
$string['period_daily'] = 'Daily';
$string['period_monthly'] = 'Monthly';
$string['period_weekly'] = 'Weekly';
$string['pluginname'] = 'Mistral AI Assistant';
$string['preset_col_filecontent_chars'] = 'Max file size';
$string['preset_col_history_chars'] = 'History size';
$string['preset_col_history_messages'] = 'History messages';
$string['preset_col_name'] = 'Preset';
$string['preset_col_rag_chunks'] = 'RAG chunks';
$string['preset_exceeds_max'] = 'The selected preset exceeds the maximum allowed by your administrator ({$a}).';
$string['preset_full'] = 'Full';
$string['preset_light'] = 'Light';
$string['preset_standard'] = 'Standard';
$string['privacy:metadata:block_mistralagent_convs'] = 'Stores conversations between users and the AI assistant.';
$string['privacy:metadata:block_mistralagent_convs:agentid'] = 'The ID of the Mistral agent used for this conversation.';
$string['privacy:metadata:block_mistralagent_convs:timecreated'] = 'The date and time the conversation was started.';
$string['privacy:metadata:block_mistralagent_convs:timemodified'] = 'The date and time the conversation was last updated.';
$string['privacy:metadata:block_mistralagent_convs:title'] = 'An optional title summarising the conversation.';
$string['privacy:metadata:block_mistralagent_convs:userid'] = 'The ID of the user who owns the conversation.';
$string['privacy:metadata:block_mistralagent_msgs'] = 'Stores individual messages exchanged during a conversation.';
$string['privacy:metadata:block_mistralagent_msgs:content'] = 'The full text of the message (question or answer).';
$string['privacy:metadata:block_mistralagent_msgs:role'] = 'The author of the message: "user" or "assistant".';
$string['privacy:metadata:block_mistralagent_msgs:timecreated'] = 'The date and time the message was sent.';
$string['privacy:metadata:block_mistralagent_quotas'] = 'Stores per-user message usage counters for each course.';
$string['privacy:metadata:block_mistralagent_quotas:messages_limit'] = 'The maximum number of messages allowed per period (null ='
                                                                         . ' unlimited).';
$string['privacy:metadata:block_mistralagent_quotas:messages_used'] = 'The number of messages sent by the user during the current'
                                                                        . ' quota period.';
$string['privacy:metadata:block_mistralagent_quotas:period_start'] = 'The start date of the current quota period.';
$string['privacy:metadata:block_mistralagent_quotas:timemodified'] = 'The date and time the quota record was last updated.';
$string['privacy:metadata:block_mistralagent_quotas:userid'] = 'The ID of the user this quota belongs to.';
$string['privacy:metadata:externalsystem'] = 'Message content is sent to the Mistral AI API (mistral.ai) for inference. No data'
                                               . ' is permanently stored by this plugin on the '
                                             . 'external service.';
$string['privacy:metadata:externalsystem:messages'] = 'The conversation history and the current user message, including any'
                                                        . ' embedded file content.';
$string['privacy:quota_path'] = 'Usage Quota';
$string['quality'] = 'Quality';
$string['quotaexceeded'] = 'You have reached your message limit for this period.';
$string['quotamanagement'] = 'Quota Management';
$string['quotaperiod'] = 'Quota period';
$string['quotaperiod_desc'] = 'Period after which the quota resets.';
$string['quotareset'] = 'Quota has been reset.';
$string['quotastatus'] = 'Messages: {$a->used}/{$a->limit}';
$string['quotaunlimited'] = 'Unlimited';
$string['quotaupdated'] = 'Quota updated successfully.';
$string['rag_context_footer'] = 'Use this information to answer the student\'s question.'
    . ' If the information is insufficient, say so clearly.';
$string['rag_question_separator'] = '--- STUDENT QUESTION ---';
$string['recommendation'] = 'Recommendation';
$string['reindex'] = 'Re-index';
$string['removefile'] = 'Remove file';
$string['resetquota'] = 'Reset quota';
$string['resource_added'] = 'Resource added.';
$string['resource_added_success'] = 'Resource added successfully.';
$string['resourceadded'] = 'Resource added and indexed successfully.';
$string['resourcecontent'] = 'Content';
$string['resourcedeleted'] = 'Resource deleted successfully.';
$string['resourcename'] = 'Name';
$string['resourcenotfound'] = 'Resource not found or does not belong to this course.';
$string['resourcereindexed'] = 'Resource reindexed successfully.';
$string['resourcereindexfailed'] = 'Re-indexing failed — see resource status for details.';
$string['resourcetype'] = 'Type';
$string['saveandindex'] = 'Save and index';
$string['selectagent'] = 'Select an agent';
$string['selectagent_help'] = 'Choose which AI agent will be available to students in this course.';
$string['send'] = 'Send';
$string['sending'] = 'Sending...';
$string['sessexpired'] = 'Your session has expired — please reload the page.';
$string['setquota'] = 'Set quota';
$string['settings_heading_api'] = 'API Configuration';
$string['settings_heading_manageagents'] = 'Agent management';
$string['settings_heading_manageagents_desc'] = 'Access the Mistral agent management page. This section lets administrators'
                                                  . ' create, edit or delete the agents available in '
                                                . 'the plugin.';
$string['settings_heading_models'] = 'Mistral Models';
$string['settings_heading_models_desc'] = 'Model IDs used by the plugin. Change these values to upgrade to a new model version'
                                            . ' without touching the code. See the <a '
                                          . 'href="https://docs.mistral.ai/getting-started/models/" target="_blank">Mistral'
                                          . ' documentation</a> for available'
                                          . ' models.';
$string['settings_heading_presets'] = 'Context Presets';
$string['settings_heading_presets_desc'] = 'Presets control how much context (conversation history, file content, document'
                                             . ' chunks) is sent to the Mistral API per request. A '
                                           . 'higher preset gives the AI more context but increases API cost and latency. The'
                                           . ' value set here is the <strong>maximum</strong>'
                                           . ' '
                                           . 'teachers may select for their course.';
$string['settings_heading_quota'] = 'Message Quotas';
$string['settings_heading_rag'] = 'RAG indexing (embeddings)';
$string['settings_heading_rag_desc'] = 'Controls the maximum number of text chunks indexed with embedding vectors per document.'
                                         . ' Beyond this limit, keyword-based search is '
                                       . 'used instead.';
$string['show_hide_key'] = 'Show/hide key';
$string['showdescription'] = 'Show description';
$string['startnewconversation'] = 'Start a new conversation';
$string['status'] = 'Status';
$string['status_error'] = 'Error';
$string['status_indexed'] = 'Indexed';
$string['status_pending'] = 'Pending';
$string['status_processing'] = 'Processing';
$string['step1_apikey'] = 'Step 1 — Enter your Mistral API key';
$string['step2_selectagent'] = 'Step 2 — Choose your agent';
$string['student'] = 'Student';
$string['tab_directtext'] = 'Direct text';
$string['tab_file'] = 'File';
$string['tab_url'] = 'URL';
$string['text_paste_placeholder'] = 'Paste text here (Ctrl+A, Ctrl+C from your PDF)';
$string['type'] = 'Type';
$string['typemessage'] = 'Type your message...';
$string['unexpected_server_response'] = 'Unexpected server response.';
$string['unlimited'] = 'Unlimited';
$string['upload_failed'] = 'Upload failed.';
$string['uploadfailed'] = 'File upload failed.';
$string['use_admin_agents'] = 'Use administrator agents';
$string['use_admin_agents_desc'] = 'Select one of the Mistral agents configured by the site administrator.';
$string['use_different_key'] = 'Enter a new API key to replace it:';
$string['use_ocr_hint'] = 'OCR automatically sends PDF files to the Mistral OCR API to extract text. If OCR fails, the plugin'
                            . ' falls back to native extraction.';
$string['use_ocr_label'] = 'Automatic Mistral OCR for PDF files';
$string['use_own_apikey'] = 'Use my own Mistral API key';
$string['use_own_apikey_desc'] = 'Enter your personal Mistral API key to use your own agents.';
$string['user_apikey_deleted'] = 'Your API key has been deleted.';
$string['user_apikey_saved'] = 'Your API key has been saved.';
$string['user_apikey_saved_badge'] = 'Key saved';
$string['user_apikey_stored'] = 'API key securely stored:';
$string['userquota'] = 'User quota';
$string['viewallconversations'] = 'View all';
$string['viewcontent'] = 'View content';
$string['viewconversation'] = 'View conversation';
$string['viewhistory'] = 'View conversations';
$string['vision_file_fallback'] = '[Generated image — file_id: {$a}]';
$string['vision_intro'] = 'The user has attached an image. Here is the analysed content:';
$string['vision_intro_sep_end'] = '--- END DESCRIPTION ---';
$string['vision_intro_sep_start'] = '--- IMAGE DESCRIPTION ---';
$string['vision_prompt_describe'] = 'Describe the image precisely and exhaustively'
    . ' (visible text, objects, context, figures if present).';
$string['vision_prompt_with_question'] = 'The user is asking: "{$a}"'
    . ' — describe the image precisely and answer the question.';
$string['vision_user_analyse'] = 'The user would like you to analyse this image.';
$string['vision_user_message'] = 'User\'s message: {$a}';
$string['youtube_added'] = 'YouTube resource added (subtitle extraction is limited).';
