<?php
 
require_once 'Efficiency/Form/AddApplicant.php';
require_once 'Efficiency/BAO/Applicant.php';
require_once 'CRM/Core/BAO/CustomField.php';
require_once 'CRM/Core/BAO/CustomGroup.php';
require_once 'CRM/Contact/BAO/Contact.php';
require_once 'Efficiency/BAO/Export.php';
require_once "CRM/Core/DAO/File.php";
require_once 'Efficiency/Form/AddApplicant/Files.php';


/**
 * This class generates form components for relationship
 * 
 */
class Efficiency_Form_AddApplicant_Assignaudit extends Efficiency_Form_AddApplicant
{
    protected $_uid;
    
    protected $_auditorIDs  = array( );
    
    protected $_retrofitIDs = array( );
    
    /**
     * Function to set variables up before form is built
     *
     * @return void
     * @access public
     */
    public function preProcess()
    {
        parent::preProcess();
        
        $session = CRM_Core_Session::singleton();
        
        //default tab selected on cancel button
        if ( $this->_applicantId ) {
            $url = CRM_Utils_System::url('civicrm/efficiency/applicant/assignaudit/view', 
                                         'reset=1&cid=' . $this->_applicantId );
            
            $session->pushUserContext( $url );
        }
        require_once 'CRM/Contact/BAO/Relationship.php';
        $relations = CRM_Contact_BAO_Relationship::getRelationship($this->_applicantId);
        
        $confirms = array();
        foreach ($relations as $key => $val) {
            $isActiveRel = $this->isTodayBeforeEndDate($val['end_date']);
            if (($val['civicrm_relationship_type_id'] == $this->auditorRelID || $val['civicrm_relationship_type_id'] == $this->retrofitRelID) && $isActiveRel) {
                $role                   = ( $val['civicrm_relationship_type_id'] == $this->auditorRelID ) ? 'Auditor' : 'Retrofit';   
                $confirms[$role]['name'] = $val['name'];
            }
        }
        $this->assign('rows', $confirms);
        if ( CRM_Core_Permission::check( 'assign_app_audit' ) ){
            $this->assign('editAssign', 1);
        }                
        // obtain region of logged in contact
        (isset($this->_loggedInUserGroupName))?$region = $this->_loggedInUserGroupName:$region = '';
        
        if ( !$region ) {
            $region = $this->getRegion($this->_applicantId);
        }
        
        $this->assign('cid', $this->_applicantId);
        if ( $region ) {
            // obtain list of contacts who belong to a region, which logged in contact belongs to
            $contacts = $this->getRegionContacts( $region );
            if ( isset( $contacts['Auditor'] ) ) 
                $this->_auditorIDs  = $contacts['Auditor'];
            if ( isset( $contacts['Retrofit'] ) ) 
                $this->_retrofitIDs = $contacts['Retrofit'];
        }
        $qa_phone_date = Efficiency_BAO_Applicant::getFieldValue( 'gcc_applicant', 'auto_status', $this->_applicantId );
        if ( defined('CIVICRM_EFFICIENCY_ELECTRIC') && CIVICRM_EFFICIENCY_ELECTRIC ) {
            $Auditor2QA  = Efficiency_BAO_Applicant::getFieldValue( 'gcc_applicant', 'Auditor2QA', $this->_applicantId );
            $clearStatus = " (<a id='clearstatus' title='unselect'".' href="#">clear</a>) ';  
            $this->assign( 'clearStatus', $clearStatus);
            $this->assign( 'Auditor2QA', $Auditor2QA);
            if( CRM_Core_Permission::check( 'view_Q_A_Note' )  && ( $this->_action & CRM_Core_Action::VIEW ) ) {
                
                $QAPhoneCallDate = Efficiency_BAO_Applicant::getFieldValue( 'gcc_measures_other', 'qa_phone_date', $this->_applicantId );
                if( !empty( $QAPhoneCallDate) && $QAPhoneCallDate != '0000-00-00 00:00:00' ) {
                    $this->assign( 'QAPhoneCall', $QAPhoneCallDate);
                }
                
                $noteParams = array(
                                    'entity_table' => 'gcc_applicant',
                                    'entity_id'    => $this->_applicantId,
                                    'contact_id'   => $this->_applicantId,
                                    'subject'      => 'QA Notes',
                                    'version'      => 3
                                    );
                $qaNote = civicrm_api( 'note','get', $noteParams );

                if ( count( $qaNote['values'] ) > 1 ) {
                    foreach ( $qaNote['values'] as $qakey => $qaval ) {
                        $id = $qakey;
                        $session->set( 'QANid', $id );
                        $qaNote['id']   = $id;
                        $qaNote['note'] = $qaNote['values'][$id]['note'];
                    }
                } 

                if( array_key_exists('id' ,$qaNote) ) {
                    
                    $session->set( 'QANid', $qaNote['id'] );
                    $this->assign( 'QANotes', $qaNote['values'][$qaNote['id']]['note'] );
                } else {
                    $session->set( 'QANid', '' );
                }
            }
        }
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
        $defaults = array( );
        $relations = CRM_Contact_BAO_Relationship::getRelationship($this->_applicantId);
        $session = CRM_Core_Session::singleton();  
        foreach ($relations as $key => $val) {
            $isActiveRel = $this->isTodayBeforeEndDate($val['end_date']);
            if ($val['civicrm_relationship_type_id'] == $this->auditorRelID  && $isActiveRel) {
                $defaults['auditor_id']           = $val['cid'];
            }
            if ($val['civicrm_relationship_type_id'] == $this->retrofitRelID && $isActiveRel){
                $defaults['retrofit_mgr_id']       = $val['cid'];
            }
        }

        if ( $this->_isElectric == 1 ) {
            $report_to_ldc = null;
            $params = array( 
                            'name'    => 'project_details_status',
                            'version' => 3,
                             );
            $status = civicrm_api( 'option_group', 'get', $params );
            $status_values = array();
            $status_options = array();
            if ( isset( $status['id'] ) ) {
                $optionvalue_params = array( 
                                            'option_group_id' => $status['id'],
                                            'version'         => 3
                                             );
                // Retrieve Option Values for project_details_status Option Group
                $result = civicrm_api( 'option_value', 'get', $optionvalue_params );
                if ( isset( $result['values'] ) ) {
                    $status_values = $result['values'];
                }
                
                if ( $status_values ) {
                    foreach ( $status_values as $skey => $sval ) {
                        // Build Radio for each Option Value
                        if ( $sval['label'] == "Report to LDC" ) {
                            $report_to_ldc = $sval['value'];
                        }
                    }                    
                }
            }

            // This is For QAPhoneDate
            $qaPhoneDate = Efficiency_BAO_Applicant::getFieldValue( 'gcc_measures_other', 'qa_phone_date', $this->_applicantId );

            if( !empty( $qaPhoneDate ) && $qaPhoneDate != '0000-00-00 00:00:00') {
                $defaults['QA_phone_Call'] =  date("m/d/Y",strtotime( $qaPhoneDate ) );
            }
            
            // This is For QA Phone Note
            
            $noteParams = array(
                                'entity_table' => 'gcc_applicant',
                                'entity_id'    => $this->_applicantId,
                                'contact_id'   => $this->_applicantId,
                                'subject'      => 'QA Notes',
                                'version'      => 3
                                );
            $qaNote = civicrm_api( 'note','get', $noteParams );

            if ( $qaNote['values'] ) {
                if ( count( $qaNote['values'] ) > 1 ) {
                    foreach ( $qaNote['values'] as $qakey => $qaval ) {
                        $id = $qakey;
                        $session->set( 'QANid', $id );
                        $qaNote['id']   = $id;
                        $qaNote['note'] = $qaNote['values'][$id]['note'];
                    }
                }
            }
            
            if( array_key_exists('id' ,$qaNote) ) {
                $session->set( 'QANid', $qaNote['id'] );
                $defaults['QANoteValues'] =  $qaNote['values'][$qaNote['id']]['note'] ;
                
            }else{
                $session->set( 'QANid', '' );
            }
            
            $status = Efficiency_BAO_Applicant::getFieldValue( 'gcc_applicant', 'auto_status', $this->_applicantId );
            
        }
        
        if ( $report_to_ldc == $status ) 
            $defaults['assign_audit_status'] = $status;
            
        
        return $defaults;
        
    }
    
    /**
     * Function to actually build the form
     *
     * @return void
     * @access public
     */
    public function buildQuickForm( ) 
    {
        $auditorIDs = array('' => '- select -');
        if(! empty($this->_auditorIDs)) {
            $auditorIDs = $auditorIDs + $this->_auditorIDs;
        }
        
        $this->addElement('select', 'auditor_id' , ts('Auditor'), $auditorIDs);
        
        $retrofitIDs = array('' => '- select -');
        if(! empty($this->_retrofitIDs)) {
            $retrofitIDs = $retrofitIDs + $this->_retrofitIDs;
        }
        $this->addElement('select', 'retrofit_mgr_id' , ts('Retrofit Manager'), $retrofitIDs);
        
        //Electric extra field code
        if ( $this->_isElectric == 1 ) {
            $fieldID             = CRM_Core_BAO_CustomField::getCustomFieldID( 'Auditor2QA' );
            $customParams        =array(
                                        'entityID'         => $this->_applicantId,
                                        'custom_'.$fieldID => 1
                                        );
            require_once 'CRM/Core/BAO/CustomValueTable.php';
            $customValue = CRM_Core_BAO_CustomValueTable::getValues( $customParams );
            
            $audi = $this->add('text', 'Auditor2QA', ts("Verification Auditor") );
            if ( $customValue['custom_'.$fieldID] ) {
                $audi->setValue( $customValue['custom_'.$fieldID] );
                
            }
            $audi->freeze( );
            
            if( ( CRM_Core_Permission::check( 'edit_Q_A_Note' ) ) && CRM_Core_Action::UPDATE ) {
                
                $qaPhoneDate = CRM_Core_BAO_CustomField::getCustomFieldID( 'qa_phone_date' );
                CRM_Core_BAO_CustomField::addQuickFormElement( $this ,'QA_phone_Call', $qaPhoneDate, false, false );
                
                $this->add('textarea', 'QANoteValues', ts("QA Notes") , array('cols' => '60', 'rows' => '3'));
            }elseif( !CRM_Core_Permission::check( 'edit_Q_A_Note' ) && CRM_Core_Action::UPDATE ){
                $this->add('textarea', 'QANoteValues', ts("QA Notes") , array('cols' => '60', 'rows' => '3' ,'readonly' => TRUE ) );
            }
            if ( CRM_Core_Permission::check( 'access_report_to_ldc_status_block' ) ) {
                // Option Groups id for project_details_status
                $params = array( 
                                'name'    => 'project_details_status',
                                'version' => 3,
                                 );
                $status = civicrm_api( 'option_group', 'get', $params );
                $status_values = array();
                $status_options = array();
                if ( isset( $status['id'] ) ) {
                    $optionvalue_params = array( 
                                                'option_group_id' => $status['id'],
                                                'version'         => 3
                                                 );
                    // Retrieve Option Values for project_details_status Option Group
                    $result = civicrm_api( 'option_value', 'get', $optionvalue_params );
                    if ( isset( $result['values'] ) ) {
                        $status_values = $result['values'];
                    }
                              
                    if ( $status_values ) {
                        foreach ( $status_values as $skey => $sval ) {
                            // Build Radio for each Option Value
                            if ( $sval['label'] == "Report to LDC" ) {
                                $status_options[$sval['value']] = $this->createElement( 'radio', null,
                                                                                        ts('Status'), 
                                                                                        $sval['label'], 
                                                                                        $sval['value'] );
                            }
                        }                    
                    }
                }
                //Add Radios to Group
                $this->addGroup( $status_options, 'assign_audit_status', ts('Set Status'));              
            }
        }
        //End of code
        $this->add( 'hidden', 'fid',$this->_fid );
        $this->addFormRule( array('Efficiency_Form_AddApplicant_Assignaudit', 'formRule' ) ); 
        
        
        parent::buildQuickForm( ); 
    }
    
    /**
     *
     *
     */
    function formRule( $param, $files, $self ) {
        $error  = array();
        $errors = array();
        $flagPartconsent = $flagSignoff = $flagLLEXTCONSENT = $appFile = $LLauditconsentFile = $PROVIDERAPP = false;
        
        if  ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) {
            $self         = new Efficiency_Form_AddApplicant_Assignaudit();          
            $param['id']  = $param['contact_id'] = $param['cid'];
            $defaults     = array( );
            $contact      = CRM_Contact_BAO_Contact::retrieve( $param, $defaults, true );

            // Landlord information in $landlord            
            $landlord = $auditor = '';
            $landlordRelID = CRM_Core_DAO::getFieldValue( 'CRM_Contact_DAO_RelationshipType', 'Landlord of', 'id', 'name_a_b' );
            $auditorRelID  = CRM_Core_DAO::getFieldValue( 'CRM_Contact_DAO_RelationshipType', 'Auditor for', 'id', 'name_a_b' );
            if (is_array($defaults)) {
                $relationship = CRM_Utils_Array::value( 'relationship', $defaults );
                
                if ( is_array($relationship) ){
                    foreach($relationship['data'] as $key => $val) {
                        if($val['relationship_type_id'] == $landlordRelID){
                            !empty($val['contact_id_a'])? $landlord = $val['contact_id_a']: $landlord =  '';
                        }elseif ($val['relationship_type_id'] == $auditorRelID ){
                            !empty($val['contact_id_a'])? $auditor = $val['contact_id_a']: $auditor =  '';
                        }
                    }
                } 
            }
            
            // File Information in filesRows Array
            $entityFileDAO            =& new CRM_Core_DAO_EntityFile();
            $entityFileDAO->entity_id = $param['cid'];
            $entityFileDAO->find( );
            //get all file names which are uploaded
            while ( $entityFileDAO->fetch() ) {
                $fileDAO =& new CRM_Core_DAO_File();
                $fileDAO->id = $entityFileDAO->file_id;
                if ( $fileDAO->find(true) ) {
                    $fileName = Efficiency_Form_AddApplicant_Files::getFileName($fileDAO->uri);
                    
                    if ( stristr( $fileName, 'PARTCONSENT' ) ) 
                        $flagPartconsent       = true;
                    if ( stristr( $fileName, 'SIGNOFF' ) ) 
                        $flagSignoff           = true;
                    if ( stristr( $fileName, 'LLEXTCONSENT' ) )
                        $flagLLEXTCONSENT      = true;
                    if ( stristr( $fileName, 'application' ) ) 
                        $appFile               = true;
                    if ( stristr( $fileName, 'LLauditconsent' ) )
                        $LLauditconsentFile    = true;
                    if ( stristr( $fileName, 'PROVIDERAPP' ) ) 
                        $PROVIDERAPP           = true;
                }
            }            
            
            ($landlord) ? $Social_Housing = Efficiency_BAO_Applicant::getFieldValue( 'landlord_custom_group', 'Social_Housing', $landlord ) : $Social_Housing = "";
            $tenure      = Efficiency_BAO_Applicant::getFieldValue( 'gcc_applicant', 'Tenure', $param['cid'] );
            $cm50        = Efficiency_BAO_Applicant::getFieldValue( 'gcc_applicant', 'CFM50_before', $param['cid'] );
            $QAVeriAudit = Efficiency_BAO_Applicant::getFieldValue( 'gcc_measures_other', 'qaveriaudit', $param['cid'] );
            $Auditor2QA  = Efficiency_BAO_Applicant::getFieldValue( 'gcc_applicant', 'Auditor2QA', $param['cid'] );
            $h_s_cost    = Efficiency_BAO_Applicant::getFieldValue( 'gcc_measures_other', 'H_S_cost', $param['cid'] );
            $job_bcr     = Efficiency_BAO_Applicant::getFieldValue( 'gcc_measures_other', 'job_bcr', $param['cid'] );
            
            
            $hsParams    =  array('entity_table' => 'gcc_applicant',
                                  'entity_id'    => $param['cid'],
                                  'contact_id'   => $param['cid'],
                                  'subject'      => 'hsnotes',
                                  'version'      => 3 
                                  );
            
            $getNote = civicrm_api( 'note','get',$hsParams);
            
            if(array_key_exists('id',$getNote )){
                $h_s_note = $getNote['values'][$getNote['id']]['note'];
            }else{
                $h_s_note = '';
            }
            
            if ( array_key_exists( '_qf_Assignaudit_upload', $param ) ) {
                
                /*** Validation for Set Status - Start ***/
                $app_status = Efficiency_BAO_Applicant::getFieldValue( 'gcc_applicant', 'auto_status', $param['cid'] );

                if ( isset( $param['assign_audit_status'] ) && $app_status ) {
                    if ( $app_status > $param['assign_audit_status'] ) {
                        $error[] = 'Cannot set status of lower priority than current status';
                    }
                }
                /*** Validation for Set Status - End ***/

                if ($job_bcr < 1.0){
                    CRM_Core_Session::setStatus( ts('BCR is less than 1.0!'), false );
                }
                
                if ( $h_s_cost >= 1 && empty( $h_s_note ) ){
                    
                    $error[] = 'A Health and Safety Note is needed';
                }
                
                
                if ( $QAVeriAudit ){
                    
                    if ( empty( $Auditor2QA ) ){
                        $error[] = 'Verification Advisor name missing from FAST.';
                    }
                }
                
                $measures    = Efficiency_BAO_Export::_getCustomValues( $param['cid'], 'gcc_measures');
                
                foreach( $measures as $key => $value ){
                    
                    if (  stristr( $value['name'],'PROOFING' ) && stristr( $value['funder'],'LDC' )  && !$cm50){
                        $error[] = 'CFM@50 Baseline Missing from FAST';
                    }
                    
                    if ( $value['measures'] == 'XM' ){
                        
                        if ( stristr( $value['funder'],'LDC' ) ){
                            
                            $flagLDC = true;
                        }
                    }
                }
                
                if ($flagLDC){
                    if ( !$flagSignoff ) $error[] = 'Signoff file missing';
                    if ( !$flagPartconsent ) $error[] = 'Participant Retrofit Consent file missing';
                    if ( !$flagLLEXTCONSENT && $tenure == 'rental' && $Social_Housing == 'No' )
                        $error[] = 'Landlord Extended and Weatherization Consent Form missing';
                }
            }
            
            // Validations on Save & View submit button
            if ( array_key_exists( '_qf_Assignaudit_submit', $param ) ) {
                $contact = Efficiency_BAO_Export::_getCustomData( $param['cid'], 'civicrm_contact', 'id' );
                $address = Efficiency_BAO_Export::_getCustomData( $param['cid'], 'civicrm_address', 'contact_id' );
                $phone   = Efficiency_BAO_Export::_getCustomData( $param['cid'], 'civicrm_phone', 'contact_id' );
                
                if ( $contact ) {
                    if ( !($contact['first_name']) ) {
                        $error[] = ts('First Name-(Contact Info) must be completed');
                    }
                    
                    if ( !($contact['last_name']) ) {
                        $error[] = ts('Last Name-(Contact Info) must be completed');
                    }
                }        
                
                if ( $phone ) {
                    if ( !($phone['phone']) ) {
                        $error[] = ts('Permanent Telephone-(Contact Info) must be completed');
                    }
                }
                
                if ( $address ) {
                    if ( !($address['street_address']) ) {
                        $error[] = ts('House Address-(Contact Info) must be completed');
                    }
                    
                    // if ( !($address['supplemental_address_1']) ) {
                    //     $error[] = ts('Mailing Address-(Contact Info) must be completed');
                    // }
                    
                    if ( !($address['city']) ) {
                        $error[] = ts('Municipality-(Contact Info) must be completed');
                    }
                    
                    if ( !($address['state_province_id']) ) {
                        $error[] = ts('State Province-(Contact Info) must be completed');
                    }
                    
                    if ( !($address['postal_code']) ) {
                        $error[] = ts('Postal Code-(Contact Info) must be completed');
                    }
                    
                    if ( !($address['country_id']) ) {
                        $error[] = ts('Country-(Contact Info) must be completed');
                    }
                } // end of if address
                
                $fileid = Efficiency_BAO_Applicant::getFieldValue( 'gcc_applicant', 'file_identifier', $param['cid'] );
                if ( !$fileid || empty( $fileid ) ) {
                    $error[] = ts( 'HAP File Identifier-(Contact Info)');
                }
                
                
                /*** Household Info Validations - Start ***/
                $customGroupName  = "GCC_Applicant";
                $groupId          = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_CustomGroup", $customGroupName, 'id', 'name' );
                $entityType       = CRM_Contact_BAO_Contact::getContactType( $param['cid'] );
                $entitySubType    = CRM_Contact_BAO_Contact::getContactSubType( $param['cid'] );
                $groupTree        = CRM_Core_BAO_CustomGroup::getTree( $entityType, $self, $param['cid'], 
                                                                       $groupId, $entitySubType );
                $viewfields       = CRM_Core_BAO_CustomGroup::buildCustomDataView( $self, $groupTree, false,
                                                                                   $groupId, "dnc_" );
                
                foreach ( $viewfields[$groupId] as $key => $val ) {
                    foreach ( $val['fields'] as $Key => $Val ) {  
                        // Check if every field under Household tab is set except 'Gas Acct' & 'Referrals To'
                        if ( $Val['field_title'] != 'Gas Acct' && $Val['field_title'] != 'Referrals To' && $Val['field_title'] !='Status' && $Val['field_title'] != 'QA status' && $Val['field_title'] != 'Landlord Audit Consent' && $Val['field_title'] != 'Auditor2QA' && $Val['field_title'] != 'Outreach PMT' && $Val['field_title'] != 'Other FUNDS' && $Val['field_title'] != 'Referrals auditor' && $Val['field_title'] != 'Referrals concatenate' && $Val['field_title'] != 'CFM50 before'  && $Val['field_title'] != 'Wx Audit' ) {
                            if ( !$Val['field_value'] ) {
                                $error[] = ts("{$Val['field_title']}-(Household Info) must be completed");
                            }
                        }
                    }
                }
                
                /*** Household Info Validations - End ***/
                
                if ( !( $appFile ) ) {
                    $error[] = "A file with string 'application' in the filename is required (Files).";
                }
                
                if ( !( $LLauditconsentFile ) && $tenure == 'rental' ) 
                    $error[] = "Landlord Basic Consent Needed";         
                
                if ( $tenure == 'rental' && $Social_Housing == 'Yes' && !( $PROVIDERAPP ) ) 
                    $error[] = "SHProvider Application must be uploaded";
                
                if ( $landlord ) {
                    $_subType              = CRM_Contact_BAO_Contact::getContactSubType( $landlord );
                    if ( in_array( 'Landlord', $_subType ) ) {
                        $_customGroupName  = "Landlord_Custom_Group";
                        $_entityType       = CRM_Contact_BAO_Contact::getContactType( $landlord );
                        $_groupId          = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_CustomGroup", 
                                                                          $_customGroupName, 'id', 'name' );
                        $_groupTree        = CRM_Core_BAO_CustomGroup::getTree( $_entityType, $self, $landlord, 
                                                                                $_groupId, $_subType );
                        $_viewfields       = CRM_Core_BAO_CustomGroup::buildCustomDataView( $self, $_groupTree, false,
                                                                                            $_groupId, "dnc_" );
                        
                        if ( $tenure == 'rental' ) {
                            if ( $_viewfields ) {
                                foreach ( $_viewfields[$_groupId] as $key => $val ) {
                                    foreach ( $val['fields'] as $Key => $Val ) {                          
                                        // Check if every field under Household tab is set except 'Gas Acct' & 'Referrals To'
                                        if ( $Val['field_title'] != '#of unitsSH' && $Val['field_title'] != 'SH Blanket Eligibility') {
                                            if ( !$Val['field_value'] ) {
                                                $error[] = ts("{$Val['field_title']}-( Landlord Info ) must be completed");
                                            }
                                        }
                                    } // end of foreach
                                } // end of foreach 
                            } // end of if $_viewfields
                            
                            $lcontact = Efficiency_BAO_Export::_getCustomData( $landlord, 'civicrm_contact', 'id' );
                            $laddress = Efficiency_BAO_Export::_getCustomData( $landlord, 'civicrm_address', 'contact_id' );
                            
                            if ( $laddress ) {
                                if ( !( $laddress['street_address'] ) ) { 
                                    $error[] = "Address ( Landlord Info ) must be completed";
                                }
                                
                                if ( !( $laddress['postal_code'] ) ) {
                                    $error[] = "Postal Code ( Landlord Info ) must be completed";
                                }
                                
                                if ( !( $laddress['state_province_id'] ) ) {
                                    $error[] = "Province ( Landlord Info ) must be completed";
                                }
                                
                                if ( !( $laddress['city'] ) ) {
                                    $error[] = "City ( Landlord Info ) must be completed";
                                }
                            }
                        } // end of if $tenure == 'rental'
                    } // end of if ( in_array( 'Landlord', $_subType ) )
                } // end of if $landlord
            } // Validations on Save & View submit button
        } // end of if electric version
        
        if ( $error ) {
            foreach ( $error as $key => $value ) {
                ( $key == 0 ) ? $errors['_qf_default'] = $value : $errors['_qf_default'] .= '<li>' . $value;
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
        $params     = $this->controller->exportValues( $this->_name );
        $buttonName = $this->controller->getButtonName( );
        $session    =& CRM_Core_Session::singleton( );
        $this->_loggedInUserID = $session->get('userID');
        $retroStatus = '';
        
        if ( $buttonName == '_qf_Assignaudit_submit_saveqa' ) {
            if ( defined('CIVICRM_EFFICIENCY_ELECTRIC') && CIVICRM_EFFICIENCY_ELECTRIC ) {
                //$QANoteId = $session->get( 'QANid' );

                $noteParams = array(
                                    'entity_table' => 'gcc_applicant',
                                    'entity_id'    => $this->_applicantId,
                                    'contact_id'   => $this->_applicantId,
                                    'subject'      => 'QA Notes',
                                    'version'      => 3
                                    );
                $noteResult = civicrm_api( 'note','get', $noteParams );

                if ( $noteResult['values'] ) {
                    if ( count( $noteResult['values'] ) > 1 ) {
                        foreach ( $noteResult['values'] as $qakey => $qaval ) {
                            $qaval['version'] = 3;
                            $delnotes = civicrm_api( 'note','delete',$qaval);
                        }
                    } else {
                        $noteParams['id']   = $noteResult['id'];
                    }
                }
                $noteParams['note'] = ( $params['QANoteValues'] ) ? $params['QANoteValues'] : 'null';
                //if( $QANoteId ) {
                //    $noteParams['id'] = $QANoteId;
                //}
                
                require_once "api/api.php";
                $qaNote = civicrm_api( 'note','create', $noteParams );
                
                if( isset( $params['QA_phone_Call'] ) ) {
                    $QADate = $params['QA_phone_Call'];
                    empty( $params['QA_phone_Call'] ) ? $QADate = "NULL" : $QADate = date("Y-m-d H:i:s", strtotime( $QADate ) ) ;
                    
                    $qaPhoneDate = Efficiency_BAO_Applicant::getFieldValue( 'gcc_measures_other', 'qa_phone_date', $this->_applicantId );
                    Efficiency_BAO_Applicant::setFieldValue( 'gcc_measures_other', 'qa_phone_date', $this->_applicantId , $QADate ); 
                }
            }
            CRM_Core_Session::setStatus( 'QA fields updated successfully', false );

        } else {
            // Set Status 
            if ( defined('CIVICRM_EFFICIENCY_ELECTRIC') && CIVICRM_EFFICIENCY_ELECTRIC ) {
                
                /*$QANoteId = $session->get( 'QANid' );
                $noteParams = array(
                                    'entity_table' => 'gcc_applicant',
                                    'entity_id'    => $this->_applicantId,
                                    'contact_id'   => $this->_applicantId,
                                    'subject'      => 'QA Notes',
                                    'note'         => ( $params['QANoteValues'] ) ? $params['QANoteValues'] : 'null',
                                    'version'      => 3
                                    );
                
                if( $QANoteId ) {
                    $noteParams['id'] = $QANoteId;
                }
                
                require_once "api/api.php";
                $qaNote = civicrm_api( 'note','create', $noteParams );
                
                if( isset( $params['QA_phone_Call'] ) ) {
                    $QADate = $params['QA_phone_Call'];
                    empty( $params['QA_phone_Call'] ) ? $QADate = "NULL" : $QADate = date("Y-m-d H:i:s", strtotime( $QADate ) ) ;
                    Efficiency_BAO_Applicant::setFieldValue( 'gcc_measures_other', 'qa_phone_date', $this->_applicantId , $QADate ); 
                }*/
                
                if ( isset( $params['cid'] ) && $buttonName == '_qf_Assignaudit_upload' ) {
                    if ( isset( $params['assign_audit_status'] ) ) {
                        // Update auto status column for current applicant in gcc applicant table
                        Efficiency_BAO_Applicant::setFieldValue( 'gcc_applicant', 'auto_status', 
                                                                 $params['cid'], $params['assign_audit_status'] );
                    } else {
                        // Update auto status column for current applicant in gcc applicant table
                        Efficiency_BAO_Applicant::setFieldValue( 'gcc_applicant', 'auto_status', 
                                                                 $params['cid'], $params['assign_audit_status'] );
                    }
                    $url = CRM_Utils_System::url('civicrm/efficiency/applicant/assignaudit/view', 
                                                 'reset=1&cid=' . $params['cid'] );
                    CRM_Utils_System::redirect( $url );
                }
            }
            
            if ( ! ( $this->_action &  CRM_Core_Action::VIEW ) ) {
                //$params = $this->controller->exportValues( $this->_name );
                
                $today     = explode('-', date('Y-m-d'));
                $yesterday = date('Y-m-d',mktime(0, 0, 0, $today[1], $today[2]-1, $today[0]));
                $doneExport = 0;
                $auditorRelation = '';
                
                require_once 'CRM/Contact/BAO/Relationship.php';
                require_once 'Efficiency/BAO/Export.php';
                
                if ($params['auditor_id']) {
                    // creating auditor household relationship
                    $relID  = $this->auditorRelID; //need to generalize
                    $ids = array('contact' => $params['cid'] );
                    
                    $relationshipParams = array();
                    $relationshipParams['relationship_type_id'] = $relID.'_b_a';
                    $relationshipParams['is_active']            = 1;
                    
                    $relationship =& new CRM_Contact_DAO_Relationship( );
                    $relationship->contact_id_b         = $params['cid'];
                    $relationship->relationship_type_id = $relID;
                    $relationship->end_date             = "NULL";
                    if ($relationship->find(true)) {
                        //time to change the end_date of this relationship, to make it a past relationship.
                        if ( $relationship->contact_id_a != $params['auditor_id'] || $relationship->contact_id_a == $params['auditor_id'] ) {
                            $relationship->end_date = CRM_Utils_Date::isoToMysql($yesterday);
                            $relationship->is_active = 1;
                            $relationship->save();
                            $auditorRelation = 
                                CRM_Contact_BAO_Relationship::add($relationshipParams, $ids, $params['auditor_id']);
                        } /*else {
                           CRM_Core_Session::setStatus( ts("Auditor already assigned"));
                           }*/
                        
                    } else {
                        //add a new relationship
                        $auditorRelation = CRM_Contact_BAO_Relationship::add($relationshipParams, $ids, $params['auditor_id']);
                    }
                    
                    
                    if ($auditorRelation && ( ($relationship->contact_id_a != $auditorRelation->contact_id_a) || $auditorRelation->contact_id_a )) {
                        
                        // if first & new relationship, copy the file in sql/FAT.xls dir to upload dir as File_ID.xls
                        if (!$relationship->contact_id_a || $auditorRelation->contact_id_a) {
                            $this->doFileCopy($params['cid'],$params['fid'] );
                            
                        }
                        
                        // Assign Audit Status Change
                        if( isset( $params['cid'] ) && $buttonName == '_qf_Assignaudit_submit' ) {
                            
                            // Get current status label
                            $statusLabel        = Efficiency_BAO_Applicant::getAppStatus( $this->_applicantId );
                            
                            // Get current status value from status label
                            $currentstatusValue = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                               $statusLabel, 'value', 'label' );
                            
                            // Get audit_assigned's value
                            $auditAssigned      = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                               'audit_assigned', 'value', 'name' );
                            
                            // Set status as Audit Assigned only if current status is less that that of audit_assigned
                            if ( $currentstatusValue < $auditAssigned) {
                                if( $auditorRelation ) {
                                    /*$auditAssigned  = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                     'audit_assigned', 'value', 'name' );*/
                                    if ( $auditAssigned ) {
                                        Efficiency_BAO_Applicant::setFieldValue( 'gcc_applicant', 'auto_status', 
                                                                                 $params['cid'] , $auditAssigned );
                                    }
                                }
                            }
                        }
                        
                        // do export only if export is not done previously
                        $config  =& CRM_Core_Config::singleton();
                        $fid     = Efficiency_BAO_Applicant::getFieldValue( 'gcc_applicant', 'file_identifier', $params['cid'] );
                        $csvpath = $config->customFileUploadDir . 'Applicant' . DIRECTORY_SEPARATOR . $params['cid'] . DIRECTORY_SEPARATOR . $fid . '.csv';
                        
                        if ( !file_exists( $csvpath ) ) {
                            Efficiency_BAO_Export::doInternalExport( $params['cid'], $params['fid'] );
                            CRM_Core_Session::setStatus( ts(" Export Done."));
                            $doneExport = 1;
                        }
                        
                        // send mail to new auditor
                        $auditStatus = Efficiency_BAO_Applicant::sendMail( $this->_loggedInUserID, $params['auditor_id'] );
                        
                        if ($auditStatus) {
                            CRM_Core_Session::setStatus( ts("Mail sent to new Auditor."));
                        } /*else {
                           CRM_Core_Session::setStatus( ts("Auditor's Email-Id not found."));
                           }*/
                    }
                }
                
                if ($params['retrofit_mgr_id']) {
                    // creating retrofit household relationship
                    $relID  = $this->retrofitRelID; //need to generalize
                    
                    $ids = array('contact' => $params['cid'] );
                    $retrofitRelation= '';
                    $relationshipParams = array();
                    $relationshipParams['relationship_type_id'] = $relID.'_b_a';
                    $relationshipParams['is_active']            = 1;
                    
                    $relationship =& new CRM_Contact_DAO_Relationship( );
                    $relationship->contact_id_b         = $params['cid'];
                    $relationship->relationship_type_id = $relID;
                    $relationship->end_date             = "NULL";
                    
                    if ($relationship->find(true)) {
                        
                        //time to change the end_date of this relationship, to make it a past relationship.
                        if ($relationship->contact_id_a != $params['retrofit_mgr_id']) {
                            $relationship->end_date = CRM_Utils_Date::isoToMysql($yesterday); //reqr corrn
                            $relationship->is_active = 1;
                            $relationship->save();
                            $retrofitRelation = 
                                CRM_Contact_BAO_Relationship::add($relationshipParams, $ids, $params['retrofit_mgr_id']);
                        }
                    } else {
                        $retrofitRelation = CRM_Contact_BAO_Relationship::add($relationshipParams, $ids, $params['retrofit_mgr_id']);
                    }
                    if ($retrofitRelation && ($relationship->contact_id_a != $retrofitRelation->contact_id_a)) {
                        
                        // send mail to new retrofit
                        
                        //  $retroStatus = Efficiency_BAO_Applicant::sendMail($this->_loggedInUserID, $params['retrofit_mgr_id'] );
                        //                     if ($retroStatus) {
                        //                         CRM_Core_Session::setStatus( ts(" Mail sent to  new Retrofit Manager."));
                        //                     } else {
                        //                         CRM_Core_Session::setStatus( ts(" Retrofit's Email-id not found."));
                        //                     }
                    }
                }
            }               
        }
        parent::endPostProcess();
    }
    
    /**
     * copies FAT.xls to upload dir when a new relationship is created.
     *
     * @return void
     * @access public
     */
    public function doFileCopy( $contactID, $filename )
    {
        $config =& CRM_Core_Config::singleton();
        $fileToCopy = $config->resourceBase . 'sql/' .  $this->_fileName .'.xls';
        
        // use uploaded FAT.xls if it exists -- see Efficiency_Form_ImportFAT
        
        if (file_exists($config->customFileUploadDir . 'Gcc' . DIRECTORY_SEPARATOR .  $this->_fileName . '.xls' )) {
            $fileToCopy = $config->customFileUploadDir . 'Gcc' . DIRECTORY_SEPARATOR . $this->_fileName . '.xls' ;
        }
        
        $directoryName = $config->customFileUploadDir . 'Applicant' . DIRECTORY_SEPARATOR . $contactID;
        
        // Retrieve File identifier of the contact
        $fid   = Efficiency_BAO_Applicant::getFieldValue( 'gcc_applicant', 'file_identifier', $this->_applicantId );
        
        // Copy .xls file only if it is not present
        if ( !file_exists( $directoryName . DIRECTORY_SEPARATOR . $fid. '.xls' ) ) {
            
            require_once "CRM/Utils/File.php";
            CRM_Utils_File::createDir( $directoryName );
            
            $whereToCopy = $directoryName . DIRECTORY_SEPARATOR . $filename . '.xls';
            
            
            $copy = copy($fileToCopy, $whereToCopy);
            
            if (!copy($fileToCopy, $whereToCopy)) {
                CRM_Core_Session::setStatus( ts(" Could not copy " . $this->_fileName . ".xls file."));
            } else {
                CRM_Core_Session::setStatus( ts( $this->_fileName . ".xls copied."));
                
                require_once 'CRM/Core/BAO/File.php';
                $fileDAO =& new CRM_Core_DAO_File();
                $fileDAO->uri               = $filename . '.xls';
                $fileDAO->mime_type         = 'application/x-xls';
                $fileDAO->upload_date       = date('Ymdhis'); 
                $fileDAO->save();
                
                require_once "CRM/Core/DAO/EntityFile.php";
                $entityFileDAO =& new CRM_Core_DAO_EntityFile();
                $entityFileDAO->entity_table = 'civicrm_contact';
                $entityFileDAO->entity_id    = $contactID;
                $entityFileDAO->file_id      = $fileDAO->id;
                $entityFileDAO->save();
            }
        }
    }
    
    /**
     * Return a descriptive name for the page, used in wizard header
     *
     * @return string
     * @access public
     */
    public function getTitle()
    {
        return ts('AssignAudit');
    }
    
    /**
     * Function to compare the current date with passed in date.
     *
     * @return string
     * @access public
     */
    static function isTodayBeforeEndDate( $end_date ) 
    {
        $now = date( 'Y-m-d' );
        $result = $end_date ? ($now <= $end_date) ? true : false : true;
        return $result;
    }
    
}

?>
