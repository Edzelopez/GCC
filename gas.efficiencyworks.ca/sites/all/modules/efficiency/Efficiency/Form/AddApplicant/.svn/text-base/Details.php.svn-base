<?php
require_once 'Efficiency/Form/AddApplicant.php';
require_once 'CRM/Custom/Form/CustomData.php';
require_once 'CRM/Core/BAO/CustomGroup.php';
require_once "api/v3/CustomValue.php";
require_once 'api/v3/Relationship.php';
require_once 'api/v3/Phone.php';
require_once 'api/v3/Contact.php';
require_once 'api/v3/Email.php';
require_once 'api/v3/Address.php';
require_once 'CRM/Core/DAO.php';
require_once 'CRM/Core/DAO/CustomGroup.php';
require_once 'CRM/Core/BAO/CustomOption.php';
require_once 'CRM/Contact/BAO/Contact.php';
require_once 'CRM/Core/BAO/CustomField.php';

class Efficiency_Form_AddApplicant_Details extends Efficiency_Form_AddApplicant
{
    public function preProcess( ) 
    {
        parent::preProcess( );

        // Upload url to be displayed against "Landlord Audit Consent" field in edit Household
        $addFilesUrl      =  CRM_Utils_System::url('civicrm/efficiency/applicant/files/update', 
                                                   'reset=1&action=update&cid=' . $this->_applicantId );
        $this->assign( 'addFilesUrl', $addFilesUrl );

        $customGroupName  = "GCC_Applicant";
        $this->_groupId   = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_CustomGroup", $customGroupName, 'id', 'name' );
        $entityType       = CRM_Contact_BAO_Contact::getContactType( $this->_applicantId );
        $entitySubType    = CRM_Contact_BAO_Contact::getContactSubType( $this->_applicantId );
        $this->_groupTree = & CRM_Core_BAO_CustomGroup::getTree( $entityType, $this, $this->_applicantId, 
                                                                 $this->_groupId, $entitySubType );
        if ( $this->_action & CRM_Core_Action::VIEW ) {
            $session = CRM_Core_Session::singleton();
            //default tab selected on cancel button
            $url = CRM_Utils_System::url('civicrm/efficiency/applicant/details/view', 
                                         'reset=1&cid=' . $this->_applicantId );
            
            $session->pushUserContext( $url );
            
            $grouptreeFields = $this->_groupTree[$this->_groupId]['fields'];
            
            if  ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ){
                $auditor2QA            = CRM_Core_BAO_CustomField::getCustomFieldID( 'Auditor2QA' );
                $QA_status             = CRM_Core_BAO_CustomField::getCustomFieldID( 'QA_status' );
                $Other_FUNDS           = CRM_Core_BAO_CustomField::getCustomFieldID( 'Other_FUNDS' );
                $Referrals_auditor     = CRM_Core_BAO_CustomField::getCustomFieldID( 'Referrals_auditor' );
                $Referrals_concatenate = CRM_Core_BAO_CustomField::getCustomFieldID( 'Referrals_concatenate' );
                $CFM50_before          = CRM_Core_BAO_CustomField::getCustomFieldID( 'CFM50_before' );
                $WxAuditDATE           = CRM_Core_BAO_CustomField::getCustomFieldID( 'CORE_SHELL_DATE' );
                $Status                = CRM_Core_BAO_CustomField::getCustomFieldID( 'Status' );
                //  $autoStatus            = CRM_Core_BAO_CustomField::getCustomFieldID( '' );
                $filds = array( $auditor2QA, $QA_status, $Other_FUNDS, $Referrals_auditor, $Referrals_concatenate, $CFM50_before, $WxAuditDATE,$Status );
                
                foreach( $grouptreeFields as $key => $value ) {
                    $elements = explode( '_' ,$key );
                    if ( in_array($key , $filds ) ){
                        //  unset( $this->_elements[$value] );
                        unset($this->_groupTree[$this->_groupId]['fields'][$key]);
                        
                    }
                    
                }
            }
            $viewfields  = CRM_Core_BAO_CustomGroup::buildCustomDataView( $this, $this->_groupTree, false, null, "dnc_" );
            $this->assign( 'viewCustomData', $viewfields );
            
            if ( CRM_Core_Permission::check( 'edit_app_details' ) ) {
                $editurl = CRM_Utils_System::url('civicrm/efficiency/applicant/details/update', 'reset=1&action=update&cid=' . $this->_applicantId );
                
                if( $editurl ) {
                    $this->assign( 'editUrl', $editurl );
                }
            }
        }
        
         $this->_groupTree = CRM_Core_BAO_CustomGroup::formatGroupTree( $this->_groupTree, 1, $this );
        
    }
    
    
    /**
     * This function sets the default values for the form. Relationship that in edit/view action
     * the default values are retrieved from the database
     * 
     * @access public
     * @return void
     */
    function setDefaultValues( )
    {
        $this->_defaults = array( );
        CRM_Core_BAO_CustomGroup::setDefaults( $this->_groupTree, $this->_defaults );
        
        return $this->_defaults;
    }
    
    
    /**
     * Function to actually build the form
     *
     * @return void
     * @access public
     */
    
    public function buildQuickForm( ) 
    {      
          $session =& CRM_Core_Session::singleton( );
        
        //Retreive current user's userid
        $this->_uid = $session->get('userID');
        $subType    = CRM_Contact_BAO_Contact::getContactSubType( $this->_uid, "," );

        if ( isset( $this->_groupTree ) ) {
            
            $grouptreeFields = $this->_groupTree[$this->_groupId]['fields'];
            $filedID               = CRM_Core_BAO_CustomField::getCustomFieldID( 'File_Identifier' );
            if  ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ){
                $auditor2QA                        = CRM_Core_BAO_CustomField::getCustomFieldID( 'Auditor2QA' );
                $QA_status                          = CRM_Core_BAO_CustomField::getCustomFieldID( 'QA_status' );
                $Other_FUNDS                     = CRM_Core_BAO_CustomField::getCustomFieldID( 'Other_FUNDS' );
                $Referrals_auditor               = CRM_Core_BAO_CustomField::getCustomFieldID( 'Referrals_auditor' );
                $Referrals_concatenate        = CRM_Core_BAO_CustomField::getCustomFieldID( 'Referrals_concatenate' );
                $CFM50_before                     = CRM_Core_BAO_CustomField::getCustomFieldID( 'CFM50_before' );
                $WxAuditDATE                      = CRM_Core_BAO_CustomField::getCustomFieldID( 'CORE_SHELL_DATE' );
                $Status                                 = CRM_Core_BAO_CustomField::getCustomFieldID( 'Status' );

                $enrollDate         = CRM_Core_BAO_CustomField::getCustomFieldID( 'Date_Application_Entered' );
                $payHeat             = CRM_Core_BAO_CustomField::getCustomFieldID( 'Pay_heating_bill' );
                $gasUtil               = CRM_Core_BAO_CustomField::getCustomFieldID( 'Gas_Util' );
                $gasAcct              = CRM_Core_BAO_CustomField::getCustomFieldID( 'Gas_Acct' );
                $tenur                  = CRM_Core_BAO_CustomField::getCustomFieldID( 'Tenure' );
                $priHeat               = CRM_Core_BAO_CustomField::getCustomFieldID( 'Primary_Heating_Fuel' );
                $dhw                    = CRM_Core_BAO_CustomField::getCustomFieldID( 'DHW_Fuel' );
                $houseType          = CRM_Core_BAO_CustomField::getCustomFieldID( 'House_Type' );
                $incomeBasis       = CRM_Core_BAO_CustomField::getCustomFieldID( 'Income_Basis' );
                $referral              = CRM_Core_BAO_CustomField::getCustomFieldID( 'Referral' );
                 //  $autoStatus            = CRM_Core_BAO_CustomField::getCustomFieldID( '' );
                $filds = array( $auditor2QA, $QA_status, $Other_FUNDS, $Referrals_auditor, $Referrals_concatenate, $CFM50_before, $WxAuditDATE,$Status );
               
               
                foreach( $grouptreeFields as $key => $value ) {
                    if ( $value['label'] == 'HAP FileID' ) {
                        unset($this->_groupTree[$this->_groupId]['fields'][$key]);
                    }
                    $elements = explode( '_' ,$key );
                    if ( in_array($key , $filds ) ){
                        //  unset( $this->_elements[$value] );
                        unset($this->_groupTree[$this->_groupId]['fields'][$key]);
                    }
                }
            }else{
                foreach( $grouptreeFields as $key => $value ) {
                    if ( $value['label'] == 'QA Status' || $value['label'] =='Status' || $value['label'] =='Auto Status') {
                        unset($this->_groupTree[$this->_groupId]['fields'][$key]);
                    }
                }
            }
            
            $dateFieldid        = CRM_Core_BAO_CustomField::getCustomFieldID( 'Date_Application_Entered' );
            CRM_Core_BAO_CustomGroup::buildQuickForm( $this, $this->_groupTree );
            foreach ( $this->_elements as $key => $value ) {
                if( strstr ( $value->_attributes['name'],'custom_'.$filedID.'_' ) ) {
                    $value->_attributes['readonly']='readonly';
                }
                if( $grouptreeFields[$filedID]['element_value'] ) {
                    if( ( strstr ( $value->_attributes['name'],'custom_'.$dateFieldid.'_' ) ) && ( $this->_actionString == 'add') ) {
                            $date =  date("m/d/Y", mktime(0,0,0,date("m"),date("d"),date("Y")));
                            $value->setValue( $date  );
                    }
                }
            }
        }
        if( $subType == 'Outreach' ) {
         foreach ( $grouptreeFields as $keys => $values ) {
                    $elementName = $values['element_name'];
                    $elementValue = $values['element_value'];
                    if ( strstr( $elementName, 'custom_'.$filedID.'_' ) ) {
                        if( $elementValue ) { 
                               foreach ( $this->_groupTree[$this->_groupId]['fields'] as $key => $value ) {
                                   if ( strstr( $value['element_name'], 'custom_'.$enrollDate.'_' ) ) {
                                       $this->addRule( $value['element_name'] , ts("Please Enter a value for %1.",array( 1=>$value['label'] ) ), 'required' );
                                   }
                                   if ( strstr( $value['element_name'], 'custom_'.$payHeat.'_' ) ) {
                                       $this->addRule( $value['element_name'] , ts("Please Enter a value for %1.",array( 1=>$value['label'] ) ), 'required' );
                                   }
                                    if ( strstr( $value['element_name'], 'custom_'.$gasUtil.'_' ) ) {
                                       $this->addRule( $value['element_name'] , ts("Please Enter a value for %1.",array( 1=>$value['label'] ) ), 'required' );
                                   }
                                     if ( strstr( $value['element_name'], 'custom_'.$gasAcct.'_' ) ) {
                                       $this->addRule( $value['element_name'] , ts("Please Enter a value for %1.",array( 1=>$value['label'] ) ), 'required' );
                                   }
                                      if ( strstr( $value['element_name'], 'custom_'.$tenur.'_' ) ) {
                                       $this->addRule( $value['element_name'] , ts("Please Enter a value for %1.",array( 1=>$value['label'] ) ), 'required' );
                                   }
                                       if ( strstr( $value['element_name'], 'custom_'.$priHeat.'_' ) ) {
                                       $this->addRule( $value['element_name'] , ts("Please Enter a value for %1.",array( 1=>$value['label'] ) ), 'required' );
                                   }
                                        if ( strstr( $value['element_name'], 'custom_'.$dhw.'_' ) ) {
                                       $this->addRule( $value['element_name'] , ts("Please Enter a value for %1.",array( 1=>$value['label'] ) ), 'required' );
                                   }
                                          if ( strstr( $value['element_name'], 'custom_'.$houseType.'_' ) ) {
                                       $this->addRule( $value['element_name'] , ts("Please Enter a value for %1.",array( 1=>$value['label'] ) ), 'required' );
                                   }
                                            if ( strstr( $value['element_name'], 'custom_'.$incomeBasis.'_' ) ) {
                                       $this->addRule( $value['element_name'] , ts("Please Enter a value for %1.",array( 1=>$value['label'] ) ), 'required' );
                                   }
                                            if ( strstr( $value['element_name'], 'custom_'.$referral.'_' ) ) {
                                       $this->addRule( $value['element_name'] , ts("Please Enter a value for %1.",array( 1=>$value['label'] ) ), 'required' );
                                   }
                               }
                        }
                    }
         }
        }
         parent::buildQuickForm( );  
           $this->addFormRule( array('Efficiency_Form_AddApplicant_Details', 'formRule' ) );
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
    static function formRule( $params )
    {
      
        
  
        $errors = array( );
        // CRM_Core_Error::debug( '$params', $params );
        // $custom = array( );
        // $filedID               = CRM_Core_BAO_CustomField::getCustomFieldID( 'File_Identifier' );
        // $enrollDate         = CRM_Core_BAO_CustomField::getCustomFieldID( 'Date_Application_Entered' );
        // $occupants          = CRM_Core_BAO_CustomField::getCustomFieldID( 'Occupants' );
        // $adults                = CRM_Core_BAO_CustomField::getCustomFieldID( 'Adults' );
        // $pLanguage         = CRM_Core_BAO_CustomField::getCustomFieldID( 'PLanguage' );
        // $payHeat             = CRM_Core_BAO_CustomField::getCustomFieldID( 'Pay_heating_bill' );
        // $payElec              = CRM_Core_BAO_CustomField::getCustomFieldID( 'Pay_electric_bill' );
        // $ldc                     = CRM_Core_BAO_CustomField::getCustomFieldID( 'LDC' );
        // $ldcAcct              = CRM_Core_BAO_CustomField::getCustomFieldID( 'LDC_Acct' );
        // $gasUtil               = CRM_Core_BAO_CustomField::getCustomFieldID( 'Gas_Util' );
        // $gasAcct              = CRM_Core_BAO_CustomField::getCustomFieldID( 'Gas_Acct' );
        // $tenur                  = CRM_Core_BAO_CustomField::getCustomFieldID( 'Tenure' );
        // $priHeat               = CRM_Core_BAO_CustomField::getCustomFieldID( 'Primary_Heating_Fuel' );
        // $dhw                    = CRM_Core_BAO_CustomField::getCustomFieldID( 'DHW_Fuel' );
        // $houseType          = CRM_Core_BAO_CustomField::getCustomFieldID( 'House_Type' );
        // $yearBuilt            = CRM_Core_BAO_CustomField::getCustomFieldID( 'YEAR_BUILT' );
        // $incomeBasis       = CRM_Core_BAO_CustomField::getCustomFieldID( 'Income_Basis' );
        // $verifiedBy          = CRM_Core_BAO_CustomField::getCustomFieldID( 'Verified_By' );
        // $referral              = CRM_Core_BAO_CustomField::getCustomFieldID( 'Referral' );
        // $outAgency          = CRM_Core_BAO_CustomField::getCustomFieldID( 'Outreach_Agency' );
        // $lanlordAudit       = CRM_Core_BAO_CustomField::getCustomFieldID( 'Landlord_Audit_Consent' );
        // $outPmt               = CRM_Core_BAO_CustomField::getCustomFieldID( 'Outreach_PMT' );
        // $referralCsr         = CRM_Core_BAO_CustomField::getCustomFieldID( 'Referrals_CSR' );
        // $confirmNew        = CRM_Core_BAO_CustomField::getCustomFieldID( 'CONFIRM_NEW_PART' );

        // foreach( $params as $keys => $values)  {
        //     if( strstr ( $keys,'custom_'.$filedID.'_' ) )  {
        //         if( !empty( $params[$keys] ) )  {
        //             $checkFileID = 1;
        //         }
        //         else {
        //             $checkFileID = 0;
        //         }
        //     }
            
        // }
        
        //  if (!$values['is_template']) {
        //             if ( CRM_Utils_System::isNull( $values['date_entered'] ) ) {
        //                 $errors['date_entered'] = ts( 'Start Date and Time are required fields' );
        
        
        //             } else {
        //                 $start = CRM_Utils_Date::processDate( $values['date_entered'] );
        
        //                 if ( ($end < $start)) {
        //                     $errors['date_entered'] = ts( 'End date should be after Start date' );
        //                 }
        //             }
        //         }
        if  ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ){
            if ( !empty( $params['cid'] ) )  {
                //check Landloard name is set or not
                $contactParams = array( 'contact_id' => $params['cid'],
                                        'id'         => $params['cid'] );
                require_once "CRM/Contact/BAO/Contact.php";
                $landlord = '';
                $landlordRelID = CRM_Core_DAO::getFieldValue( 'CRM_Contact_DAO_RelationshipType', 'Landlord of', 'id', 'name_a_b' );
                
                CRM_Contact_BAO_Contact::retrieve( $contactParams, $defaults, null  );
                if (is_array($defaults)) {
                    $relationship = CRM_Utils_Array::value( 'relationship', $defaults );
                                      
                    if ( is_array($relationship) ){
                        foreach($relationship['data'] as $key => $val) {
                            if($val['relationship_type_id'] == $landlordRelID){
                                !empty($val['contact_id_a'])? $landlord = $val['contact_id_a']: $landlord =  '';
                            }
                        }
                    } 
                }
            }
            
            require_once 'Efficiency/BAO/Applicant.php';
            if ($landlord){
                $field_id = Efficiency_BAO_Applicant::getFieldValue( 'landlord_custom_group', 'Social_Housing', $landlord );
            }else{
                $field_id = "";
            }
            $tenur                  = CRM_Core_BAO_CustomField::getCustomFieldID( 'Tenure' );
            foreach($params as $key => $value ){
                $name = explode("_",$key );
                if( $name[0] == 'custom' ){
                    if( $name[1] == $tenur ){
                        $Tenure =$value;
                        break;
                    }
                }
            }

            if( !empty( $errors ) ){
                $warning = "WARNING: Social Housing and Tenure entries conflict - please correct.<br><div class='icon inform-icon'></div>&nbsp;&nbsp;";
            }else{
                $warning = "WARNING: Social Housing and Tenure entries conflict - please correct.";
            }

            if ( $field_id == 'Yes' and $Tenure == 'owner_occupied' ){
                CRM_Core_Session::setStatus($warning,false);
            }
           $yearBuilt            = CRM_Core_BAO_CustomField::getCustomFieldID( 'YEAR_BUILT' );
            foreach($params as $key => $value ){
                $name = explode("_",$key );
                if( $name[0] == 'custom' ){
                    if( $name[1] == $yearBuilt ){
                        $YBuilt =$value;
                        $yearBuilt = $key;
                        break;
                    }
                }
            }

            if($YBuilt){
                if ( strlen($YBuilt) != 4 ){
                    $errors[$yearBuilt] = "Year Built should be 4 digits.";
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
        $buttonName = $this->controller->getButtonName( );
              
        $params = $this->controller->exportValues( $this->_name );
       
        $fields = array();
        require_once 'CRM/Core/BAO/CustomValueTable.php';
        if ( $buttonName == '_qf_Details_upload' || $buttonName == '_qf_Details_submit' ) {
            CRM_Core_BAO_CustomValueTable::postProcess( $params,
                                                        $fields,
                                                        'civicrm_contact',
                                                        $this->_applicantId,
                                                        'Individual' );
       

            /*** Delete & Insert records for current user in cache table - Start ***/
            
            $formvalues = 1;        
            
            // delete current user's last form preferences from cache table
            CRM_Core_BAO_Cache::deleteGroup( "gcc_refresh_customer_{$params['cid']}" );
            
            // Insert current user's last selected form preferences into cache table
            if($this->_actionString == "update") {
                CRM_Core_BAO_Cache::setItem( $formvalues, "gcc_refresh_customer_{$params['cid']}", 
                                         'Efficiency_Form_AddApplicant_Files', null );
            }
            
            /*** Delete & Insert records for current user in cache table - End ***/
            

            CRM_Core_Session::setStatus( ts(" Household Information Saved Successfully. "));
        } else {
            return;
        }
        
        if ( $this->_action & CRM_Core_Action::UPDATE && $this->_actionString == "update" ) {
            //check Landloard name is set or not
            $params = array( 'contact_id' => $this->_applicantId,
                             'id'         => $this->_applicantId );
            
            require_once "CRM/Contact/BAO/Contact.php";
            $landlord = '';
            
            CRM_Contact_BAO_Contact::retrieve( $params, $defaults );
            if (is_array($defaults)) {
                $relationship = CRM_Utils_Array::value( 'relationship', $defaults );
                
                if ( is_array($relationship) ){
                    foreach($relationship['data'] as $key => $val) {
                        if($val['relationship_type_id'] == $this->auditorRelID){
                            !empty($val['contact_id_a'])?$this->_auditor = $val['contact_id_a']: $this->_auditor =  '';
                        }
                    }
                } 
            }
            //  Temporay disabled Email notifications on HouseHold update
           
            /* require_once "Efficiency/BAO/Applicant.php";
            if ( !empty( $this->_auditor ) ){
                // send mail to new auditor
                $auditStatus = Efficiency_BAO_Applicant::sendNotificationMail( $this->_applicantId, $this->_auditor );
                } */
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
        return ts('Application');
    }
    
}