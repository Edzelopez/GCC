<?php

require_once 'Efficiency/Form/AddApplicant.php';
require_once 'CRM/Core/Form.php';
/**
 * Upload new default FAST.xls
 */
class Efficiency_Form_ImportFAST extends CRM_Core_Form {

    protected $_label = 'FAT';

    /**
     * Function to build the form
     *
     * @return None
     * @access public
     */
    public function buildQuickForm( ) {
        if ( defined('CIVICRM_EFFICIENCY_ELECTRIC') && CIVICRM_EFFICIENCY_ELECTRIC ) {
            $this->add( 'file', 'upload_fat', ts('Replace default FAST'), 'size=30 maxlength=60');
            $this->assign('version','FAST');
            $this->_label = 'FAST';
        } else {
            $this->add( 'file', 'upload_fat', ts('Replace default FAT'), 'size=30 maxlength=60');
            $this->assign('version','FAT');
        }
        $this->addUploadElement( 'upload_fat' );

      
        $buttons   = array( );
        $buttons[] = array ( 'type'      => 'upload',
                             'name'      => ts('Upload'),
                             'spacing'   => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                             'isDefault' => true   );

        $buttons[] = array ( 'type'      => 'cancel',
                             'name'      => ts('Cancel') );
        
        $this->addButtons( $buttons );
        
        // All validation
        $this->addFormRule( array( 'Efficiency_Form_ImportFAST', 'formRule' ) );
       
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
    static function formRule( $params, $files ,$self) {
        if ($files['upload_fat']['size'] == 0) {
            if ( defined('CIVICRM_EFFICIENCY_ELECTRIC') && CIVICRM_EFFICIENCY_ELECTRIC ) {
                return array('upload_fat' => 'Import FAST.xls required');
            } else {
                return array('upload_fat' => 'Import FAT.xls required');
            }
        }
        //Added code as browsers support different MIME type while uploading .xls file
        if ( ( $files['upload_fat']['type'] == 'application/excel' ) ) {
            return true;
        } else if ( ( $files['upload_fat']['type'] == 'text/csv' ) ) {
            return true;
        } else if ( ( $files['upload_fat']['type'] == 'application/vnd.ms-excel' ) ) {
            return true;
        } else if ( ( $files['upload_fat']['type'] == 'application/msexcel' ) ) {
            return true;
        }else if ( ( $files['upload_fat']['type'] == 'application/ms-excel' ) ) {
            return true;
        }else {
            return array('upload_fat' => '.xls file required');
        } 
        
        return true;
    }
    
    /**
     * Process the uploaded files
     *
     * @return void
     * @access public
     */
    public function postProcess( ) {
        if ( ! ( $this->_action &  CRM_Core_Action::VIEW ) ) {
            $uploadFat = $this->controller->exportValues( $this->_name );
           
            require_once 'CRM/Core/Config.php';
            require_once 'CRM/Utils/File.php';
            
            self::installFAST($uploadFat);

            $session = CRM_Core_Session::singleton();
            $url = CRM_Utils_System::url('civicrm/efficiency/applicant/importfile', 'reset=1' );
            $session->pushUserContext( $url );

            CRM_Core_Session::setStatus( "{$this->_label}.xls file has been uploaded." );
        }
    }
    
    private function installFAST($uploadFat) {
        require_once 'CRM/Core/Config.php';
        require_once 'CRM/Utils/File.php';
        
        $config =& CRM_Core_Config::singleton();
        $directoryName = $config->customFileUploadDir . 'Gcc';
        $data = $uploadFat['upload_fat']['name'];
        CRM_Utils_File::createDir( $directoryName );

        if ( defined('CIVICRM_EFFICIENCY_ELECTRIC') && CIVICRM_EFFICIENCY_ELECTRIC ) {
            if ( ! rename( $data, $directoryName . DIRECTORY_SEPARATOR . 'FAST.xls' ) ) {
                CRM_Core_Error::fatal( ts( 'Could not move custom file to custom upload directory' ) );
                break;
            }
        } else {
            if ( ! rename( $data, $directoryName . DIRECTORY_SEPARATOR . 'FAT.xls' ) ) {
                CRM_Core_Error::fatal( ts( 'Could not move custom file to custom upload directory' ) );
                break;
            }
        }
        
    }
}
?>
