<?php


/**
 * Override class for CRM_Report_OutputHandler_Csv
 */
class CRM_Txcampaigntweaks_Report_OutputHandler_Csv extends CRM_Report_OutputHandler_Csv {

  /**
   * Return the html body of the email. We'll get CiviCRM's original html body
   * from the parent class, and parse it to replace the report URL (which is for
   * the front-end interface) with a back-end URL
   *
   * @return string
   */
  public function getMailBody():string {
    $html = parent::getMailBody();
    $html = CRM_Txcampaigntweaks_Util::replaceReportUrlWithBackendUrl($html, $this->getForm()->getID());
    return $html;
  }

}
