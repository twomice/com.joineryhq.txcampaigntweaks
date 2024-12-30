<?php
// This file declares a managed database record of type "ReportTemplate".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
return [
  [
    'name' => 'CRM_Txcampaigntweaks_Form_Report_BotSpamContacts',
    'entity' => 'ReportTemplate',
    'params' => [
      'version' => 3,
      'label' => 'BotSpam Contact Review',
      'description' => 'BotSpam Contacts Review',
      'class_name' => 'CRM_Txcampaigntweaks_Form_Report_BotSpamContacts',
      'report_url' => 'com.joineryhq.txcampaigntweaks/botspamcontacts',
      'component' => '',
    ],
  ],
];
