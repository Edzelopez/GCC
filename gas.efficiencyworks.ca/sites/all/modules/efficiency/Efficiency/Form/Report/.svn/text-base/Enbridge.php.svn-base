<?php
 
require_once 'Efficiency/Form/Report.php';
require_once 'CRM/Core/OptionGroup.php';

/**
 * Enbridge Report
 * based on Gas LDC Monthly Report
 * 
 */
class Efficiency_Form_Report_Enbridge extends Efficiency_Form_Report
{

    /**
     * Function to set variables up before form is built
     *
     * @return void
     * @access public
     */
    public function preProcess()
    {
        parent::preProcess();

        $this->_formFields = array(
                                   'file_identifier'    => array(
                                                                'title'      => 'FileID',
                                                                'tableAlias' => 'ga'
                                                                ),
                                   /* Fix for - Records that should be reported to the report are NOT reported IF the participant status is Closed Start */
                                   'gas_util_id'             => array(
                                                                'title'      => 'Status',
                                                                'tableAlias' => 'ga',
                                                                'hidden'     => '1', //hidden form-field
                                                                ),
                                   /* Fix for - Records that should be reported to the report are NOT reported IF the participant status is Closed End */
                                   'audit_invoiced'     => array(
                                                                'title'      => 'Audit Billed',
                                                                'tableAlias' => 'gmi'
                                                                ),
                                   'retrofit_invoiced'  => array(
                                                                'title' => 'Retrofit Invoiced',
                                                                'tableAlias' => 'gmi'
                                                                )
                                   ); 
        if ( !( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) ) {
            $this->_statusOptions = array( 
                                          'status_audit_assigned'             => array(
                                                                                       'title' => 'Audit Assigned',
                                                                                       'tableAlias' => 'auda'
                                                                                       ),
                                          'status_retrofit_pending'           =>  array(
                                                                                        'title' => 'Retrofit Pending',
                                                                                        'tableAlias' => 'retrop',
                                                                                        ),
                                          'status_no_potential'               =>  array(
                                                                                        'title' => 'Closed - No potential',
                                                                                        'tableAlias' => 'closenp',
                                                                                        ),
                                          'status_close_participant_withdraw' =>  array(
                                                                                        'title' => 'Closed - Participant Withdrew',
                                                                                        'tableAlias' => 'closepw',
                                                                                        ),
                                          'status_retrofit_completed'         =>  array(
                                                                                        'title' => 'Retrofit Completed',
                                                                                        'tableAlias' => 'retroc'
                                                                                        ),
                                          'status_project_completed'          =>  array(
                                                                                        'title' => 'Project Completed',
                                                                                        'tableAlias' => 'procom'
                                                                                        )
                                           );
            $this->assign('statusOptions', $this->_statusOptions);
        }
        $this->assign('formFields', $this->_formFields);
        
        $selectFields      = array(
                                   'file_identifier'     => array(
                                                                 'title'      => 'FileID',
                                                                 'tableAlias' => 'ga'
                                                                 ),
                                   'gas_acct'            => array( // Fix for account# in report
                                                                 'title'      => 'Account',
                                                                 'tableAlias' => 'ga'
                                                                 ),
                                   'audit_completed'     => array(
                                                                 'title' => 'Audit Date',
                                                                 'tableAlias' => 'gmo'
                                                                 ),
                                   'retrofit_completed'  => array(
                                                                 'title' => 'Retrofit completed',
                                                                 'tableAlias' => 'gmo'
                                                                 ),
                                   'retrofit_invoiced'   => array(
                                                                 'title'      => 'Retrofit Billed',
                                                                 'tableAlias' => 'gmi'
                                                                 ),
                                   'phone'               => array(
                                                                'title'       => 'Phone',
                                                                'tableAlias'  => 'cp'
                                                                ),
                                   'planguage'           => array(
                                                                'title'       => 'Language',
                                                                'tableAlias'  => 'ga'
                                                                ),
                                   'audit_invoiced'      => array(
                                                                'title'       => 'Audit Billed',
                                                                'tableAlias'  => 'gmi'
                                                                ),
                                   'job_costs'           => array(
                                                                  'title' => 'Job Costs',
                                                                  'tableAlias' => 'gmo'
                                                                  ),
                                   'job_m3saved'         => array(
                                                                  'title' => 'Job M3',
                                                                  'tableAlias' => 'gmo'
                                                                  ),
                                   'job_bcr'             => array(
                                                                  'title' => 'Job BCR',
                                                                  'tableAlias' => 'gmo'
                                                                  ),
                                   'ft2'                 => array(
                                                                  'title' => 'Ft2',
                                                                  'tableAlias' => 'gmo'
                                                                  ),
                                   );

        if ( ! ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) ) {
            $selectGasFields   = array (
                                       'h_s_cost'             => array(
                                                                       'title' => 'H&S work',
                                                                  'tableAlias' => 'gmo'
                                                                       ),
                                       'display_name_a'        => array(
                                                                        'title' => 'Landlord Name',
                                                                        'name'  => 'display_name',
                                                                        'tableAlias' => 'crc'
                                                                        ),
                                       'phone_a'               => array(
                                                                        'title' => 'Landlord Phone',
                                                                        'name' => 'phone',
                                                                        'tableAlias' => 'crp'
                                                                          ),
                                       'email_a'               => array(
                                                                        'title' => 'Landlord Email',
                                                                        'name' => 'email',
                                                                        'tableAlias' => 'cre'
                                                                        ),
                                       'shhd_flow'             => array(
                                                                        'title' => 'Flow test',
                                                                        'tableAlias' => 'gmo'
                                                                        ),
                                       'xm_installed'          => array(
                                                                        'title' => 'Retrofit Start Date',
                                                                        'tableAlias' => 'gr',
                                                                        'aggregate' => 'MIN',
                                                                        ),
                                       'house_age'            => array(
                                                                       'title'      => 'House Age',
                                                                       'tableAlias' => 'ga'
                                                                       ),
                                       'htg_sys'              => array(
                                                                       'title' => 'Heat_Sys',
                                                                       'tableAlias' => 'gmo'
                                                                       ),
                                       'retrofit_completed'   => array(
                                                                       'title' => 'Retrofit completed',
                                                                       'tableAlias' => 'gmo'
                                                                       ),
                                       'referred2hap'   => array(
                                                                       'title' => 'Referred to eLDC',
                                                                       'tableAlias' => 'ga'
                                                                       ),
                                   );

            $selectFields = array_merge($selectFields, $selectGasFields);

        } else {
            $selectEleFields   = array (
                                        'job_npv'             => array(
                                                                       'title' => 'Job TRC NPV',
                                                                       'tableAlias' => 'gmo'
                                                                       ),
                                        );
            $selectFields = array_merge($selectFields, $selectEleFields);
        }
        $this->_selectFields = array_merge($this->_selectFields, $selectFields);
        //$this->_selectFields = $selectFields;

        // Our participant's status
        $this->_statusField = array('title' => 'Participant Status');

        $this->_optionGroups = array(
                                     'dhw_fuel_id'       => array(
                                                                   'title'       => 'DHW fuel',
                                                                   'tableAlias'  => 'ga',
                                                                   'options'   => CRM_Core_OptionGroup::values('dhw_fuel')
                                                                   ),
                                     'house_type_id'     => array(
                                                                   'title' => 'House Type',
                                                                   'tableAlias' => 'ga',
                                                                   'options'   => CRM_Core_OptionGroup::values('house_type')
                                                                   ),
                                     );
        if ( ! ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) ) {

            $optionGroupsGasFields = array(
                                           'tenure'     => array(
                                                                 'title' => 'Tenure',
                                                                 'tableAlias' => 'ga',
                                                                 'options'   => CRM_Core_OptionGroup::values('tenure')
                                                                   ),
                                           'ldc_id'     => array(
                                                                 'title' => 'eLDC Name',
                                                                 'tableAlias' => 'ga',
                                                                 'options'   => CRM_Core_OptionGroup::values('ldc')
                                                                   ),
                                           );
            $this->_optionGroups = array_merge( $this->_optionGroups, $optionGroupsGasFields );
        }
        
        
        // for values of a table to be included as columns
        $this->_measuresValueColumns = array(
                                             'name' => array ('values' =>
                                                              array('%Attic insulation%'   => 'Attic m3',
                                                                    '%Wall insulation%'    => 'Wall m3',
                                                                    '%Basement insulation%'=> 'Bsmt m3',
                                                                    '%Draft proofing%'     => 'Draftproof m3',
                                                                    ),
                                                              'fields' =>
                                                              array('costs'   => 'Costs',
                                                                    'm3saved' => 'M3 Saved',
                                                                    ),
                                                              )
                                             );
        if ( !( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) ) {
            
            $this->_measuresValueColumns = array(
                                             'name' => array ('values' =>
                                                              array('%Attic insulation%'   => 'Attic m3',
                                                                    '%Wall insulation%'    => 'Wall m3',
                                                                    '%Basement insulation%'=> 'Bsmt m3',
                                                                    '%Draft proofing%'     => 'Draftproof m3',
                                                                    '%Showerhead%' => 'Showerheads',
                                                                    '%T-stat%' => 'Prog T-stat',
                                                                    '%Aerator%' => 'Aerators delivered',
                                                                    ),
                                                              'fields' =>
                                                              array('costs'   => 'Costs',
                                                                    'm3saved' => 'M3 Saved',
                                                                    'installed' => 'Installed'
                                                                    ),
                                                              )
                                             );
        }

        if ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) {
            $measuresValueColumnsEleFields = array( '%Other%' => 'Other m3' );
            $this->_measuresValueColumns = array_merge( $this->_measuresValueColumns['name']['values'] , $measuresValueColumnsEleFields );
        }
        
    }
    
    /**
     * name of export file.
     *
     * @param string $output type of output
     * @return string name of the file
     */
    public function getExportFileName( $output = 'csv') {
        return ts('Green Communities Weatherization Report');
    }

    /**
     * returns the header sequence to be used in report
     *
     * @return Array
     */
    public function getHeaderSequence() {
     
        if ( ! ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) ) {
            return array(
                         $this->_selectFields['file_identifier']['title'], 
                         $this->_selectFields['display_name']['title'], 
                         $this->_selectFields['street_address']['title'],
                         $this->_selectFields['city']['title'], 
                         $this->_selectFields['postal_code']['title'],    
                         $this->_selectFields['phone']['title'],
                         $this->_selectFields['planguage']['title'],
                         $this->_optionGroups['tenure']['title'],
                         $this->_selectFields['display_name_a']['title'],
                         $this->_selectFields['phone_a']['title'],
                         $this->_selectFields['email_a']['title'],  
                         $this->_selectFields['gas_acct']['title'],/*Fix for account# in report*/
                         $this->_selectFields['ft2']['title'],
                         $this->_optionGroups['house_type_id']['title'],
                         $this->_selectFields['house_age']['title'],
                         $this->_selectFields['audit_completed']['title'],//Added audit m3 estimate field in report
                         $this->_selectFields['audit_invoiced']['title'],
                         $this->_statusField['title'],
                         $this->_selectFields['htg_sys']['title'],
                         $this->_optionGroups['dhw_fuel_id']['title'],
                         $this->_selectFields['xm_installed']['title'],
                         $this->_selectFields['retrofit_completed']['title'],
                         $this->_selectFields['retrofit_invoiced']['title'],
                         'Attic cost', //$this->_measuresValueColumns['name']['fields']['costs'],
                         $this->_measuresValueColumns['name']['values']['%Attic insulation%'],
                         'Wall cost', //$this->_measuresValueColumns['name']['fields']['costs'],
                         $this->_measuresValueColumns['name']['values']['%Wall insulation%'],
                         'Bsmt cost', //$this->_measuresValueColumns['name']['fields']['costs'],
                         $this->_measuresValueColumns['name']['values']['%Basement insulation%'],
                         'Draftproof cost', //$this->_measuresValueColumns['name']['fields']['costs'],
                         $this->_measuresValueColumns['name']['values']['%Draft proofing%'],
                         $this->_selectFields['job_costs']['title'],
                         $this->_selectFields['job_m3saved']['title'],
                         $this->_selectFields['job_bcr']['title'],
                         $this->_selectFields['h_s_cost']['title'],
                         "Wx Status",
                         "TAPS qualified",
                         "TAPS action taken",
                         $this->_selectFields['shhd_flow']['title'],
                         $this->_measuresValueColumns['name']['values']['%Showerhead%'],
                         $this->_measuresValueColumns['name']['values']['%T-stat%'],
                         $this->_measuresValueColumns['name']['values']['%Aerator%'],
                         $this->_selectFields['referred2hap']['title'],
                         $this->_optionGroups['ldc_id']['title'],
                         "Other ee measure recommendations",
                         "Comments",
                         );

        } else {
            return array(
                         $this->_selectFields['file_identifier']['title'], 
                         $this->_selectFields['display_name']['title'], 
                         $this->_selectFields['street_address']['title'],
                         $this->_selectFields['city']['title'], 
                         $this->_selectFields['phone']['title'],
                         $this->_selectFields['planguage']['title'],
                         $this->_selectFields['gas_acct']['title'],/*Fix for account# in report*/
                         $this->_selectFields['ft2']['title'],
                         $this->_optionGroups['house_type_id']['title'],
                         $this->_selectFields['audit_completed']['title'],//Added audit m3 estimate field in report
                         $this->_selectFields['audit_invoiced']['title'],
                         $this->_statusField['title'],
                         $this->_optionGroups['central_air_id']['title'],
                         $this->_optionGroups['dhw_fuel_id']['title'],
                         $this->_selectFields['retrofit_completed']['title'],
                         $this->_selectFields['retrofit_invoiced']['title'],
                         'Attic cost', //$this->_measuresValueColumns['name']['fields']['costs'],
                         $this->_measuresValueColumns['name']['values']['%Attic insulation%'],
                         'Wall cost', //$this->_measuresValueColumns['name']['fields']['costs'],
                         $this->_measuresValueColumns['name']['values']['%Wall insulation%'],
                         'Bsmt cost', //$this->_measuresValueColumns['name']['fields']['costs'],
                         $this->_measuresValueColumns['name']['values']['%Basement insulation%'],
                         'Draftproof cost', //$this->_measuresValueColumns['name']['fields']['costs'],
                         $this->_measuresValueColumns['name']['values']['%Draft proofing%'],
                         'Other cost', //$this->_measuresValueColumns['name']['fields']['costs'],
                         $this->_measuresValueColumns['name']['values']['%Other%'],
                         $this->_selectFields['job_costs']['title'],
                         $this->_selectFields['job_m3saved']['title'],
                         $this->_selectFields['job_npv']['title'],
                         $this->_selectFields['job_bcr']['title'],
                         );
            
        }
    
    }

    /**
     * returns the result sequence to be used in report
     *
     * @return Array
     */
    public function getResultSequence() {

        if ( ! ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) ) {

            return array(
                         $this->_selectFields['file_identifier']['title'], 
                         $this->_selectFields['display_name']['title'], 
                         $this->_selectFields['street_address']['title'],
                         $this->_selectFields['city']['title'],
                         $this->_selectFields['postal_code']['title'],
                         $this->_selectFields['phone']['title'],
                         $this->_selectFields['planguage']['title'],
                         $this->_optionGroups['tenure']['title'], 
                         $this->_selectFields['display_name_a']['title'], 
                         $this->_selectFields['phone_a']['title'],
                         $this->_selectFields['email_a']['title'],
                         $this->_selectFields['gas_acct']['title'],/*Fix for account# in report*/
                         $this->_selectFields['ft2']['title'],
                         $this->_optionGroups['house_type_id']['title'],
                         $this->_selectFields['house_age']['title'],
                         $this->_selectFields['audit_completed']['title'],//Added audit m3 estimate field in report
                         $this->_selectFields['audit_invoiced']['title'],
                         $this->_statusField['title'],
                         $this->_selectFields['htg_sys']['title'],
                         $this->_optionGroups['dhw_fuel_id']['title'],
                         $this->_selectFields['xm_installed']['title'],
                         $this->_selectFields['retrofit_completed']['title'],
                         $this->_selectFields['retrofit_invoiced']['title'],
                         $this->_measuresValueColumns['name']['fields']['costs'] . '-1',
                         $this->_measuresValueColumns['name']['fields']['m3saved'] . '-1',
                         //$this->_measuresValueColumns['name']['values']['%Attic insulation%'],
                         $this->_measuresValueColumns['name']['fields']['costs'] . '-2',
                         $this->_measuresValueColumns['name']['fields']['m3saved'] . '-2',
                         //$this->_measuresValueColumns['name']['values']['%Wall insulation%'],
                         $this->_measuresValueColumns['name']['fields']['costs'] . '-3',
                         $this->_measuresValueColumns['name']['fields']['m3saved'] . '-3',
                         //$this->_measuresValueColumns['name']['values']['%Basement insulation%'],
                         $this->_measuresValueColumns['name']['fields']['costs'] . '-4',
                         $this->_measuresValueColumns['name']['fields']['m3saved'] . '-4',
                         //$this->_measuresValueColumns['name']['values']['%Draft proofing%'],
                         $this->_selectFields['job_costs']['title'],
                         $this->_selectFields['job_m3saved']['title'],
                         $this->_selectFields['job_bcr']['title'],
                         $this->_selectFields['h_s_cost']['title'],
                         "Wx Status",
                         "TAPS qualified",
                         "TAPS action taken",
                         $this->_selectFields['shhd_flow']['title'],
                         $this->_measuresValueColumns['name']['fields']['installed'] . '-5',
                         $this->_measuresValueColumns['name']['fields']['installed'] . '-6',
                         $this->_measuresValueColumns['name']['fields']['installed'] . '-7',
                         $this->_selectFields['referred2hap']['title'],
                         $this->_optionGroups['ldc_id']['title'],
                         "Other ee measure recommendations",
                         "Comments",
                         );
         

        } else {
            
             return array(
                         $this->_selectFields['file_identifier']['title'], 
                         $this->_selectFields['display_name']['title'], 
                         $this->_selectFields['street_address']['title'],
                         $this->_selectFields['city']['title'],
                         $this->_selectFields['phone']['title'],
                         $this->_selectFields['planguage']['title'],
                         $this->_selectFields['gas_acct']['title'],/*Fix for account# in report*/
                         $this->_selectFields['ft2']['title'],
                         $this->_optionGroups['house_type_id']['title'],
                         $this->_selectFields['audit_completed']['title'],//Added audit m3 estimate field in report
                         $this->_selectFields['audit_invoiced']['title'],
                         $this->_statusField['title'],
                         $this->_optionGroups['central_air_id']['title'],
                         $this->_optionGroups['dhw_fuel_id']['title'],
                         $this->_selectFields['retrofit_completed']['title'],
                         $this->_selectFields['retrofit_invoiced']['title'],
                         $this->_measuresValueColumns['name']['fields']['costs'] . '-1',
                         $this->_measuresValueColumns['name']['fields']['m3saved'] . '-1',
                         //$this->_measuresValueColumns['name']['values']['%Attic insulation%'],
                         $this->_measuresValueColumns['name']['fields']['costs'] . '-2',
                         $this->_measuresValueColumns['name']['fields']['m3saved'] . '-2',
                         //$this->_measuresValueColumns['name']['values']['%Wall insulation%'],
                         $this->_measuresValueColumns['name']['fields']['costs'] . '-3',
                         $this->_measuresValueColumns['name']['fields']['m3saved'] . '-3',
                         //$this->_measuresValueColumns['name']['values']['%Basement insulation%'],
                         $this->_measuresValueColumns['name']['fields']['costs'] . '-4',
                         $this->_measuresValueColumns['name']['fields']['m3saved'] . '-4',
                         //$this->_measuresValueColumns['name']['values']['%Draft proofing%'],
                         $this->_measuresValueColumns['name']['fields']['costs'] . '-5',
                         $this->_measuresValueColumns['name']['fields']['m3saved'] . '-5',
                         //$this->_measuresValueColumns['name']['values']['%Other%'],
                         $this->_selectFields['job_costs']['title'],
                         $this->_selectFields['job_m3saved']['title'],
                         $this->_selectFields['job_npv']['title'],
                         $this->_selectFields['job_bcr']['title'],
                         );
        }
    }

    /**
     * returns the title header to be used in report
     *
     * @return Array
     */
    public function getTitleHeader( $params ) {
        if ($params['ldc_id']) {
            $options = CRM_Core_OptionGroup::values('ldc');
            $ldc = $options[$params['ldc_id']];
        }
        $date = date('M-d');
        $titleHeader = ", \n, Green Communities Weatherization Report \n, $date \n, \n";
        return $titleHeader;
    }


    public function getAppDetails( $params )
    {     
        $result = parent::getAppDetails($params);
                
        if ( ! ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) ) {
            foreach( $result as $key => $appDetail ){
                if($appDetail['Tenure']){
                    if( $appDetail['Tenure'] == "Owner-occupied" )
                        $result[$key]['Tenure'] = "Owner";
                    elseif( $appDetail['Tenure'] == "Rental" )
                        $result[$key]['Tenure'] = "Tenant";
                }
                if ( isset($appDetail['Participant Status']) ) {
                    if( $appDetail['Participant Status'] == "Audit Assigned" )
                        $result[$key]['Participant Status'] = "Initial Audit Scheduled";
                    elseif( $appDetail['Participant Status'] == "Closed - No potential" )
                        $result[$key]['Participant Status'] = "Initial Audit Completed";
                    elseif( $appDetail['Participant Status'] == "Closed - Participant withdrew" )
                        $result[$key]['Participant Status'] = "Withdrawn";
                    elseif( $appDetail['Participant Status'] == "Retrofit Pending" )
                        $result[$key]['Participant Status'] = "Initial Audit Completed";
                    elseif( $appDetail['Participant Status'] == "Retrofit Completed" )
                        $result[$key]['Participant Status'] = "Retrofit Complete";
                    elseif( $appDetail['Participant Status'] == "Project Completed" )
                        $result[$key]['Participant Status'] = "Project Complete";
                }
                if ( isset($appDetail['Referred to eLDC']) ) {
                    if ( $appDetail['Referred to eLDC'] == '1' ) {
                        $result[$key]['Referred to eLDC'] = "Yes";
                    } elseif ( $appDetail['Referred to eLDC'] == '0' ) {
                        $result[$key]['Referred to eLDC'] = "No";
                    } 
                }
                if(isset($appDetail['Wx Status'])){
                    if( $appDetail['Participant Status'] == "Audit Assigned" )
                        $result[$key]['Wx Status'] = "";
                    elseif( $appDetail['Participant Status'] == "Closed - No potential" )
                        $result[$key]['Wx Status'] = "CET failed";
                    elseif( $appDetail['Participant Status'] == "Retrofit Pending" )
                        $result[$key]['Wx Status'] = "In progress";
                    elseif( $appDetail['Participant Status'] == "Retrofit Completed" )
                        $result[$key]['Wx Status'] = "Project complete";
                    elseif( $appDetail['Participant Status'] == "Project Completed" )
                        $result[$key]['Wx Status'] = "Project closed";
                    elseif( $appDetail['Participant Status'] == "Closed - Participant withdrew" )
                        $result[$key]['Wx Status'] = "Project closed";
                }
                if ( isset($appDetail['Flow test']) && $appDetail['Flow test'] != "" ) {
                    if ( $appDetail['Flow test'] == '0' ) {
                        $result[$key]['Flow test'] = "Yes";
                    } else {
                        $result[$key]['Flow test'] = "No";
                    }
                }
            }
        }
        return $result;
    }

    public function buildFromClause( ) 
    {
        $from = parent::buildFromClause();
        if ( ! ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) ) {
            $RelID = CRM_Core_DAO::getFieldValue( 'CRM_Contact_DAO_RelationshipType',
                                                  'Landlord is', 'id', 'name_b_a' );
            $additionalFrom = "LEFT JOIN civicrm_relationship cr ON (cc.id = cr.contact_id_b) AND cr.relationship_type_id = {$RelID} 
LEFT JOIN civicrm_contact crc ON ( cr.contact_id_a = crc.id)
LEFT JOIN civicrm_phone   crp ON (crp.contact_id  = cr.contact_id_a AND crp.is_primary = 1)
LEFT JOIN civicrm_email   cre ON (cre.contact_id  = cr.contact_id_a AND cre.is_primary = 1)";
            $from = str_replace("LEFT JOIN civicrm_relationship cr ON (cc.id = cr.contact_id_b)", $additionalFrom, $from);
            $from .= " LEFT JOIN gcc_retrofit gr ON (gr.entity_id = cc.id)";
        }
        return $from;
    }
}

?>
