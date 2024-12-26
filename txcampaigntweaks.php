<?php

require_once 'txcampaigntweaks.civix.php';
// phpcs:disable
use CRM_Txcampaigntweaks_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_alterReportVar().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterReportVar
 */
function txcampaigntweaks_civicrm_alterReportVar($varType, &$var, $reportForm) {
  switch ($varType) {
    case 'outputhandlers':
      // Override the built-in PDF and CSV handers with our own, because we want to
      // alter the report URL in the getMailBody() html content.
      $var['\CRM_Report_OutputHandler_Pdf'] = 'CRM_Txcampaigntweaks_Report_OutputHandler_Pdf';
      $var['\CRM_Report_OutputHandler_Csv'] = 'CRM_Txcampaigntweaks_Report_OutputHandler_Csv';
      break;
  }
}

function txcampaigntweaks_civicrm_buildForm($formName, &$form) {
  // enable tracking feature
  if ((
    $formName == 'CRM_Contribute_Form_Contribution_Main'
  )) {
    CRM_Core_Resources::singleton()->addScriptFile(E::LONG_NAME, 'js/CRM_Contribute_Form_Contribution_Main.js');
  }

  if ((
    $formName == 'CRM_Contribute_Form_Contribution_Main'
    || $formName == 'CRM_Contribute_Form_Contribution_Confirm'
    || $formName == 'CRM_Contribute_Form_Contribution_ThankYou'
  )) {

    $contributionPageId = $form->_id;

    $ufJoinGet = civicrm_api3('UFJoin', 'get', [
      'sequential' => 1,
      'return' => ["uf_group_id"],
      'entity_table' => "civicrm_contribution_page",
      'entity_id' => $contributionPageId,
      'module' => "civicontribute",
    ]);
    $profileIds = CRM_Utils_Array::collect('uf_group_id', ($ufJoinGet['values'] ?? array()));
    if (!empty($profileIds)) {
      $ufFieldGet = civicrm_api3('UFField', 'get', [
        'sequential' => 1,
        'uf_group_id' => ['IN' => $profileIds],
        'field_type' => "contact",
        'field_name' => "group",
      ]);
      $groupFieldLabels = CRM_Utils_Array::collect('label', ($ufFieldGet['values'] ?? array()));
    }
    // use the custom field ID and custom field label here
    $trackingFields = $groupFieldLabels;
    $form->assign('trackingFields', $trackingFields);
  }
}

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function txcampaigntweaks_civicrm_config(&$config) {
  _txcampaigntweaks_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function txcampaigntweaks_civicrm_install() {
  _txcampaigntweaks_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function txcampaigntweaks_civicrm_enable() {
  _txcampaigntweaks_civix_civicrm_enable();
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function txcampaigntweaks_civicrm_preProcess($formName, &$form) {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
//function txcampaigntweaks_civicrm_navigationMenu(&$menu) {
//  _txcampaigntweaks_civix_insert_navigation_menu($menu, 'Mailings', array(
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ));
//  _txcampaigntweaks_civix_navigationMenu($menu);
//}
