<?php

/**
 * Utility methods for txcampaigtweaks extension.
 */
class CRM_Txcampaigntweaks_Util {

  public static function replaceReportUrlWithBackendUrl($html, $reportInstanceId) {
    $reportUrlTs = ts('Report URL');
    $matches = [];
    preg_match('/' . $reportUrlTs . ':\s+(http[^<\s]+)/', $html, $matches);
    $reportUrl = $matches[1];

    $backendReportUrl = CRM_Utils_System::url('civicrm/report/instance/' . $reportInstanceId, 'reset=1', TRUE, NULL, TRUE, FALSE, TRUE);

    $html = str_replace($reportUrl, $backendReportUrl, $html);
    return $html;
  }

}
