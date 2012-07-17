<?php
require_once 'Efficiency/Form/AddApplicant.php';
require_once 'CRM/Contact/BAO/Contact.php';
require_once 'api/v3/Relationship.php';
require_once 'api/v3/Phone.php';
require_once 'api/v3/Contact.php';
require_once 'api/v3/Email.php';
require_once 'api/v3/Address.php';
require_once 'CRM/Core/DAO.php'; 
require_once 'CRM/Contact/BAO/Contact.php'; 
require_once 'CRM/Core/BAO/CustomGroup.php';
require_once 'CRM/Core/BAO/CustomValueTable.php';
require_once 'api/api.php';

class Efficiency_Form_AddApplicant_Landlord extends Efficiency_Form_AddApplicant
{
    
    /**
     * The contact id, used when add/edit relationship
     *
     * @var int
     */
    
    public function preProcess( ) 
    { 
        parent::preProcess( );
         
        if ( $this->_isElectric == 1 ) {
            $customGroupName  = "Landlord_Custom_Group";
            $this->_groupId   = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_CustomGroup", $customGroupName, 'id', 'name' );
           
        }
        
        if ( $this->_action & CRM_Core_Action::VIEW ) {
            $session = CRM_Core_Session::singleton();
            
            //default tab selected on cancel button
            $url = CRM_Utils_System::url('civicrm/efficiency/applicant/landlord/view', 
                                         'reset=1&cid=' . $this->_applicantId );
                     
            
            $session->pushUserContext( $url );
            
            $params      = array( );
            $defaults    = array( );
            $contactinfo = array( );
            
            if ( $this->_applicantId ) {  
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
                            if($val['relationship_type_id'] == $this->landlordRelID){
                                !empty($val['contact_id_a'])?$this->_contactId = $val['contact_id_a']: $this->_contactId =  '';
                            }
                        }
                    } 
                }
            }
            
            if ( $this->_contactId ) {
                $params['id'] = $params['contact_id'] = $this->_contactId;
                $contact = CRM_Contact_BAO_Contact::retrieve( $params, $defaults, true );
                $subType = CRM_Contact_BAO_Contact::getContactSubType( $this->_contactId, "," );
            }

            if ( $subType == 'Landlord' ) {
                
                if ( $this->_isElectric == 1) {
                    $entityType       = CRM_Contact_BAO_Contact::getContactType( $this->_contactId  );
                   
                    $this->_groupTree = & CRM_Core_BAO_CustomGroup::getTree( $entityType, $this, $this->_contactId , 
                                                                             $this->_groupId, $subType );
                                     
                    
                    $viewfields  = CRM_Core_BAO_CustomGroup::buildCustomDataView( $this, $this->_groupTree, false, null, "dnc_" );
                    $this->assign( 'viewCustomData', $viewfields );
              
                }
  
                if ( isset( $this->_contactId ) ) {
                    $contactinfo['contact_id'] = $this->_contactId;
                }
                
                $contactinfo['first_name'] = isset( $contact->first_name ) ? $contact->first_name : '';

                
                if ( isset( $contact->last_name ) ) {
                    $contactinfo['last_name'] = $contact->last_name;
                } else {
                    $contactinfo['last_name'] = '';
                }
                $contactinfo['display_name'] = isset( $contact->display_name ) ? $contact->display_name : '';

                $contactinfo['email'] = isset( $contact->email[1]['email'] ) ? $contact->email[1]['email'] : '';
                
                if ( isset( $contact->contact_sub_type ) ) {
                    $contactinfo['contact_sub_type'] = $contact->contact_sub_type;
                } else {
                    $contactinfo['contact_sub_type'] = '';
                }
                
                if ( isset( $contact->address[1]['street_address'] ) ) {
                    $contactinfo['permanant_address'] = $contact->address[1]['street_address'];
                } else {
                    $contactinfo['permanant_address'] = '';
                }
                
                if ( $this->_isElectric == 0 ) {
                    if ( isset( $contact->address[1]['supplemental_address_1'] ) ) {
                        $contactinfo['additional_add_1'] = $contact->address[1]['supplemental_address_1'];
                    } else {
                        $contactinfo['additional_add_1'] = '';
                    }
                    
                    if ( isset( $contact->address[1]['supplemental_address_2'] ) ) {
                        $contactinfo['additional_add_2'] = $contact->address[1]['supplemental_address_2'];
                    } else {
                        $contactinfo['additional_add_2'] = '';
                    }
                    
                }
                if ( isset( $contact->address[1]['city'] ) ) {
                    $contactinfo['city'] = $contact->address[1]['city'];            
                } else {
                    $contactinfo['city'] = '';
                }
                
                if ( isset( $contact->address[1]['postal_code'] ) ) {
                    $contactinfo['zip_postal_code'] = $contact->address[1]['postal_code'];
                } else {
                    $contactinfo['zip_postal_code'] = '';
                }
                
                if ( $this->_isElectric != 1 ) {
                    if ( isset( $contact->address[1]['country_id'] ) ) {
                        $contactinfo['country'] = CRM_Core_DAO::getFieldValue( 'CRM_Core_DAO_Country', $contact->address[1]['country_id'], 'name', 'id' );
                    } else {
                        $contactinfo['country'] = '';
                    }
                }
                
               
                
                if ( isset( $contact->address[1]['state_province_id'] ) ) {
                    $contactinfo['state_province'] = CRM_Core_DAO::getFieldValue( 'CRM_Core_DAO_StateProvince', $contact->address[1]['state_province_id'], 'name', 'id' );
                } else {
                    $contactinfo['state_province'] = ''; 
                }
                
                if ( isset( $this->_contactId ) ) {

                    $phone = ereg_replace("[^0-9]", "",CRM_Core_DAO::getFieldValue( 'CRM_Core_DAO_Phone', $this->_contactId, 'phone', 'contact_id' ) );
                    if ( $phone )
                        $usPhone = '(' . substr($phone , 0, 3 ) . ')' . substr($phone , 3, 3 ).'-' . substr($phone , 6, 9 ) ;
                    $contactinfo['permanant_telephone'] = $usPhone;
                } else {
                    $contactinfo['permanant_telephone'] = '';
                }
                
                //assign contactinfo to this to be used in .tpl file
                $this->assign( 'contactinfo', $contactinfo );
                $this->assign( 'selectedChild', 'landlord' );
                
            }
            
            if ( CRM_Core_Permission::check( 'edit_app_landlord' ) ) {
                
                $editurl = CRM_Utils_System::url('civicrm/efficiency/applicant/landlord/update', 'reset=1&action=update&cid=' . $this->_applicantId );
                    
                $this->assign( 'editUrl', $editurl );
             
            }
          
        } else if ( $this->_action & CRM_Core_Action::UPDATE ) {
            $session    = & CRM_Core_Session::singleton( );
            $this->_uid = $session->get('userID');
            if(isset($this->_applicantId))  {    
                
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
                            if($val['relationship_type_id'] == $this->landlordRelID){
                                !empty($val['contact_id_a'])?$this->_contactID = $val['contact_id_a']: $this->_contactID =  '';
                            }
                        }
                    } 
                }
                $session = CRM_Core_Session::singleton();
                $session->set( 'landlordID', $this->_contactID );
            }
            
        }
        if  ( $this->_isElectric == 1 ) {
            if ( isset( $this->_contactID ) ) {
                $entityType       = CRM_Contact_BAO_Contact::getContactType( $this->_contactID );
                $entitySubType    = CRM_Contact_BAO_Contact::getContactSubType( $this->_contactID );
                $this->_groupTree = & CRM_Core_BAO_CustomGroup::getTree( $entityType, $this, $this->_contactID, 
                                                                         $this->_groupId, $entitySubType );
            } else {
                $this->_groupTree = & CRM_Core_BAO_CustomGroup::getTree( 'Individual',
                                                                         $this,
                                                                         null,
                                                                         $this->_groupId,
                                                                         'Landlord', null );
                
            }
            $this->_groupTree = CRM_Core_BAO_CustomGroup::formatGroupTree( $this->_groupTree, 1, $this );
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
          
        if ( isset($this->_contactID ) ) {
            $params = array( 'contact_id' => $this->_contactID,
                             'id'         => $this->_contactID );
            $ids = array();
            $contact =& CRM_Contact_BAO_Contact::retrieve( $params, $defaults, $ids );
          
         
                                  
            $defaults['location[2][address][street_address]']         = $contact->address[1]['street_address'];
            if  ( $this->_isElectric == 0 ) {
                $defaults['location[2][address][supplemental_address_1]'] = $contact->address[1]['supplemental_address_1'];
                $defaults['location[2][address][supplemental_address_2]'] = $contact->address[1]['supplemental_address_2'];
                $defaults['email'] = $contact->email[1]['email']; // for gas landlord
            }
            $defaults['location[2][address][city]']                   = $contact->address[1]['city'];
            $defaults['location[2][address][postal_code]']            = $contact->address[1]['postal_code'];
            $defaults['location[2][address][state_province_id]']      = $contact->address[1]['state_province_id'];

            if ( isset( $contact->phone[1]['phone'] ) ) {
                $phone = ereg_replace("[^0-9]", "", $contact->phone[1]['phone']);
                $usPhone = '(' . substr($phone , 0, 3 ) . ')' . substr($phone , 3, 3 ).'-' . substr($phone , 6, 9 ) ;
                $defaults['location[2][phone][1][phone]']                 = $usPhone;
            }
          

            
            if  ( $this->_isElectric == 1 ) {
                if ( isset( $contact->email[1]['email'] ) ) {
                    $defaults['email'] = $contact->email[1]['email'];
                }
                CRM_Core_BAO_CustomGroup::setDefaults( $this->_groupTree, $defaults );
            } else {
                
                $defaults['location[2][address][country_id]']             = $contact->address[1]['country_id'];

            }
            
        } else {
            $countryId    = CRM_Core_DAO::getFieldValue( 'CRM_Core_DAO_Country', 'Canada', 'id', 'name' );
            $stateProvId  = CRM_Core_DAO::getFieldValue( 'CRM_Core_DAO_StateProvince', 'Ontario', 'id', 'name' );
            if  ( $this->_isElectric != 1 ) {
                $defaults['location[2][address][country_id]']        = $countryId;
            }
            $defaults['location[2][address][state_province_id]'] = $stateProvId;

        }
        
        
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
 
        if  ( $this->_isElectric == 1 ) {
            CRM_Core_BAO_CustomGroup::buildQuickForm( $this, $this->_groupTree );
            //  $this->add('text', 'first_name' , ts('First Name') , array('size' => 25));
            //             $this->add('text', 'last_name' , ts('Last Name') , array('size' => 25));
            $this->add('text', 'first_name' , ts('Contact Name') , array('size' => 25));
            $this->addElement('text','email', ts('Email'), array('size' => 25));
            $this->addRule( "email", ts('Email is not valid.'), 'email' );
            $this->addElement('hidden', 'electric', $this->_isElectric);
         
           
        } else {
            $this->add('text', 'display_name' , ts('Landlord Name') , array('size' => 25));
           
        }
      
        parent::buildQuickForm( );
        self::buildAddressBlock( 2, 
                                 ts( 'Address' ),
                                 ts( 'Telephone' ),
                                 '');
       

        $this->addFormRule( array('Efficiency_Form_AddApplicant_Landlord', 'formRule' ) );
        
    }


    function buildAddressBlock( $locationId, $title, $phone,
                                $alternatePhone  = null, $addressRequired = null,
                                $phoneRequired = null, $altPhoneRequired = null,
                                $locationName = null ) {
        if ( ! $locationName ) {
            $locationName = "location";
        }
        require_once 'CRM/Core/Config.php';
        $config = CRM_Core_Config::singleton( );
        $attributes = CRM_Core_DAO::getAttribute('CRM_Core_DAO_Address');
        
        $location[$locationId]['address']['street_address']         =
            $this->addElement('text', "{$locationName}[$locationId][address][street_address]", $title,
                              $attributes['street_address']);


        if ( $this->_isElectric == 0 ) {
            $location[$locationId]['address']['supplemental_address_1'] =
                $this->addElement('text', "{$locationName}[$locationId][address][supplemental_address_1]", ts('Additional Address 1'),
                                  $attributes['supplemental_address_1']);
            $location[$locationId]['address']['supplemental_address_2'] =
                $this->addElement('text', "{$locationName}[$locationId][address][supplemental_address_2]", ts('Additional Address 2'),
                                  $attributes['supplemental_address_2']);
          }
            $location[$locationId]['address']['city']                   =
                $this->addElement('text', "{$locationName}[$locationId][address][city]", ts('City'),
                                  $attributes['city']);
       
        
        $location[$locationId]['address']['postal_code']            =
            $this->addElement('text', "{$locationName}[$locationId][address][postal_code]", ts('Postal Code'),
                              $attributes['postal_code']);
       
        $state_province_id = CRM_Core_PseudoConstant::stateProvince();
      
        foreach ( $state_province_id as $key => $value ) {
            if ( $value == 'Ontario' ) {
                $l_provinces[$key]  = $state_province_id[$key];
                          
            } 
        }
       
        $location[$locationId]['address']['state_province_id']      =
            $this->addElement('select', "{$locationName}[$locationId][address][state_province_id]", ts('Province'), $l_provinces);
        $state =  $this->_elementIndex['location[2][address][state_province_id]'];
        $this->_elements[$state]->freeze();
        
        if ( $this->_isElectric == 0 ) {
            $location[$locationId]['address']['country_id']             =
                $this->addElement('select', "{$locationName}[$locationId][address][country_id]", ts('Country'),
                                  array('' => ts('- select -')) + CRM_Core_PseudoConstant::country());
            $state =  $this->_elementIndex['location[2][address][country_id]'];
            $this->_elements[$state]->freeze();
            
        }

       
        if ( $phone ) {
            $location[$locationId]['phone'][1]['phone']      = $this->addElement('text',
                                                                                 "{$locationName}[$locationId][phone][1][phone]", 
                                                                                 $phone,
                                                                                 CRM_Core_DAO::getAttribute('CRM_Core_DAO_Phone',
                                                                                                            'phone'));
           
            $this->addRule("{$locationName}[$locationId][phone][1][phone]", ts('Please enter a valid number for %1', array(1 => $phone)), 'phone');
        }
        
        if ( $alternatePhone ) {
            $location[$locationId]['phone'][2]['phone']      = $this->addElement('text',
                                                                                 "{$locationName}[$locationId][phone][2][phone]", 
                                                                                 $alternatePhone,
                                                                                 CRM_Core_DAO::getAttribute('CRM_Core_DAO_Phone',
                                                                                                            
                                                                                                            'phone'));
            
            $this->addRule("{$locationName}[$locationId][phone][2][phone]", ts('Please enter a valid number for %1', array(1 => $alternatePhone)), 'phone');
        }
        if ( $this->_isElectric == 0 ) {
            $this->addElement('text','email', ts('Landlord Email'), array('size' => 25)); // added Landlord Email
            $this->addRule( "email", ts('Email is not valid.'), 'email' ); // added Landlord Email
        }
    }

    static function formRule( $params ) {
        $errors = array( );
        if  ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ){
            $field_id = Efficiency_BAO_Applicant::getFieldValue( 'gcc_applicant', 'Tenure', $params['cid'] );
            $socialHousing     = CRM_Core_BAO_CustomField::getCustomFieldID( 'Social_Housing' );
        
            foreach($params as $key => $value ){
                $name = explode("_",$key );
                if( $name[0] == 'custom' ){
                    if( $name[1] == $socialHousing ){
                        $social_housing =$value;
                        break;
                    }
                }
            }
            if ( $field_id == 'owner_occupied' and $social_housing == 'Yes' ){
                $warning = ts("WARNING: Social Housing and Tenure entries conflict - please correct.<br><div class='icon inform-icon'></div>&nbsp;&nbsp;");
                CRM_Core_Session::setStatus($warning,false);
            }
        }
        
        if ($errors) {
            return $errors;
        } else {
            return true;
        }
    }
    
    
    public function postProcess() 
    {
        $session = CRM_Core_Session::singleton();
        
        $landlord = $session->get('landlordID');
        $phoneParams = array();
        
        // check if dedupe button, if so return.
        $buttonName = $this->controller->getButtonName( );
        
        //Context passed through the url
        
        $this->_contactID = $session->get('cid');
        
        if ( $buttonName == '_qf_Landlord_upload' || $buttonName == '_qf_Landlord_submit' ) {
            //get the submitted values in an array
            $params                     = $this->controller->exportValues( $this->_name );
            $params['first_name'] = $params['display_name'];
         
            $params['contact_sub_type'] = 'Landlord';
            $params['contact_type']     = 'Individual';
            $params['version']          = 3;
                
            if(isset($landlord)){
                $params['contact_id'] = $landlord;
                    
                $getphoneParams = array( 'version'            => 3,
                                         'check_permissions'  => false,
                                         'contact_id'         => $params['contact_id'],
                                         'location_type_id'   => 1 );
                $resultContactId = civicrm_api( 'phone','get', $getphoneParams);
            }
                
            $contact  = civicrm_api( 'contact', 'create', $params );
            /****Changed to include email field for Landlord****/
            if ( $this->_isElectric == 0 ) {
                if ( isset( $params['contact_id'] ) && isset( $params['email'] ) ) {
                    
                    $getemailParams = array( 'version'            => 3,
                                             'check_permissions'  => false,
                                             'contact_id'         => $params['contact_id']
                                             );
                    $resultEmailId = civicrm_api( 'email','get', $getemailParams);
                  
                    // Create a new email entry
                    $emailParams = array(
                                         'id' => $resultEmailId['id'],
                                         'contact_id'       => $contact['id'],
                                         'location_type_id' => 1,
                                         'email'            => ( $params['email'] ) ? $params['email'] : 'null',
                                         'is_primary'       => 1,
                                         'version'          => 3,
                                          );
                    
                    require_once 'api/api.php';
                    $emailgetResult = civicrm_api( 'email','create',$emailParams );
                }
            }
            /****Changed to include email field for Landlord END****/
            
            if ( $this->_isElectric == 1 ) {   
                // $emailParams = array( 
//                                      'contact_id'       => $contact['id'],
//                                      'location_type_id' => 6,
//                                      'email'            => ( $params['email'] ) ? $params['email'] : 'null',
//                                      'is_primary'       => 1,
//                                      'version'          => 3,
//                                       );
                    
//                 require_once 'api/api.php';
//                 $emailResult = civicrm_api( 'email','create',$emailParams );
                
                // Update email field
                if ( isset( $params['contact_id'] ) && isset( $params['email'] ) ) { 
                    
                    $getemailParams = array( 'version'            => 3,
                                             'check_permissions'  => false,
                                             'contact_id'         => $params['contact_id']
                                             );
                    $resultEmailId = civicrm_api( 'email','get', $getemailParams);
                    
                    $emailcount = count($resultEmailId['values']);
                    
                    // Check if email entry exists
                    if ( $emailcount >= 1 ) {
                        foreach ( $resultEmailId['values'] as $ekey => $eval ) {
                            $delparams = array();
                            $delparams = array( 
                                               'contact_id' => $eval['contact_id'],
                                               'id'         => $eval['id'],
                                               'version' => 3,
                                               );
                            
                            require_once 'api/api.php';
                            // Delete email entry
                            $delresult = civicrm_api( 'email','delete',$delparams );
                        }	
                    }
                    
                    // Create a new email entry
                    $emailParams = array( 
                                         'contact_id'       => $contact['id'],
                                         'location_type_id' => 1,
                                         'email'            => ( $params['email'] ) ? $params['email'] : 'null',
                                         'is_primary'       => 1,
                                         'version'          => 3,
                                         );
                    
                    //$emailParams['id'] = $resultEmailId['id'];
                    require_once 'api/api.php';
                    $emailgetResult = civicrm_api( 'email','create',$emailParams );
                }
                
                $fields = array();
                $a=CRM_Core_BAO_CustomValueTable::postProcess( $params,
                                                               $fields,
                                                               'civicrm_contact',
                                                               $contact['id'],
                                                               'Individual' );
            }
                
                
            $addressMain['contact_id']       = $contact['id'] ;
            $addressMain['location_type_id'] = 1;
            $addressMain['version']          = 3;
            $addressMain['is_primary']       = 1;
                
            $addressGet = civicrm_api( 'address','get', $addressMain );
            if ( $addressGet['id'] ) {
                $addressMain['id'] = $addressGet['id'];
            }
            $addressMain = array_merge( $addressMain, $params['location'][2]['address'] );
                
            $address  = civicrm_api( 'address', 'create', $addressMain );
                
            if ( $params['location'][2]['phone'][1]['phone'] ) 
                $phone = ereg_replace("[^0-9]", "", $params['location'][2]['phone'][1]['phone']);
            $phoneParams = array( 
                                 'contact_id'       => $contact['id'] ,
                                 'location_type_id' => 1,
                                 'phone'            => ( $phone ) ? $phone : 'null',
                                 'is_primary'       => 1,
                                 'version'          => 3,
                                  );
            if ( $landlord && array_key_exists('id',$resultContactId ) ){
                $phoneParams['id'] = $resultContactId['id'];
            }
                
                
            $phone = civicrm_api( 'phone','create',$phoneParams );
                
            $rel['contact_id_a']         = $contact['id'];
            $rel['contact_id_b']         = $this->_applicantId;
            $rel['relationship_type_id'] = 12;
            $rel['is_active']            = 1;
            $rel['version']              = 3;
                
            $relationship  = civicrm_api( 'relationship', 'create', $rel );

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
                
                
            CRM_Core_Session::setStatus( ts("Landlord Information Saved Successfully. "));
           
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
            //  Temporary disabled Email notifications on Landlord update
            
            /* require_once "Efficiency/BAO/Applicant.php";
            if ( !empty( $this->_auditor ) ){
                // send mail to new auditor
                $auditStatus = Efficiency_BAO_Applicant::sendNotificationMail( $this->_applicantId, $this->_auditor );
                } */
        }
        
        parent::endPostProcess( );
        
    }    
}