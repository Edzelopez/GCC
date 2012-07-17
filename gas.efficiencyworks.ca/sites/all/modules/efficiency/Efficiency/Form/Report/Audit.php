<?php


require_once 'Efficiency/Form/Report.php';

/**
 * This class generates audit report
 * 
 */
class Efficiency_Form_Report_Audit extends Efficiency_Form_Report
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
                                   'file_identifier'    => array(
                                                                 'title' => 'FileID',
                                                                 'tableAlias' => 'ga'
                                                                 ),
                                   'audit_completed'    => array(
                                                                 'title' => 'Audit Completed',
                                                                 'tableAlias' => 'gmo'
                                                                 ),
                                   'audit_type_id'      => array(
                                                                 'title' => 'Audit Time',
                                                                 'tableAlias' => 'gmo'
                                                                 ),
                                   'audit_invoiced'     => array(
                                                                 'title' => 'Audit Invoiced',
                                                                 'tableAlias' => 'gmi'
                                                                 ),
                                   'audit_completed_blank' => array(
                                                                    'title' => 'Report blank dates only',
                                                                    'desc'  => 'Blank dates only',
                                                                    'tableAlias' => 'gmo',
                                                                    'noSelect'   => true, 
                                                                    ),
                                   'audit_invoiced_blank'  => array(
                                                                    'title' => 'Report blank dates only',
                                                                    'desc'  => 'Blank dates only',
                                                                    'tableAlias' => 'gmi',
                                                                    'noSelect'   => true, 
                                                                    ),
                                   ); 
        } else {
            $this->_formFields = array(
                                   'file_identifier'    => array(
                                                                 'title' => 'OPA FileID',
                                                                 'tableAlias' => 'ga'
                                                                 ),
                                   'audit_completed'    => array(
                                                                 'title' => 'Audit Completed',
                                                                 'tableAlias' => 'gmo'
                                                                 ),
                                   'audit_invoiced'     => array(
                                                                 'title' => 'Audit Invoiced',
                                                                 'tableAlias' => 'gmi'
                                                                 ),
                                   'audit_completed_blank' => array(
                                                                    'title' => 'Report blank dates only',
                                                                    'desc'  => 'Blank dates only',
                                                                    'tableAlias' => 'gmo',
                                                                    'noSelect'   => true, 
                                                                    ),
                                   'audit_invoiced_blank'  => array(
                                                                    'title' => 'Report blank dates only',
                                                                    'desc'  => 'Blank dates only',
                                                                    'tableAlias' => 'gmi',
                                                                    'noSelect'   => true, 
                                                                    ),
                                   ); 
        }
        $this->assign('formFields', $this->_formFields);
        $this->_selectFields = array_merge($this->_formFields, $this->_selectFields );
        // Our participant's status
        $this->_statusField = array('title' => 'Participant Status');
    }

    /**
     * name of export file.
     *
     * @param string $output type of output
     * @return string name of the file
     */
    public function getExportFileName( $output = 'csv') {
        return ts('GC Audit Report');
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
                     $this->_statusField['title'], 
                     $this->_selectFields['audit_completed']['title'],
                     $this->_selectFields['audit_type_id']['title'], 
                     $this->_selectFields['audit_invoiced']['title']
                     );
        } else {
            return array(
                     $this->_selectFields['file_identifier']['title'], 
                     $this->_selectFields['first_name']['title'], 
                     $this->_selectFields['last_name']['title'], 
                     $this->_selectFields['street_address']['title'],
                     $this->_selectFields['city']['title'],
                     $this->_statusField['title'], 
                     $this->_selectFields['audit_completed']['title'],
                     $this->_selectFields['audit_invoiced']['title']
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

    /**
     * returns the title header to be used in report
     *
     * @return Array
     */
    public function getTitleHeader( $params ) {
        if ( ! ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) ) {
            $titleHeader = ", Energy Efficiency Assistance for Houses \n, Monthly Audit Report: {$params['file_identifier']} \n";
        } else {
            $titleHeader = ", Home Assistance Program \n, Monthly Audit Report: {$params['file_identifier']} \n";
            
        }
        return $titleHeader;
    }
}    
?>
