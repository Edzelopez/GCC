<?php


require_once 'Efficiency/Form/Report.php';

/**
 * This class generates form components for relationship
 * 
 */
class Efficiency_Form_Report_Retrofit extends Efficiency_Form_Report
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
        if ( ! ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) ) {
            $this->_formFields = array(
                                       'file_identifier'       => array(
                                                                        'title' => 'FileID',
                                                                        'tableAlias' => 'ga'
                                                                        ),
                                       'retrofit_completed'    => array(
                                                                        'title' => 'Retrofit completed',
                                                                    'tableAlias' => 'gmo'
                                                                        ),
                                       'retrofit_invoiced'     => array(
                                                                        'title' => 'Retrofit Invoiced',
                                                                        'tableAlias' => 'gmi'
                                                                    ),
                                       ); 
        } else {
            $this->_formFields = array(
                                       'file_identifier'       => array(
                                                                        'title' => 'OPA FileID',
                                                                        'tableAlias' => 'ga'
                                                                        ),
                                       'retrofit_completed'    => array(
                                                                        'title' => 'Retrofit completed',
                                                                    'tableAlias' => 'gmo'
                                                                        ),
                                       'retrofit_invoiced'     => array(
                                                                        'title' => 'Retrofit Invoiced',
                                                                        'tableAlias' => 'gmi'
                                                                    ),
                                       );
        }
        $this->assign('formFields', $this->_formFields);
        $this->_selectFields = array_merge($this->_formFields, $this->_selectFields );
        if ( ! ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) ) {
            $select            = array(
                                       'xm_costs'              => array(
                                                                        'title' => 'Extended Costs',
                                                                        'tableAlias' => 'gmo'
                                                                        ),
                                       'job_m3saved'           => array(	
                                                                        'title' => 'Gas Savings',
                                                                        'tableAlias' => 'gmo'
                                                                            ),
                                       'xm_kwh'                => array(
                                                                    'title' => 'Extended Savings kWh/y',
                                                                    'tableAlias' => 'gmo'
                                                                        ),
                                       'xm_trc'                  => array(
                                                                        'title' => 'Extended TRC NPV',
                                                                        'tableAlias' => 'gmo'
                                                                        ),
                                       'shhd_flow'             => array(
                                                                        'title' => 'Flow Test',
                                                                        'tableAlias' => 'gmo'
                                                                        ),
                                       ); 
            
        } else {
            
            $select            = array(
                                       'xm_costs'              => array(
                                                                        'title' => 'Job Costs',
                                                                        'tableAlias' => 'gmo'
                                                                        ),
                                       'xm_kwh'                => array(
                                                                        'title' => 'Job Savings kWh/y',
                                                                        'tableAlias' => 'gmo'
                                                                        ),
                                       'xm_trc'                => array(
                                                                        'title' => 'Job NPV',
                                                                        'tableAlias' => 'gmo'
                                                                    ),
                                       );
            
        }
        $this->_selectFields = array_merge($this->_selectFields, $select);
        
        require_once 'CRM/Core/OptionGroup.php';

        // For option Groups to be included as columns
        $this->_optionGroups = array(
                                     'ldc_id'    => array(
                                                          'title'      => 'LDC',
                                                          'tableAlias' => 'ga',
                                                          'options'    => CRM_Core_OptionGroup::values('ldc'),
                                                          ),
                                     );

        // For option Values to be included as columns

//         $this->_optionValues = 
//             array(
//                   'central_air_id' => array(
//                                             'table'     => 'gcc_applicant',
//                                             'options'   => array(
//                                                                  'In attic'=>array(
//                                                                                    'title' => 'Attic INS',
//                                                                                    'value' => CRM_Core_OptionGroup::getValue('central_air', 'In attic') ),
//                                                                  'In basement'=>array(
//                                                                                       'title' => 'Bsm INS',
//                                                                                       'value' => CRM_Core_OptionGroup::getValue('central_air', 'In basement') ),
//                                                                  ),
//                                             ),
//                   );

        // for values of a table to be included as columns
        $this->_measuresValueColumns = array(
                                             'name' => array ('values' =>
                                                              array(
                                                                    '%Showerhead%'   => 'Shhds Installed',
                                                                    '%Aerator%'   => 'Aerators Installed',
                                                                    '%T-stat%'   => 'P T-stat Installed',
                                                                    '%Attic insulation%'   => 'Attic INS',
                                                                    '%Wall insulation%'    => 'Wall INS',
                                                                    '%Basement insulation%'=> 'Bsmt INS',
                                                                    '%Floor insulation%'   => 'Flr INS',
                                                                    '%Draft proofing%'     => 'Draft proof',
                                                                    '%Refrigerator replacement-15 cu ft%' => 'Fridge 15',
                                                                    '%Refrigerator replacement-18 cu ft%' => 'Fridge 18',
                                                                    '%Refrigerator replacement-21 cu ft%' => 'Fridge 21',
                                                                    '%Refrigerator removal only%' => 'Fridge Rmv',
                                                                    '%DHW fuel switch%' => 'Fuel switch DHW',
                                                                    '%DW Heat recovery%'=> 'DW Heat recovery',//guess work
                                                                    ),
                                                              )
                                             );

    }

    /**
     * name of export file.
     *
     * @param string $output type of output
     * @return string name of the file
     */
    public function getExportFileName( $output = 'csv') {
        return ts('GC Retrofit Report');
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
                         $this->_selectFields['retrofit_completed']['title'],
                         $this->_selectFields['xm_costs']['title'], 
                         $this->_selectFields['job_m3saved']['title'],
                         $this->_selectFields['xm_kwh']['title'], 
                         $this->_selectFields['xm_trc']['title'], 
                         $this->_selectFields['shhd_flow']['title'], 
                         $this->_measuresValueColumns['name']['values']['%Showerhead%'],
                         $this->_measuresValueColumns['name']['values']['%Aerator%'],
                         $this->_measuresValueColumns['name']['values']['%T-stat%'],
                         $this->_measuresValueColumns['name']['values']['%Attic insulation%'],
                         $this->_measuresValueColumns['name']['values']['%Wall insulation%'],
                         $this->_measuresValueColumns['name']['values']['%Basement insulation%'],
                         $this->_measuresValueColumns['name']['values']['%Floor insulation%'],
                         $this->_measuresValueColumns['name']['values']['%Draft proofing%'],
                         $this->_measuresValueColumns['name']['values']['%Refrigerator replacement-15 cu ft%'],
                         $this->_measuresValueColumns['name']['values']['%Refrigerator replacement-18 cu ft%'],
                         $this->_measuresValueColumns['name']['values']['%Refrigerator replacement-21 cu ft%'],
                         $this->_measuresValueColumns['name']['values']['%Refrigerator removal only%'],
                         $this->_measuresValueColumns['name']['values']['%DHW fuel switch%'],
                         $this->_measuresValueColumns['name']['values']['%DW Heat recovery%'],
                         $this->_optionGroups['ldc_id']['title'],
                         $this->_selectFields['retrofit_invoiced']['title'],
                         );
        } else {
            return array(
                         $this->_selectFields['file_identifier']['title'], 
                         $this->_selectFields['first_name']['title'], 
                         $this->_selectFields['last_name']['title'], 
                         $this->_selectFields['street_address']['title'],
                         $this->_selectFields['city']['title'], 
                         $this->_selectFields['retrofit_completed']['title'],
                         $this->_selectFields['xm_costs']['title'], 
                         $this->_selectFields['xm_kwh']['title'], 
                         $this->_selectFields['xm_trc']['title'], 
                         $this->_measuresValueColumns['name']['values']['%Attic insulation%'],
                         $this->_measuresValueColumns['name']['values']['%Wall insulation%'],
                         $this->_measuresValueColumns['name']['values']['%Basement insulation%'],
                         $this->_measuresValueColumns['name']['values']['%Floor insulation%'],
                         $this->_measuresValueColumns['name']['values']['%Draft proofing%'],
                         $this->_measuresValueColumns['name']['values']['%Refrigerator replacement-15 cu ft%'],
                         $this->_measuresValueColumns['name']['values']['%Refrigerator replacement-18 cu ft%'],
                         $this->_measuresValueColumns['name']['values']['%Refrigerator replacement-21 cu ft%'],
                         $this->_measuresValueColumns['name']['values']['%Refrigerator removal only%'],
                         $this->_measuresValueColumns['name']['values']['%DHW fuel switch%'],
                         $this->_measuresValueColumns['name']['values']['%DW Heat recovery%'],
                         $this->_optionGroups['ldc_id']['title'],
                         $this->_selectFields['retrofit_invoiced']['title'],
                         );
        }
        
    }

    /**
     * returns the result sequence to be used in report
     *
     * @return Array
     */
    public function getResultSequence() {
        return $this->getHeaderSequence();
    }

    /*** Function modified for gas site ***/
    public function getAppDetails( $params ) {
        
        $result = parent::getAppDetails($params);
          
        if ( ! ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) ) {
            foreach ( $result as $key => $appDetail ) {
                if ( isset($appDetail['Flow Test']) && $appDetail['Flow Test'] != "" ) {
                    if ( $appDetail['Flow Test'] == '0' ) {
                        $result[$key]['Flow Test'] = "Yes";
                    } else {
                        $result[$key]['Flow Test'] = "No";
                    }
                } 
                if ( isset($appDetail['Aerators Installed']) && $appDetail['Aerators Installed'] != "" ) {
                    if ( $appDetail['Aerators Installed'] == '1' ) {
                        $result[$key]['Aerators Installed'] = "Yes";
                    } else {
                        $result[$key]['Aerators Installed'] = "No";
                    }
                } 
                if ( isset($appDetail['P T-stat Installed']) && $appDetail['P T-stat Installed'] != "" ) {
                    if ( $appDetail['P T-stat Installed'] == '1' ) {
                        $result[$key]['P T-stat Installed'] = "Yes";
                    } else {
                        $result[$key]['P T-stat Installed'] = "No";
                    }
                }
            }
            return $result;
        }
    }
    /**
     * returns the title header to be used in report
     *
     * @return Array
     */
    public function getTitleHeader( $params ) {
        if ( ! ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) ) {
            $titleHeader = ", GC Monthly Retrofit Report: {$params['file_identifier']} \n";
            
        } else {
            $titleHeader = ", Home Assistance Program \n, GC Monthly Retrofit Report: {$params['file_identifier']} \n";
            
        }
        return $titleHeader;
    }
}

?>
