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
 * French language strings for block_mistralagent.
 *
 * @package    block_mistralagent
 * @copyright  2026 Miguël Dhyne <miguel.dhyne@gmail.com>
 * @author     Miguël Dhyne <miguel.dhyne@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['add_json'] = 'Ajouter JSON';
$string['addagent'] = 'Ajouter un nouvel agent';
$string['addagent_manual'] = 'Saisir manuellement';
$string['added_chunks'] = 'Ajouté ({$a} morceaux).';
$string['addresource'] = 'Ajouter une ressource';
$string['admin_apikey_configured'] = 'La clé API de l\'administrateur est déjà configurée dans les paramètres du plugin. Cliquez'
                                       . ' sur "Récupérer mes agents" pour '
                                     . 'l\'utiliser directement.';
$string['agent_selected'] = 'Agent sélectionné';
$string['agentdeleted'] = 'Agent supprimé avec succès.';
$string['agentdescription'] = 'Description';
$string['agentdescription_help'] = 'Une description de ce que fait cet agent.';
$string['agentenabled'] = 'Activé';
$string['agentid'] = 'ID de l\'agent Mistral';
$string['agentid_help'] = 'L\'identifiant de l\'agent depuis Mistral (ex: ag_019af389bf31729094a3da9a3d169afd)';
$string['agentinuse'] = 'Cet agent ne peut pas être supprimé car il est utilisé dans un ou plusieurs cours.';
$string['agentname'] = 'Nom de l\'agent';
$string['agentname_help'] = 'Un nom convivial pour identifier cet agent.';
$string['agentnotfound'] = 'Agent non trouvé.';
$string['agents'] = 'Agents';
$string['agents_found'] = 'agents trouvés';
$string['agentsaved'] = 'Agent enregistré avec succès.';
$string['allusers'] = 'Tous les utilisateurs';
$string['analysisprefix'] = 'Analyse :';
$string['api_error'] = 'Erreur de communication avec l\'API Mistral';
$string['apierror'] = 'Erreur de communication avec Mistral : {$a}';
$string['apikey'] = 'Clé API Mistral';
$string['apikey_already_saved'] = 'Clé API déjà enregistrée — laissez vide pour la conserver';
$string['apikey_desc'] = 'Entrez votre clé API Mistral. Cette clé sera utilisée pour toutes les requêtes des agents.';
$string['apikey_empty'] = 'La clé API ne peut pas être vide.';
$string['apikey_invalid'] = 'Clé API invalide ou non reconnue par Mistral.';
$string['attachfile'] = 'Joindre un fichier';
$string['attachfilelimit'] = 'Limite : {$a} Ko de texte extrait.';
$string['attachfiletip'] = 'Formats acceptés : TXT, JSON, DOCX, PDF, JPG, PNG, GIF, WEBP.';
$string['backtosettings'] = 'Retour aux réglages administrateur';
$string['chatwithai'] = 'Discuter avec l\'Assistant IA';
$string['chunks'] = 'extraits';
$string['cleanup_button'] = 'Nettoyer les ressources invalides';
$string['cleanup_confirm'] = 'Nettoyer les ressources invalides ?';
$string['cleanup_done'] = '{$a} ressource(s) nettoyée(s).';
$string['cleanup_invalid_content'] = 'Contenu invalide — utilisez JSON ou collez le texte.';
$string['cleanup_no_content'] = 'Aucun contenu trouvé — utilisez JSON ou collez le texte.';
$string['cleanup_nothing'] = 'Aucune ressource à nettoyer.';
$string['config_consequence_filecontent_chars'] = '<strong>Taille du fichier :</strong> plus grand = les étudiants peuvent'
                                                    . ' joindre des documents plus volumineux, mais les '
                                                  . 'très grands fichiers ralentissent la réponse.';
$string['config_consequence_history_chars'] = '<strong>Taille de l\'historique :</strong> plus grand = l\'IA se souvient de plus'
                                                . ' de la conversation, mais utilise plus de '
                                              . 'tokens par requête (coût plus élevé).';
$string['config_consequence_history_messages'] = '<strong>Nombre de messages dans l\'historique :</strong> plus élevé = plus de'
                                                   . ' messages passés sont conservés, aidant '
                                                 . 'l\'IA à suivre les longues conversations, mais augmente l\'utilisation des'
                                                 . ' tokens.';
$string['config_consequence_rag_chunks'] = '<strong>Extraits de documents (RAG) :</strong> plus d\'extraits = l\'IA accède à plus'
                                             . ' de passages des documents du cours, '
                                           . 'améliorant la précision sur les cours riches en contenu.';
$string['config_max_chunks'] = 'Limite de chunks pour ce bloc';
$string['config_max_chunks_help'] = 'Laissez 0 pour utiliser la valeur globale définie par l\'administrateur. Entrez une valeur'
                                      . ' entre 1 et 200 pour surcharger uniquement '
                                    . 'pour ce bloc.';
$string['config_max_chunks_hint'] = 'Valeur globale actuelle : {\$a} chunks. Entrez 0 pour l\'utiliser, ou une valeur entre 1 et'
                                      . ' 200 pour surcharger.';
$string['config_max_chunks_invalid'] = 'Veuillez entrer un nombre entier positif (ou 0 pour utiliser la valeur globale).';
$string['config_preset'] = 'Préréglage de contexte';
$string['config_preset_detail_intro'] = 'Ce préréglage applique les valeurs indiquées dans le tableau ci-dessus. Choisissez un'
                                          . ' préréglage plus léger pour réduire le '
                                        . 'coût et le temps de réponse, ou un préréglage plus complet pour donner davantage de'
                                        . ' contexte à'
                                        . ' l\'IA.';
$string['config_preset_help'] = 'Choisissez la quantité de contexte utilisée par l\'assistant IA pour ce cours.'
                                  . ' <strong>Léger</strong> est plus rapide et moins coûteux. '
                                . '<strong>Standard</strong> convient à la plupart des cours. <strong>Complet</strong> donne à'
                                . ' l\'IA le plus de contexte possible, mais peut'
                                . ' '
                                . 'augmenter le temps de réponse.';
$string['config_rag_header'] = 'Paramètres RAG';
$string['config_summary_intro'] = 'Chaque préréglage ajuste quatre paramètres. Des valeurs plus grandes donnent à l\'IA plus de'
                                    . ' contexte, mais augmentent le coût API '
                                  . 'et le temps de réponse.';
$string['config_summary_title'] = 'Que change chaque préréglage ?';
$string['configsaved'] = 'Configuration enregistrée avec succès.';
$string['configure_own_agent'] = 'Configurer mon agent personnel';
$string['configure_own_agent_hint'] = 'Vous serez redirigé vers une page où vous pouvez entrer votre clé API et choisir parmi vos'
                                        . ' agents Mistral.';
$string['configureagent'] = 'Configurer l\'agent';
$string['confirm_delete_user_key'] = 'Êtes-vous sûr de vouloir supprimer votre clé API enregistrée ?';
$string['confirm_deselect_custom'] = 'Êtes-vous sûr de vouloir désactiver votre agent personnel et revenir aux agents de'
                                       . ' l\'administrateur ?';
$string['confirmdelete'] = 'Supprimer cette ressource ?';
$string['confirmdeleteagent'] = 'Êtes-vous sûr de vouloir supprimer cet agent ?';
$string['confirmdeleteconversation'] = 'Êtes-vous sûr de vouloir supprimer cette conversation ?';
$string['content_invalid'] = 'Contenu invalide (données binaires) — réindexez avec JSON ou collez le texte.';
$string['content_valid'] = 'Contenu valide';
$string['continue'] = 'Continuer';
$string['conversationdeleted'] = 'Conversation supprimée.';
$string['conversationfrom'] = 'Conversation du {$a}';
$string['conversationhistory'] = 'Historique des conversations';
$string['conversationwith'] = 'Conversation avec {$a}';
$string['copied'] = 'Copié !';
$string['copy'] = 'Copier';
$string['copyright_reminder'] = 'Ne téléchargez que des documents pour lesquels vous détenez les droits ou disposez d\'une'
                                  . ' licence appropriée. Le texte des documents '
                                . 'est envoyé à l\'API Mistral pour l\'embedding.';
$string['copyright_reminder_title'] = 'Rappel sur les droits d\'auteur';
$string['courseconfig'] = 'Configurer l\'Assistant IA pour ce cours';
$string['current_custom_agent'] = 'Agent personnel actuel';
$string['custom_agent_active_warning'] = 'Un agent personnel est actuellement actif :';
$string['custom_agent_active_warning2'] = 'Sélectionner un agent ci-dessous le remplacera.';
$string['custom_agent_deselected'] = 'Agent personnel désélectionné. Les agents de l\'administrateur sont à nouveau actifs.';
$string['custom_agent_missing'] = 'Veuillez entrer une clé API et sélectionner un agent.';
$string['custom_agentid_invalid'] = 'L\'ID de l\'agent doit commencer par ag: ou ag_ suivi d\'au moins 8 caractères.';
$string['defaultquota'] = 'Quota de messages par défaut';
$string['defaultquota_desc'] = 'Nombre de messages qu\'un étudiant peut envoyer par période. Entrez 0 pour illimité.';
$string['delete_user_apikey'] = 'Supprimer ma clé';
$string['deleteagent'] = 'Supprimer l\'agent';
$string['deselect_custom_agent'] = 'Désélectionner l\'agent personnel';
$string['editagent'] = 'Modifier l\'agent';
$string['emptytext'] = 'Le texte ne peut pas être vide.';
$string['err_api_400'] = 'Requête invalide : {$a}';
$string['err_api_401'] = 'Clé API invalide ou expirée.';
$string['err_api_403'] = 'Accès refusé à l\'API Mistral.';
$string['err_api_404'] = 'Ressource non trouvée (404) : {$a}';
$string['err_api_429'] = 'Trop de requêtes — veuillez patienter quelques secondes.';
$string['err_api_5xx'] = 'Serveur Mistral temporairement indisponible.';
$string['err_api_curl'] = 'Impossible de contacter l\'API Mistral. Vérifiez la connexion réseau du serveur.';
$string['err_api_curl_download'] = 'Erreur cURL lors du téléchargement du fichier généré : {$a}';
$string['err_api_default'] = 'Erreur API ({$a->code}) : {$a->message}';
$string['err_api_empty_response'] = 'L\'API Mistral a retourné une réponse vide.';
$string['err_api_http_download'] = 'Téléchargement du fichier échoué (HTTP {$a}).';
$string['err_api_json'] = 'Réponse invalide de l\'API Mistral (JSON malformé).';
$string['err_api_network'] = 'Erreur de connexion réseau (cURL {$a}).';
$string['err_api_no_content'] = 'L\'API Mistral n\'a retourné aucun message texte ni image.'
    . ' Vérifiez les logs (mode DEBUG_DEVELOPER) pour le détail de la réponse.';
$string['err_api_no_image'] = 'L\'API de génération d\'images n\'a retourné aucune image.';
$string['err_api_no_response'] = 'Impossible de contacter l\'API Mistral (aucune réponse HTTP).';
$string['err_api_timeout'] = 'La requête a expiré. Le message était peut-être trop long.';
$string['err_docx_empty'] = 'Impossible d\'extraire le texte du fichier DOCX.';
$string['err_docx_xml'] = 'Impossible de lire word/document.xml dans le fichier DOCX.';
$string['err_docx_zip'] = 'Impossible d\'ouvrir le fichier DOCX (archive ZIP invalide).';
$string['err_file_corrupt'] = 'Contenu du fichier invalide (base64 corrompu).';
$string['err_file_empty'] = 'Fichier vide ou illisible.';
$string['err_file_magic'] = 'Le fichier ne correspond pas au type \'{\$a}\' (signature binaire incorrecte). Vérifiez que vous'
                              . ' n\'avez pas renommé un fichier d\'un '
                                  . 'autre format.';
$string['err_file_too_large'] = 'Le contenu du fichier dépasse la limite autorisée ({\$a} Ko de texte extrait). Essayez un'
                                  . ' fichier plus court, ou demandez à votre '
                                  . 'enseignant d\'augmenter le preset du cours.';
$string['err_file_type'] = 'Type de fichier non supporté : \'{$a}\'. Formats acceptés : TXT, JSON, DOCX, PDF, JPG, PNG, GIF, WEBP.';
$string['err_file_upload'] = 'Erreur lors de l\'envoi du fichier : {$a}';
$string['err_msg_required'] = 'Message ou fichier requis.';
$string['err_pdf_extract'] = 'Impossible d\'extraire le texte du PDF. Causes possibles : PDF scanné sans couche texte, PDF'
                               . ' chiffré, ou police personnalisée '
                                  . 'incompatible. Conseil : copiez-collez le texte directement dans la zone de message.';
$string['err_pdf_extract_ocr'] = 'Impossible d\'extraire le texte du PDF. Le fichier est peut-être scanné (image) ou protégé.'
                                   . ' Cochez « Utiliser l\'OCR Mistral » lors '
                                 . 'de l\'upload.';
$string['err_upload_cant_write'] = 'Impossible d\'écrire le fichier temporaire.';
$string['err_upload_form_size'] = 'Fichier trop volumineux (limite formulaire).';
$string['err_upload_ini_size'] = 'Fichier trop volumineux (limite php.ini).';
$string['err_upload_no_file'] = 'Aucun fichier reçu.';
$string['err_upload_no_tmp'] = 'Dossier temporaire manquant sur le serveur.';
$string['err_upload_partial'] = 'Fichier partiellement envoyé. Réessayez.';
$string['errorcommunication'] = 'Erreur de communication avec le serveur.';
$string['exportconversation'] = 'Exporter';
$string['extracting'] = 'Extraction…';
$string['extraction_failed_use_json'] = 'Extraction échouée — utilisez JSON ou collez le texte.';
$string['extraction_invalid_content'] = 'L\'extraction a produit un contenu invalide.';
$string['fetch_agents'] = 'Récupérer mes agents';
$string['fetch_agents_saved'] = 'Récupérer mes agents';
$string['file_context_analyse'] = 'L\'utilisateur souhaite que tu analyses ce fichier.';
$string['file_context_end'] = '--- FIN DU FICHIER ---';
$string['file_context_image_analyse'] = 'L\'utilisateur a joint une image. Analyse-la.';
$string['file_context_intro'] = 'Voici le contenu du fichier \'{$a}\' joint par l\'utilisateur :';
$string['file_context_question'] = 'Question/demande de l\'utilisateur : {$a}';
$string['file_context_start'] = '--- DÉBUT DU FICHIER ---';
$string['file_truncated'] = 'tronqué';
$string['file_truncated_marker'] = '[... contenu tronqué ici — la limite de ce niveau est {$a} K caractères ...]';
$string['filefailed'] = 'Extraction échouée';
$string['filenotready'] = 'Veuillez attendre que le fichier soit prêt ou cliquez sur « Coller le texte ».';
$string['fileready'] = 'Prêt';
$string['filetoobig'] = 'Le fichier est trop volumineux. Taille maximale : 5 Mo.';
$string['generated_image_alt'] = 'Image générée';
$string['indexed_success'] = 'Indexé avec succès ({$a} morceaux).';
$string['instanceconfig_header'] = 'Assistant IA — Paramètres de contexte';
$string['invalidresponse'] = 'Réponse invalide de l\'API Mistral.';
$string['invalidtext'] = 'Texte invalide — assurez-vous de coller du texte lisible.';
$string['json_format_hint'] = 'Format : <code>{"title":"…", "content":"…"}</code>';
$string['json_recommended'] = 'Le format <strong>JSON</strong> est le plus fiable. Pour les PDF, utilisez l\'onglet <strong>«'
                                . ' Texte direct »</strong> si '
                                       . 'l\'extraction échoue.';
$string['lastactivity'] = 'Dernière activité';
$string['manageagents'] = 'Gérer les agents';
$string['manageagents_link'] = 'Cliquez ici pour gérer les agents Mistral';
$string['manageresources'] = 'Gérer les ressources';
$string['max_embedding_chunks'] = 'Limite globale de chunks par document';
$string['max_embedding_chunks_desc'] = 'Nombre maximum de segments envoyés à l\'API Mistral pour vectorisation lors de'
                                         . ' l\'indexation d\'un document (entre 1 et 200). '
                                       . 'Valeur par défaut : 50 (~30 pages). Augmenter cette valeur augmente le coût API et le'
                                       . ' temps'
                                       . ' d\'indexation.';
$string['max_preset'] = 'Préréglage maximum pour les enseignants';
$string['max_preset_desc'] = 'Les enseignants peuvent choisir tout préréglage jusqu\'à ce niveau inclus. Abaisser cette valeur'
                               . ' plafonne silencieusement toutes les '
                             . 'configurations de cours existantes.';
$string['messages'] = 'Messages';
$string['mistralagent:addinstance'] = 'Ajouter un nouveau bloc Assistant IA Mistral';
$string['mistralagent:configureagent'] = 'Configurer l\'agent pour un cours';
$string['mistralagent:manageagents'] = 'Gérer les agents Mistral';
$string['mistralagent:managequotas'] = 'Gérer les quotas utilisateurs';
$string['mistralagent:myaddinstance'] = 'Ajouter un nouveau bloc Assistant IA Mistral au tableau de bord';
$string['mistralagent:use'] = 'Utiliser l\'Assistant IA Mistral';
$string['mistralagent:viewconversations'] = 'Voir les conversations des étudiants';
$string['model_embed'] = 'Modèle d\'embeddings';
$string['model_embed_desc'] = 'Modèle utilisé pour générer les vecteurs d\'embeddings lors de l\'indexation RAG des ressources'
                                . ' (endpoint /v1/embeddings). Valeur '
                              . 'recommandée actuelle : <code>mistral-embed</code>.';
$string['model_image'] = 'Modèle de génération d\'images';
$string['model_image_desc'] = 'Modèle utilisé pour générer des images à partir de descriptions textuelles (endpoint'
                                . ' /v1/images/generations). Valeur recommandée '
                              . 'actuelle : <code>pixtral-1-25-01</code>.';
$string['model_ocr'] = 'Modèle OCR';
$string['model_ocr_desc'] = 'Modèle utilisé pour extraire le texte des documents PDF scannés (endpoint /v1/ocr). Valeur'
                              . ' recommandée actuelle : '
                            . '<code>mistral-ocr-latest</code>.';
$string['model_vision'] = 'Modèle d\'analyse d\'images (vision)';
$string['model_vision_desc'] = 'Modèle utilisé pour analyser les images jointes par les étudiants dans le chat (endpoint'
                                 . ' /v1/chat/completions). Doit être un modèle '
                               . 'multimodal supportant la vision. Valeur recommandée actuelle : <code>pixtral-12b-2409</code>.';
$string['my_agents_desc'] = 'Entrez votre clé API Mistral personnelle pour récupérer la liste de vos agents et en choisir un pour'
                              . ' ce bloc.';
$string['my_agents_title'] = 'Mes agents Mistral personnels';
$string['myconversations'] = 'Mes conversations';
$string['name_and_content_required'] = 'Le nom et le contenu sont obligatoires.';
$string['name_optional'] = 'Nom (optionnel)';
$string['newconversation'] = 'Nouvelle conversation';
$string['nmessages'] = '{$a} messages';
$string['no_agents_found'] = 'Aucun agent trouvé pour cette clé. Créez un agent sur console.mistral.ai.';
$string['noagentavailable'] = 'L\'assistant IA n\'est pas disponible pour ce cours.';
$string['noagentconfigured'] = 'Aucun assistant IA n\'a été configuré pour ce cours.';
$string['noagents'] = 'Aucun agent n\'a encore été créé.';
$string['noapikey'] = 'La clé API Mistral n\'est pas configurée. Veuillez contacter l\'administrateur.';
$string['nochunks'] = 'Aucun morceau trouvé.';
$string['noconversations'] = 'Aucune conversation trouvée.';
$string['noconversationsyet'] = 'Aucune conversation. Commencez à discuter !';
$string['noresources'] = 'Aucune ressource.';
$string['ocr_failed'] = 'L\'extraction OCR a échoué. Le fichier est peut-être chiffré ou corrompu.';
$string['openchat'] = 'Ouvrir le chat';
$string['own_agentid_hint'] = 'Format : ag:xxxxxxxxxxxxxxxx — trouvez cet ID dans la console Mistral sur la page de votre agent.';
$string['own_agentid_label'] = 'ID de votre agent Mistral';
$string['own_agentname_hint'] = 'Nom affiché dans le bloc pour identifier cet agent. Si vide, l\'ID sera utilisé.';
$string['own_agentname_label'] = 'Nom affiché (optionnel)';
$string['own_agentname_placeholder'] = 'Ex : Mon assistant chimie';
$string['own_apikey_hint'] = 'Format : votre clé API Mistral — obtenez-la sur console.mistral.ai';
$string['own_apikey_info'] = 'Votre clé API est chiffrée avant stockage et n\'est utilisée que pour les appels à l\'API Mistral'
                               . ' depuis ce bloc.';
$string['own_apikey_label'] = 'Clé API Mistral personnelle';
$string['pastemodalintro'] = 'L\'extraction automatique a échoué. Ouvrez votre fichier, sélectionnez tout (Ctrl+A), copiez'
                               . ' (Ctrl+C) et collez ci-dessous :';
$string['pastemodaltitle'] = 'Coller le contenu du fichier';
$string['pasteplaceholder'] = 'Collez le texte ici…';
$string['pastetext'] = 'Coller le texte';
$string['pdf_extraction_warning'] = 'L\'extraction PDF peut échouer. Préférez JSON ou Texte direct.';
$string['period_daily'] = 'Quotidien';
$string['period_monthly'] = 'Mensuel';
$string['period_weekly'] = 'Hebdomadaire';
$string['pluginname'] = 'Assistant IA Mistral';
$string['preset_col_filecontent_chars'] = 'Taille max fichier';
$string['preset_col_history_chars'] = 'Taille historique';
$string['preset_col_history_messages'] = 'Messages historique';
$string['preset_col_name'] = 'Préréglage';
$string['preset_col_rag_chunks'] = 'Extraits RAG';
$string['preset_exceeds_max'] = 'Le préréglage sélectionné dépasse le maximum autorisé par votre administrateur ({$a}).';
$string['preset_full'] = 'Complet';
$string['preset_light'] = 'Léger';
$string['preset_standard'] = 'Standard';
$string['privacy:metadata:block_mistralagent_convs'] = 'Stocke les conversations entre les utilisateurs et l\'assistant IA.';
$string['privacy:metadata:block_mistralagent_convs:agentid'] = 'L\'identifiant de l\'agent Mistral utilisé pour cette'
                                                                 . ' conversation.';
$string['privacy:metadata:block_mistralagent_convs:timecreated'] = 'La date et l\'heure de création de la conversation.';
$string['privacy:metadata:block_mistralagent_convs:timemodified'] = 'La date et l\'heure de dernière modification de la'
                                                                      . ' conversation.';
$string['privacy:metadata:block_mistralagent_convs:title'] = 'Un titre optionnel résumant la conversation.';
$string['privacy:metadata:block_mistralagent_convs:userid'] = 'L\'identifiant de l\'utilisateur propriétaire de la conversation.';
$string['privacy:metadata:block_mistralagent_msgs'] = 'Stocke les messages individuels échangés au cours d\'une conversation.';
$string['privacy:metadata:block_mistralagent_msgs:content'] = 'Le texte complet du message (question ou réponse).';
$string['privacy:metadata:block_mistralagent_msgs:role'] = 'L\'auteur du message : « user » (étudiant) ou « assistant » (IA).';
$string['privacy:metadata:block_mistralagent_msgs:timecreated'] = 'La date et l\'heure d\'envoi du message.';
$string['privacy:metadata:block_mistralagent_quotas'] = 'Stocke les compteurs d\'utilisation par utilisateur et par cours.';
$string['privacy:metadata:block_mistralagent_quotas:messages_limit'] = 'Le nombre maximum de messages autorisés par période (null'
                                                                         . ' = illimité).';
$string['privacy:metadata:block_mistralagent_quotas:messages_used'] = 'Le nombre de messages envoyés par l\'utilisateur durant la'
                                                                        . ' période en cours.';
$string['privacy:metadata:block_mistralagent_quotas:period_start'] = 'La date de début de la période de quota en cours.';
$string['privacy:metadata:block_mistralagent_quotas:timemodified'] = 'La date et l\'heure de dernière mise à jour du compteur.';
$string['privacy:metadata:block_mistralagent_quotas:userid'] = 'L\'identifiant de l\'utilisateur concerné par ce quota.';
$string['privacy:metadata:externalsystem'] = 'Le contenu des messages est envoyé à l\'API Mistral AI (mistral.ai) pour'
                                               . ' traitement. Aucune donnée n\'est stockée '
                                             . 'durablement par ce plugin sur le service externe.';
$string['privacy:metadata:externalsystem:messages'] = 'L\'historique de la conversation et le message courant de l\'utilisateur,'
                                                        . ' y compris le contenu de tout fichier joint.';
$string['privacy:quota_path'] = 'Quota d\'utilisation';
$string['quality'] = 'Qualité';
$string['quotaexceeded'] = 'Vous avez atteint votre limite de messages pour cette période.';
$string['quotamanagement'] = 'Gestion des quotas';
$string['quotaperiod'] = 'Période du quota';
$string['quotaperiod_desc'] = 'Période après laquelle le quota est réinitialisé.';
$string['quotareset'] = 'Le quota a été réinitialisé.';
$string['quotastatus'] = 'Messages : {$a->used}/{$a->limit}';
$string['quotaunlimited'] = 'Illimité';
$string['quotaupdated'] = 'Quota mis à jour avec succès.';
$string['rag_context_footer'] = 'Utilise ces informations pour répondre à la question de l\'étudiant.'
    . ' Si les informations ne sont pas suffisantes, indique-le clairement.';
$string['rag_question_separator'] = '--- QUESTION DE L\'ÉTUDIANT ---';
$string['recommendation'] = 'Recommandation';
$string['reindex'] = 'Réindexer';
$string['removefile'] = 'Supprimer le fichier';
$string['resetquota'] = 'Réinitialiser le quota';
$string['resource_added'] = 'Ressource ajoutée.';
$string['resource_added_success'] = 'Ressource ajoutée avec succès.';
$string['resourceadded'] = 'Ressource ajoutée et indexée avec succès.';
$string['resourcecontent'] = 'Contenu';
$string['resourcedeleted'] = 'Ressource supprimée avec succès.';
$string['resourcename'] = 'Nom';
$string['resourcenotfound'] = 'Ressource introuvable ou n\'appartenant pas à ce cours.';
$string['resourcereindexed'] = 'Ressource réindexée avec succès.';
$string['resourcereindexfailed'] = 'La réindexation a échoué — consultez le statut de la ressource.';
$string['resourcetype'] = 'Type';
$string['saveandindex'] = 'Enregistrer et indexer';
$string['selectagent'] = 'Sélectionner un agent';
$string['selectagent_help'] = 'Choisissez quel agent IA sera disponible pour les étudiants dans ce cours.';
$string['send'] = 'Envoyer';
$string['sending'] = 'Envoi en cours...';
$string['sessexpired'] = 'Votre session a expiré — veuillez recharger la page.';
$string['setquota'] = 'Définir le quota';
$string['settings_heading_api'] = 'Configuration API';
$string['settings_heading_manageagents'] = 'Gestion des agents';
$string['settings_heading_manageagents_desc'] = 'Accès à la page de gestion des agents Mistral. Cette section permet de créer,'
                                                  . ' modifier ou supprimer les agents '
                                                . 'disponibles dans le plugin.';
$string['settings_heading_models'] = 'Modèles Mistral';
$string['settings_heading_models_desc'] = 'Identifiants des modèles utilisés par le plugin. Modifiez ces valeurs pour passer à'
                                            . ' une nouvelle version de modèle sans '
                                          . 'toucher au code. Consultez la <a'
                                          . ' href="https://docs.mistral.ai/getting-started/models/" target="_blank">documentation'
                                          . ' Mistral</a>'
                                          . ' '
                                          . 'pour la liste des modèles disponibles.';
$string['settings_heading_presets'] = 'Préréglages de contexte';
$string['settings_heading_presets_desc'] = 'Les préréglages contrôlent la quantité de contexte (historique, fichiers, extraits de'
                                             . ' documents) envoyée à l\'API Mistral '
                                           . 'par requête. Un préréglage plus élevé donne plus de contexte à l\'IA, mais augmente'
                                           . ' le coût et la latence. La valeur'
                                           . ' '
                                           . 'définie ici est le <strong>maximum</strong> que les enseignants peuvent sélectionner'
                                           . ' pour leur'
                                           . ' cours.';
$string['settings_heading_quota'] = 'Quotas de messages';
$string['settings_heading_rag'] = 'Indexation RAG (embeddings)';
$string['settings_heading_rag_desc'] = 'Contrôle le nombre maximum de segments de texte (chunks) indexés avec des vecteurs'
                                         . ' d\'embeddings par document. Au-delà de cette '
                                       . 'limite, la recherche par mots-clés est utilisée à la place.';
$string['show_hide_key'] = 'Afficher/masquer la clé';
$string['showdescription'] = 'Voir la description';
$string['startnewconversation'] = 'Démarrer une nouvelle conversation';
$string['status'] = 'Statut';
$string['status_error'] = 'Erreur';
$string['status_indexed'] = 'Indexé';
$string['status_pending'] = 'En attente';
$string['status_processing'] = 'Traitement';
$string['step1_apikey'] = 'Étape 1 — Entrez votre clé API Mistral';
$string['step2_selectagent'] = 'Étape 2 — Choisissez votre agent';
$string['student'] = 'Étudiant';
$string['tab_directtext'] = 'Texte direct';
$string['tab_file'] = 'Fichier';
$string['tab_url'] = 'URL';
$string['text_paste_placeholder'] = 'Collez le texte ici (Ctrl+A, Ctrl+C depuis votre PDF)';
$string['type'] = 'Type';
$string['typemessage'] = 'Écrivez votre message...';
$string['unexpected_server_response'] = 'Réponse inattendue du serveur.';
$string['unlimited'] = 'Illimité';
$string['upload_failed'] = 'Upload échoué.';
$string['uploadfailed'] = 'Échec de l\'envoi du fichier.';
$string['use_admin_agents'] = 'Utiliser les agents de l\'administrateur';
$string['use_admin_agents_desc'] = 'Sélectionnez l\'un des agents Mistral configurés par l\'administrateur du site.';
$string['use_different_key'] = 'Entrez une nouvelle clé API pour la remplacer :';
$string['use_ocr_hint'] = 'L\'OCR envoie automatiquement les PDF à l\'API Mistral OCR pour en extraire le texte. Si l\'OCR'
                            . ' échoue, le plugin tente l\'extraction native en '
                          . 'secours.';
$string['use_ocr_label'] = 'OCR Mistral automatique pour les PDF';
$string['use_own_apikey'] = 'Utiliser ma propre clé API Mistral';
$string['use_own_apikey_desc'] = 'Entrez votre clé API Mistral personnelle pour utiliser vos propres agents.';
$string['user_apikey_deleted'] = 'Votre clé API a été supprimée.';
$string['user_apikey_saved'] = 'Votre clé API a été enregistrée.';
$string['user_apikey_saved_badge'] = 'Clé enregistrée';
$string['user_apikey_stored'] = 'Clé API enregistrée de manière sécurisée :';
$string['userquota'] = 'Quota utilisateur';
$string['viewallconversations'] = 'Voir tout';
$string['viewcontent'] = 'Voir le contenu';
$string['viewconversation'] = 'Voir la conversation';
$string['viewhistory'] = 'Voir les conversations';
$string['vision_file_fallback'] = '[Image générée — file_id : {$a}]';
$string['vision_intro'] = 'L\'utilisateur a joint une image. Voici son contenu analysé :';
$string['vision_intro_sep_end'] = '--- FIN DESCRIPTION ---';
$string['vision_intro_sep_start'] = '--- DESCRIPTION DE L\'IMAGE ---';
$string['vision_prompt_describe'] = 'Décris précisément et exhaustivement le contenu de cette image'
    . ' (texte visible, objets, contexte, données chiffrées si présentes).';
$string['vision_prompt_with_question'] = 'L\'utilisateur pose cette question : "{$a}"'
    . ' — décris précisément l\'image et réponds à sa question.';
$string['vision_user_analyse'] = 'L\'utilisateur souhaite que tu analyses cette image.';
$string['vision_user_message'] = 'Message de l\'utilisateur : {$a}';
$string['youtube_added'] = 'Ressource YouTube ajoutée (extraction des sous-titres limitée).';
