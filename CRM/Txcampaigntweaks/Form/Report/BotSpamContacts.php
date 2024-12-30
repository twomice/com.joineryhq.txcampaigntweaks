<?php

use CRM_Txcampaigntweaks_ExtensionUtil as E;

class CRM_Txcampaigntweaks_Form_Report_BotSpamContacts extends CRM_Report_Form {

  protected $_addressField = FALSE;
  protected $_summary = NULL;
  protected $_customGroupExtends = array('Individual');
  protected $_customGroupGroupBy = FALSE;

  public function __construct() {
    $this->_columns = [
      'civicrm_contact' => [
        'dao' => 'CRM_Contact_DAO_Contact',
        'fields' => [
          'sort_name' => [
            'title' => ts('Contact Name'),
            'required' => TRUE,
          ],
          'first_name' => [
            'title' => ts('First Name'),
          ],
          'middle_name' => [
            'title' => ts('Middle Name'),
          ],
          'last_name' => [
            'title' => ts('Last Name'),
          ],
          'id' => [
            'no_display' => TRUE,
            'required' => TRUE,
          ],
          'gender_id' => [
            'title' => ts('Gender'),
          ],
          'contact_type' => [
            'title' => ts('Contact Type'),
          ],
          'contact_sub_type' => [
            'title' => ts('Contact Subtype'),
          ],
          'created_date' => [
            'default' => TRUE,
          ],
          'screening_rule' => [
            'default' => TRUE,
            'title' => E::ts('Screening Rule*'),
            'dbAlias' => '"F#1676-1"',
          ],
        ],
        'filters' => $this->getBasicContactFilters(),
        'grouping' => 'contact-fields',
        'order_bys' => [
          'created_date' => [
            'default' => TRUE,
            'default_weight' => '0',
            'default_order' => 'DESC',
          ],
          'sort_name' => [
            'title' => ts('Last Name, First Name'),
            'default' => '1',
            'default_weight' => '1',
            'default_order' => 'ASC',
          ],
          'first_name' => [
            'title' => ts('First Name'),
          ],
          'gender_id' => [
            'name' => 'gender_id',
            'title' => ts('Gender'),
          ],
          'birth_date' => [
            'name' => 'birth_date',
            'title' => ts('Birth Date'),
          ],
          'contact_type' => [
            'title' => ts('Contact Type'),
          ],
          'contact_sub_type' => [
            'title' => ts('Contact Subtype'),
          ],
        ],
      ],
      'civicrm_email' => [
        'fields' => [
          'email' => [
            'title' => ts('Primary email'),
            'default' => TRUE,
          ],
        ],
      ],
    ];
    $this->_groupFilter = TRUE;
    $this->_tagFilter = TRUE;
    parent::__construct();
  }

  public function beginPostProcessCommon() {
    $candidateSql = "
    select
      c.id,
      c.display_name
    from
      (
        select
          l.*
        from
          civicrm_log l
          inner join (
            SELECT
              min(id) as first_id,
              entity_id
            FROM
              `civicrm_log`
            where
              entity_table = 'civicrm_contact'
            group by
              entity_id
          ) t on t.first_id = l.id
        where
          l.modified_id = l.entity_id
      ) self
      inner join civicrm_contact c on c.id = self.entity_id
      left join civicrm_address a on a.contact_id = c.id
      left join civicrm_phone ph on ph.contact_id = c.id
      left join civicrm_contribution ctr on ctr.contact_id = c.id
      left join civicrm_participant p on p.contact_id = c.id
      left join civicrm_entity_tag t on t.entity_table = 'civicrm_contact'
      and t.entity_id = c.id
      left join (
        select
          contact_id_a as cid
        from
          civicrm_relationship
        union
        select
          contact_id_b as cid
        from
          civicrm_relationship
      ) rel on rel.cid = c.id
      inner join (
        select
          contact_id as cid,
          count(*) cnt
        from
          civicrm_email
        group by
          contact_id
      ) e on c.id = e.cid
    where
      c.gender_id is null
      and ifnull(c.source, '') = ''
      and c.first_name not like '% %'
      and c.last_name not like '% %'
      and c.contact_type = 'individual'
      and CAST(
        lower(c.first_name) AS BINARY
      ) = CAST(c.first_name AS BINARY)
      and CAST(
        lower(c.last_name) AS BINARY
      ) = CAST(c.last_name AS BINARY)
      and a.id is null
      and ph.id is null
      and ctr.id is null
      and p.id is null
      and t.id is null
      and rel.cid is null
      and e.cnt = 1

    ";

    $this->createTemporaryTable('civireport_botspamcandidates_temp1', $candidateSql);

    parent::beginPostProcessCommon();

    $this->setBotScreeningRulesTplVar();
  }

  private function setBotScreeningRulesTplVar() {
    $botScreeningRules = [
      'F#1676-1' => '
        - no address, phone number, gender
        - exactly one email address
        - no relationships
        - no contributions
        - no values in any custom fields
        - no event records
        - no tags
        - no contact source
        - all-lowercase first name and last name
        - no spaces in first name or last name
        - contact type = "Individual"
      ',
    ];
    $this->assign('botScreeningRules', $botScreeningRules);
  }

  public function from() {
    $this->_from = NULL;

    $this->_from = "
      FROM
        civicrm_contact {$this->_aliases['civicrm_contact']} {$this->_aclFrom}
        INNER JOIN {$this->temporaryTables['civireport_botspamcandidates_temp1']['name']} t on t.id = {$this->_aliases['civicrm_contact']}.id
    ";
    if ($this->isTableSelected('civicrm_email')) {
      $this->_from .= "
        LEFT JOIN civicrm_email {$this->_aliases['civicrm_email']} on {$this->_aliases['civicrm_email']}.contact_id = {$this->_aliases['civicrm_contact']}.id AND {$this->_aliases['civicrm_email']}.is_primary
      ";
    }
  }

  public function alterDisplay(&$rows) {
    // custom code to alter rows
    $entryFound = FALSE;
    $checkList = array();
    foreach ($rows as $rowNum => $row) {

      if (!empty($this->_noRepeats) && $this->_outputMode != 'csv') {
        // not repeat contact display names if it matches with the one
        // in previous row
        $repeatFound = FALSE;
        foreach ($row as $colName => $colVal) {
          if (($checkList[$colName] ?? NULL) &&
            is_array($checkList[$colName]) &&
            in_array($colVal, $checkList[$colName])
          ) {
            $rows[$rowNum][$colName] = "";
            $repeatFound = TRUE;
          }
          if (in_array($colName, $this->_noRepeats)) {
            $checkList[$colName][] = $colVal;
          }
        }
      }

      if (array_key_exists('civicrm_address_state_province_id', $row)) {
        if ($value = $row['civicrm_address_state_province_id']) {
          $rows[$rowNum]['civicrm_address_state_province_id'] = CRM_Core_PseudoConstant::stateProvince($value, FALSE);
        }
        $entryFound = TRUE;
      }

      if (array_key_exists('civicrm_address_country_id', $row)) {
        if ($value = $row['civicrm_address_country_id']) {
          $rows[$rowNum]['civicrm_address_country_id'] = CRM_Core_PseudoConstant::country($value, FALSE);
        }
        $entryFound = TRUE;
      }

      if (array_key_exists('civicrm_contact_sort_name', $row) &&
        $rows[$rowNum]['civicrm_contact_sort_name'] &&
        array_key_exists('civicrm_contact_id', $row)
      ) {
        $url = CRM_Utils_System::url("civicrm/contact/view",
          'reset=1&cid=' . $row['civicrm_contact_id'],
          $this->_absoluteUrl
        );
        $rows[$rowNum]['civicrm_contact_sort_name_link'] = $url;
        $rows[$rowNum]['civicrm_contact_sort_name_hover'] = E::ts("View Contact Summary for this Contact.");
        $entryFound = TRUE;
      }

      if (!$entryFound) {
        break;
      }
    }
  }

}
