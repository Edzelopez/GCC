<?php
 
require_once 'Efficiency/Form/Report.php';

/**
 * Gas LDC Final Report
 * 
 */
class Efficiency_Form_Report_GasLDCFinal extends Efficiency_Form_Report
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
                                   'funder'            => array(
                                                                'title'      => 'Funder',
                                                                'tableAlias' => 'gm',
                                                                'hidden'     => 'bill Union', //hidden form-field
                                                                ),
                                   'audit_invoiced' => array(
                                                                'title'      => 'Audit Invoiced',
                                                                'tableAlias' => 'gmi'
                                                                ),
                                   ); 
        $this->assign('formFields', $this->_formFields);

        $selectFields      = array(
                                   'file_identifier'    => array(
                                                                 'title'      => 'FileID',
                                                                 'tableAlias' => 'ga'
                                                                 ),
                                   'ldc_acct'           => array(
                                                                 'title'      => 'Account',
                                                                 'tableAlias' => 'ga'
                                                                 ),
                                   'audit_completed'    => array(
                                                                 'title' => 'Audit Completed',
                                                                 'tableAlias' => 'gmo'
                                                                 ),
                                   'retrofit_completed' => array(
                                                                 'title' => 'Retrofit completed',
                                                                 'tableAlias' => 'gmo'
                                                                 ),
                                   'xm_costs'           => array(
                                                                 'title' => 'Extended Costs',
                                                                 'tableAlias' => 'gmo'
                                                                 ),
                                   'xm_trc'             => array(
                                                                 'title' => 'Extended TRC NPV',
                                                                 'tableAlias' => 'gmo'
                                                                 ),
                                   'retrofit_invoiced'  => array(
                                                                 'title'      => 'Retrofit Invoiced',
                                                                 'tableAlias' => 'gmi'
                                                                 ),
                                   'bm_costs'           => array(
                                                                 'title'      => 'Audit Install Costs',
                                                                 'tableAlias' => 'gmo'
                                                                ),
                                   'basic_m3'           => array(
                                                                 'title'      => 'Audit Install Savings m3',
                                                                 'tableAlias' => 'gmo'
                                                                ),
                                   'bm_trc'             => array(
                                                                 'title'      => 'Audit Install TRC',
                                                                 'tableAlias' => 'gmo'
                                                                ),
                                   'extended_m3'        => array(
                                                                'title'       => 'Extended Savings m3',
                                                                'tableAlias'  => 'gmo'
                                                                ),
                                   'job_costs'          => array(
                                                                'title'       => 'Job Costs',
                                                                'tableAlias'  => 'gmo'
                                                                ),
                                   'job_m3saved'        => array(
                                                                'title'       => 'Job Savings m3',
                                                                'tableAlias'  => 'gmo'
                                                                ),
                                   'job_npv'            => array(
                                                                'title'       => 'Job TRC',
                                                                'tableAlias'  => 'gmo'
                                                                )
                           
                                   );
        
        $this->_selectFields = array_merge($this->_selectFields, $selectFields);
        
        // for values of a table to be included as columns
        $this->_measuresValueColumns = array(
                                             'name' => array ('values' =>
                                                              array(
                                                                    '%DHW Pipe Wrap%'   => 'DHW Pipe Wrap',
                                                                    '%Bath Aerator%'    => 'Bath Aerator',
                                                                    '%Kitchen Aerator%' => 'Kitchen Aerator',
                                                                    '%SHHD 1.25%'       => 'SHHD 1.25',
                                                                    '%SHHD 1.5%'        => 'SHHD 1.5',
                                                                    '%Attic insulation%'   => 'Attic INS',
                                                                    '%Wall insulation%'    => 'Wall INS',
                                                                    '%Basement insulation%'=> 'Bsmt INS',
                                                                    '%Floor insulation%'   => 'Flr INS',
                                                                    '%Draft proofing%'     => 'Draft proof',
                                                                    '%DHW fuel switch%' => 'Fuel switch DHW',
                                                                    '%DW Heat recovery%'=> 'DW Heat recovery',//guess work
                                                                    ),
                                                              'fields' => array ('costs' => 'Costs', 'm3saved' => 'm3 Saved')
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
        return ts('Gas LDC Final Report');
    }

    /**
     * returns the header sequence to be used in report
     *
     * @return Array
     */
    public function getHeaderSequence() {
        return array(
                     $this->_selectFields['file_identifier']['title'], 
                     $this->_selectFields['display_name']['title'], 
                     $this->_selectFields['street_address']['title'],
                     $this->_selectFields['city']['title'], 
                     $this->_selectFields['ldc_acct']['title'],
                     $this->_selectFields['audit_completed']['title'],
                     $this->_selectFields['bm_costs']['title'],
                     $this->_selectFields['basic_m3']['title'],
                     $this->_selectFields['bm_trc']['title'],
                     $this->_selectFields['retrofit_completed']['title'],
                     $this->_measuresValueColumns['name']['values']['%DHW Pipe Wrap%'],
                     'Costs-1', 'm3 Saved-1',
                     $this->_measuresValueColumns['name']['values']['%Bath Aerator%'],
                     'Costs-2', 'm3 Saved-2',
                     $this->_measuresValueColumns['name']['values']['%Kitchen Aerator%'],
                     'Costs-3', 'm3 Saved-3',
                     $this->_measuresValueColumns['name']['values']['%SHHD 1.25%'],
                     'Costs-4', 'm3 Saved-4',
                     $this->_measuresValueColumns['name']['values']['%SHHD 1.5%'],
                     'Costs-5', 'm3 Saved-5',
                     $this->_selectFields['xm_costs']['title'], 
                     $this->_selectFields['extended_m3']['title'],
                     $this->_selectFields['xm_trc']['title'], 
                     $this->_measuresValueColumns['name']['values']['%Attic insulation%'],
                     'Costs-6', 'm3 Saved-6', 
                     $this->_measuresValueColumns['name']['values']['%Wall insulation%'],
                     'Costs-7', 'm3 Saved-7',
                     $this->_measuresValueColumns['name']['values']['%Basement insulation%'],
                     'Costs-8', 'm3 Saved-8',
                     $this->_measuresValueColumns['name']['values']['%Floor insulation%'],
                     'Costs-9', 'm3 Saved-9',
                     $this->_measuresValueColumns['name']['values']['%Draft proofing%'],
                     'Costs-10', 'm3 Saved-10',
                     $this->_measuresValueColumns['name']['values']['%DHW fuel switch%'],
                     'Costs-11', 'm3 Saved-11',
                     $this->_measuresValueColumns['name']['values']['%DW Heat recovery%'],
                     'Costs-12', 'm3 Saved-12',
                     $this->_selectFields['retrofit_invoiced']['title'],
                     $this->_selectFields['job_costs']['title'],
                     $this->_selectFields['job_m3saved']['title'],
                     $this->_selectFields['job_npv']['title']
                     );
    }

    /**
     * returns the result sequence to be used in report
     *
     * @return Array
     */
    public function getResultSequence() {
        return $this->getHeaderSequence();
    }

    /**
     * returns the title header to be used in report
     *
     * @return Array
     */
    public function getTitleHeader( $params ) {
        require_once 'CRM/Core/OptionGroup.php';
       
        if (isset($params['ldc_id'])) {
            $options = CRM_Core_OptionGroup::values('ldc');
            $ldc = $options[$params['ldc_id']];
            $titleHeader = ", Energy Efficiency Assistance for Houses \n, Gas LDC Final Report: $ldc \n";
        }else{
            $titleHeader = '';
        }
        
       
        return $titleHeader;
    }
}

?>
