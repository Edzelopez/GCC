<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.0                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2011                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2011
 * $Id$
 *
 */

require_once 'CRM/Core/Form.php';
require_once 'CRM/Core/BAO/CustomValueTable.php';
require_once 'CRM/Core/BAO/CustomField.php';
require_once 'Efficiency/BAO/Applicant.php';
/**
 * This class generates form components for processing Event  
 * 
 */
class Efficiency_Form_Import extends CRM_Core_Form
{
    protected $_cid;
    private $_uploadNames;
    protected $_fid;
    /**
     * Function to set variables up before form is built
     *
     * @return void
     * @access public
     */
    public function preProcess()
    {
         $this->_cid = CRM_Utils_Request::retrieve( 'cid', 'Positive', $this, true );
         $this->_contextView = CRM_Utils_Request::retrieve( 'context', 'String', $this, false, 0, 'REQUEST' );
     
        if ( $this->_cid ) {
            $cancelUrl = CRM_Utils_System::url('civicrm/efficiency/applicant/files/view',
                                               "reset=1&action=view&cid={$this->_cid}");
            $this->assign( 'cancelUrl', $cancelUrl );

            $fieldID     = CRM_Core_BAO_CustomField::getCustomFieldID( 'File_Identifier' );
            $customParams =array(
                                 'entityID'         => $this->_cid,
                                 'custom_'.$fieldID => 1
                                 );
            
            $identifier  = CRM_Core_BAO_CustomValueTable::getValues( $customParams );
            $this->_fid = $identifier['custom_'.$fieldID];
            $this->add( 'hidden', 'fid',$this->_fid );
            CRM_Utils_System::setTitle( ts( $identifier['custom_'.$fieldID] ) );
        }
        
        if ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) {
            $this->assign( 'fat_fast', 'FAST' );
        } else {
            $this->assign( 'fat_fast', 'FAT' );
        }
        
    }
    
    function setDefaultValues( ) {
        
        $defaults = array( );
        $fieldID = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'H_S_checkDone' );
        $customParams =array(
                             'entityID' => $this->_cid,
                             $fieldID   => 1
                             );
        
        $H_S_checkDone  = CRM_Core_BAO_CustomValueTable::getValues( $customParams );
        $defaults['H_S_checkDone'] = $H_S_checkDone[$fieldID];
        return $defaults;

    }
    
    /**
     * Function to actually build the form
     *
     * @return None
     * @access public
     */
    public function buildQuickForm( ) {
        
        $import_arr = array();
         // Upload button
        $this->addButtons(array(
                                array ( 
                                       'type' => 'upload',
                                       'name' => ts('Continue >>'),
                                       'spacing' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                                       'isDefault' => true
                                        )// ,
                                // array (
                                //        'type' => 'submit',
                                //        'name' => ts('Cancel') ),
                                )
                          );
        if ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) {
            $import_arr = array(
                                'name' => 'uploadFileFAST',
                                'description' => 'Import FAST',
                                'required' => true,
                                );
        } else {
            $import_arr = array(
                                'name' => 'uploadFileFAST',
                                'description' => 'Import FAT',
                                'required' => true,
                                );
        }
        // Fieldnames and descriptions for file uploads
        $uploadFields = array(
                              array(
                                    'name' => 'uploadFile',
                                    'description' => 'Import Data File',
                                    'required' => true,
                                    ),
                              $import_arr
                              );
        
        foreach (range(0,4) as $i) {
            $uploadFields[] = array(
                                    'name' => 'uploadFileOther' . $i,
                                    'description' => 'Other File',
                                    'required' => false,
                                    );
        }

        $uploadFields_Counter = 0;
        // Add fields
        foreach ($uploadFields as $f) {
            $this->add( 'file', $f['name'], ts($f['description']), 'size=80 maxlength=60', $f['required']);
            $uploadFields_Counter++;
            if ( $uploadFields_Counter == 2 ) {
                //Code to add H&S checdone field for electric
                if ( defined('CIVICRM_EFFICIENCY_ELECTRIC') && CIVICRM_EFFICIENCY_ELECTRIC ) {
                    $fieldID = CRM_Core_BAO_CustomField::getCustomFieldID( 'H_S_checkDone' );
                    CRM_Core_BAO_CustomField::addQuickFormElement( $this ,'H_S_checkDone', $fieldID );
                }
                //End of code
            }
        }             
        
        // All validation
        $this->addFormRule( array( 'Efficiency_Form_Import', 'formRule' ), $this );
        
        // Save upload names for postProcess
        $this->_uploadNames = array();
        foreach ($uploadFields as $f) {
            $this->_uploadNames[] = $f['name'];
        }
        
        // Workaround for multiple file uploads
        $this->set( 'uploadNames', $this->_uploadNames );
        $config =& CRM_Core_Config::singleton( );
                
        // $this->controller->fixUploadAction( $config->customFileUploadDir, $this->_uploadNames );
            parent::buildQuickForm();
    }
    
    /**
     * function for validation
     *
     * @param array $params (reference ) an assoc array of name/value pairs
     *
     * @return mixed true or array of errors
     * @access public
     * @static
     */
    static function formRule( $params, $files, $self ) {
       
        require_once 'Efficiency/BAO/Import.php';
        return  Efficiency_BAO_Import::validate( $files ,$params['fid'],$self->_cid );
    }
    
    /**
     * Process the uploaded files
     *
     * @return void
     * @access public
     */
    public function postProcess( ) {
        if ( ! ( $this->_action &  CRM_Core_Action::VIEW ) ) {
            
            $params = $this->controller->exportValues( $this->_name );
            require_once 'Efficiency/BAO/Import.php';
            
            $import =& new Efficiency_BAO_Import( $this->_cid );
            $import->import($params[$this->_uploadNames[0]],$this->_cid);

            // Workaround default handling of 'uploadFile' by deleting it
            unlink($params[$this->_uploadNames[0]]['name']);
            
            // Save all the uploaded files, except the first (CSV data)
            require_once 'CRM/Core/BAO/File.php';
            $uploadCount = 0;
             
            foreach (array_splice($this->_uploadNames, 1) as $uploadName) {
                if (!empty($params[$uploadName]['name'])) {
                    
                    $fileName = $params[$uploadName]['name'];
                    
                    if ($uploadName == 'uploadFile') {
                        $params['description'] = 'Import Data File';
                    } elseif ($uploadName == 'uploadFileFAST') {
                        if ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) {
                            $params['description'] = 'Import FAST';
                        } else {
                            $params['description'] = 'Import FAT';
                        }
                    } else {
                        $params['description'] = '';
                    }
                    
                      CRM_Core_BAO_File::filePostProcess($fileName, null, 'civicrm_contact', $this->_cid, 'Applicant', false, $params, $uploadName,$params[$uploadName]['type']);
                     $uploadCount++;
                }
            }
            
            if ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) {
                Efficiency_BAO_Applicant::setFieldValue( 'gcc_measures_other', 'h_s_checkdone', $this->_cid , $params['H_S_checkDone'] ); }
            drupal_set_message($uploadCount . ' ' . ts('files uploaded'));
            
            /*** Fix for - Set status to review Start ********/
            require_once 'Efficiency/BAO/Applicant.php';
            Efficiency_BAO_Applicant::setReview($this->_cid);
            
            /*** Fix for - Set status to review End ********/
            
            $url = "civicrm/efficiency/applicant/projectdetails/view";
            $urlParams = "reset=1&cid={$this->_cid}&action=view&selectedChild=projectdetails";
            CRM_Utils_System::redirect( CRM_Utils_System::url( $url, $urlParams ) );
            
            
            //    /*** Fix for - After UPLOAD FAT, return to Project Details screen of the file uploaded. Start **/
//             return CRM_Utils_System::redirect( CRM_Utils_System::url( 'civicrm/efficiency/application/view', 'reset=1&action=view&cid='. $this->_cid .'&selectedChild=audit' ) );
//             /  *** Fix for - After UPLOAD FAT, return to Project Details screen of the file uploaded. End **/
        }     
    }
}
?>
