<?php
require_once 'Efficiency/Form/AddApplicant.php';
require_once 'CRM/Custom/Form/CustomData.php';
require_once 'CRM/Core/BAO/CustomGroup.php';
require_once "api/v3/CustomValue.php";
require_once "CRM/Core/DAO/EntityFile.php";
require_once "CRM/Core/DAO/File.php";
require_once 'CRM/Core/BAO/CustomValueTable.php';
require_once 'CRM/Contact/BAO/Contact.php';
require_once 'CRM/Core/DAO/CustomGroup.php';
require_once 'CRM/Core/BAO/CustomField.php';

class Efficiency_Form_AddApplicant_Files extends Efficiency_Form_AddApplicant
{

    static  $_links  = null;
       
    public function preProcess( ) 
    {
        parent::preProcess();

        if ( $this->_action & CRM_Core_Action::VIEW ) {
            $session = CRM_Core_Session::singleton();
        
            //default tab selected on cancel button
            $url = CRM_Utils_System::url('civicrm/efficiency/applicant/files/view', 
                                         'reset=1&cid=' . $this->_applicantId );
        
            $session->pushUserContext( $url );
        }

        if ( defined('CIVICRM_EFFICIENCY_ELECTRIC') && CIVICRM_EFFICIENCY_ELECTRIC ) {
            $customGroupName  = "gcc_measures_other";
            $this->_groupId   = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_CustomGroup", $customGroupName, 'id', 'name' );
            $entityType       = CRM_Contact_BAO_Contact::getContactType( $this->_applicantId );
            $entitySubType    = CRM_Contact_BAO_Contact::getContactSubType( $this->_applicantId );
            $this->_groupTree = & CRM_Core_BAO_CustomGroup::getTree( $entityType, $this, $this->_applicantId, 
                                                                     $this->_groupId, $entitySubType );
        }    
        $this->assign( 'fat_fast',  $this->_fileName );
  
        $entityFileDAO =& new CRM_Core_DAO_EntityFile();
        $entityFileDAO->entity_id    = $this->_applicantId;
        $entityFileDAO->entity_table = "civicrm_contact";
        $entityFileDAO->find();
        $this->assign('cid', $this->_applicantId);

        $fileRows = array();
        $count=0;
        while ($entityFileDAO->fetch()) {
            $fileDAO =& new CRM_Core_DAO_File();
            $fileDAO->id = $entityFileDAO->file_id;
            $fileDAO->find(true);
                                           
            $fileRows[$count]['name'] = self::getFileName($fileDAO->uri);
            $fileRows[$count]['description'] = $fileDAO->description;
            $fileRows[$count]['data'] = $fileDAO->uri;
            $fileRows[$count]['date'] = $fileDAO->upload_date;
            $fileRows[$count]['fileURL']  = CRM_Utils_System::url( 'civicrm/file', 
                                                                   "reset=1&id={$fileDAO->id}&eid={$entityFileDAO->entity_id}&quest=Applicant" );
            if (  CRM_Core_Permission::check( 'delete_app_files' ) ){

                $fileRows[$count]['deleteURL']  = '<a href=' .CRM_Utils_System::url( 'civicrm/efficiency/delete/file', 
                                                                                     "reset=1&id={$fileDAO->id}&eid={$entityFileDAO->entity_id}&quest=Applicant&action=delete" ) . '>delete</a>'; 

            }
            
            $count++;
        }
 
        if (! empty($fileRows)) {
            // Remove duplicate filenames for display, preserving the most recent
            $uniqueFilenames = array();
            
            foreach (array_reverse($fileRows, true) as $n => $row) {
                if (in_array($row['name'], $uniqueFilenames)) {
                    unset($fileRows[$n]);
                } else {
                    $uniqueFilenames[] = $row['name'];
                }
            }
            
            
            if (  CRM_Core_Permission::check( 'upload_app_FAST' ) ){
                
                
                $uploadFAST =  '<a href="' . CRM_Utils_System::url( 'civicrm/efficiency/applicant/import', 
                                                                    "reset=1&cid=" .  $this->_applicantId )
                               .'">Upload '.$this->_fileName .'</a>';
            }
            
            $this->assign('uploadFAST',$uploadFAST);
        }
        
        $uploadApp_perm = 0;    
        if (  ! ( CRM_Core_Permission::check( 'upload_app_files' ) ) ){
            $uploadApp_perm = 1;
        }
        $this->assign( 'uploadApp_perm',$uploadApp_perm );
        $this->assign('rows', $fileRows);
        
    }
    
    /**
     * Function to actually build the form
     *
     * @return void
     * @access public
     */
   
    public function buildQuickForm( ) 
    {        
        // $attributes = CRM_Core_DAO::getAttribute('CRM_Core_DAO_File' );

        for($i = 1;$i<=5;$i++){
            $this->add('file', 'uploadFile-' . $i, ts('Upload File'), 'size=30 maxlength=60');
            $this->add('text', 'description-' . $i, ts('Description'),'size=40 maxlength=60' );
            $this->addUploadElement( 'uploadFile-'.$i );
        }
        /*
        //Code to add H&S checdone field for electric
        if ( defined('CIVICRM_EFFICIENCY_ELECTRIC') && CIVICRM_EFFICIENCY_ELECTRIC ) {
            $fieldID = CRM_Core_BAO_CustomField::getCustomFieldID( 'H_S_checkDone' );
            CRM_Core_BAO_CustomField::addQuickFormElement( $this ,'H_S_checkDone', $fieldID );

        }
        //End of code      
        */

        $buttons   = array( );
                       
        $buttons[] = array ( 'type'      => 'upload',
                             'name'      => ts('Upload'),
                             'spacing'   => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                             'isDefault' => true   );

        $buttons[] = array ( 'type'      => 'cancel',
                             'name'      => ts('Cancel') );
        
        $this->addButtons( $buttons );
       
        $this->add( 'hidden', 'fid',$this->_fid );

        $this->addFormRule( array( 'Efficiency_Form_AddApplicant_Files', 'formRule' ));

    }
    
    

    /**
     * global validation rules for the form
     *
     * @param array $fields posted values of the form
     *
     * @return array list of errors to be posted back to the form
     * @static
     * @access public
     */
    static function formRule( $params, $files, $self ) 
    {
        $errors = array( );
        /*  
        //To give validation message for H&S checkdone field in electric
        if ( defined('CIVICRM_EFFICIENCY_ELECTRIC') && CIVICRM_EFFICIENCY_ELECTRIC ) {
            foreach( $params as $key => $value ) {
                if ( strstr ( $key,'custom' ) && $params[$key] == '' )  {
                    $errors[$key] = ts( 'Complete H&S Check Done field' );
                    break;
                }
            }
        }
        //end of code 
        */
        foreach($files  as $key => $value ) {
            if ( !empty( $value['name'] ) ){
                
                if ( ! preg_match( '/^[A-Z0-9]+$/', substr( $value['name'] , 0, 4 ) )  ) {
                    $errors[$key] = ts( 'File  can consist of only Capital alphanumeric characters' );
                    
                } 
                if ( defined('CIVICRM_EFFICIENCY_ELECTRIC') && CIVICRM_EFFICIENCY_ELECTRIC ) {
                    if ( ( substr_compare( $value['name'], $params['fid'], 0, 11) != 0 )) {
                        $errors[$key] = ts( 'Filename error:'.$value['name'] );
                    } elseif ( ctype_alpha( substr( $value['name'] , 0, 11 ) ) )  {
                    }/* else {
                        $errors[$key] = ts( 'Filename error:'.$value['name'] ); 
                    }*/  
                } else {
                    if ( substr_compare( $value['name'], $params['fid'], 0, 10) != 0 ) {
                        $errors[$key] = ts( 'Filename error:'.$value['name'] );
                    } elseif ( ctype_alpha( substr( $value['name'] , 0, 10 ) ) )  {
                    }/* else {
                        $errors[$key] = ts( 'Filename error:'.$value['name'] ); 
                    }*/  
                }
                if ( ctype_lower( substr( $value['name'] , 0, 1 ) ) ) {                    
                    // Check for 1st character lowercase in file name
                    $errors[$key] = ts( 'Filenames must start with capital letters' ); 
                }
           }
        }
        return $errors;
    }
    
    /**
     * process the form after the input has been submitted and validated
     *
     * @access public
     * @return void
     */
    public function postProcess() 
    {       
        if ( ! ( $this->_action &  CRM_Core_Action::VIEW ) ) {
            
            $params = $this->controller->exportValues( $this->_name );
            /*
            //Code for Electric version to insert custom field value
            if ( $this->_isElectric == 1 ) {


                $H_s_checkDone           =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'H_S_checkDone' );
                
                $values = array( 
                                $H_s_checkDone  => $params['H_S_checkDone'] ,
                                'version'       => 3,
                                'entity_id'     =>$this->_applicantId,
                                 );
                
                require_once 'api/api.php';
                $result = civicrm_api( 'custom_value','create',$values );
                            
                
                //  foreach( $params as $key => $value ) {
                //                     if ( strstr ( $key,'custom' ) ) {
                //                         $fields = array();
                //                         CRM_Core_BAO_CustomValueTable::postProcess( $params,
                //                                                                     $fields,
                //                                                                     'civicrm_contact',
                //                                                                     $this->_applicantId,
                //                                                                     'Individual' );
                //                         break;
                //                     }
                //                 }
            }
            //End of code
            */
            for($i = 1;$i<=5;$i++){
                $params['description'] = $params['description-'.$i];
                $element =  'uploadFile-'.$i;
                if (array_key_exists( $element , $params )) {
                    CRM_Core_BAO_File::filePostProcess( $params['uploadFile-'.$i]['name'],
                                                        null,
                                                        'civicrm_contact', 
                                                        $params['cid'],
                                                        'Applicant', 
                                                        false,
                                                        $params,
                                                        null,
                                                        $params["uploadFile-".$i]["type"] );
                    CRM_Core_Session::setStatus( ts($i . "Files Uploaded Successfully. "));
                }
                
                
            }
            require_once 'Efficiency/BAO/Applicant.php';
            Efficiency_BAO_Applicant::setReview($this->_applicantId);
            
        }
        
        $buttonName = $this->controller->getButtonName( );
        if ( $this->_applicantId ) {
            if ( $buttonName = '_qf_Files_upload' ) {
                $cid = $this->_applicantId;
                $class = strtolower( CRM_Utils_String::getClassName( $this->_name ) );
                CRM_Utils_System::redirect( CRM_Utils_System::url( "civicrm/efficiency/applicant/{$class}/view",
                                                                   "reset=1&action=view&cid={$cid}".
                                                                   "&selectedChild={$class}" ) );
            }
        }
        parent::endPostProcess();
    }
    
    
    
    /**
     * Return a descriptive name for the page, used in wizard header
     *
     * @return string
     * @access public
     */
    public function getTitle()
    {
        return ts('File');
    }
    
    /**
     * Restore original filename for display.
     */
    static function getFileName($file) {
        $dpos = strrpos($file, '.'); // rightmost dot
        $upos = strrpos($file, '_'); // rightmost underscore
        if ($dpos && $upos && ($dpos - $upos - 1 == 32)) {
            $file = substr($file, 0, $upos) . substr($file, $dpos);
        }
        return $file;
    }
    
}
