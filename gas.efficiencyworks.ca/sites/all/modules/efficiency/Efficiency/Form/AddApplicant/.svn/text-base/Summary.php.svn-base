<?php
require_once 'Efficiency/Form/AddApplicant.php';
require_once 'CRM/Utils/Request.php';
require_once 'CRM/Contact/BAO/Contact.php';
require_once 'api/api.php';
require_once 'Efficiency/Form/AddApplicant/TabHeader.php';
require_once 'CRM/Core/Permission.php';
require_once 'Efficiency/BAO/Export.php';
require_once 'api/v3/Relationship.php';
require_once 'api/v3/Phone.php';
require_once 'api/v3/Contact.php';
require_once 'api/v3/Email.php';
require_once 'api/v3/Address.php';


class Efficiency_Form_AddApplicant_Summary extends Efficiency_Form_AddApplicant
{
    
    
    protected $_group_id = null;
    
    public function preProcess( ) 
    {
        parent::preProcess( );
        $this->_fileFieldid = CRM_Core_BAO_CustomField::getCustomFieldID( 'File_Identifier' );
        
        if ( $this->_action & CRM_Core_Action::VIEW && CRM_Core_Permission::check( 'view_app_summary' ) ) {
            $session = CRM_Core_Session::singleton();
            
            if ( isset( $_GET['selectedChild'] ) ) {
                
                //default tab selected indentified by selectedChild parameter
                $url = CRM_Utils_System::url('civicrm/efficiency/applicant/summary/view', 'reset=1&cid=' . $this->_applicantId . '&selectedChild=' . "{$_GET['selectedChild']}" );
                $this->assign( 'selectedChild', $_GET['selectedChild'] );     
                unset($_GET['selectedChild']);//unset selectedChild from $_GET
                
            } else {
                
                $url = CRM_Utils_System::url('civicrm/efficiency/applicant/view', 'reset=1&cid=' . $this->_applicantId );
            }
            
            $session->pushUserContext( $url );
            
            $params      = array( );
            $defaults    = array( );
            $contactinfo = array( );
            $allTabs     = array( );
            
            $params['id'] = $params['contact_id'] = $this->_applicantId;
            $contact      = CRM_Contact_BAO_Contact::retrieve( $params, $defaults, true );
            $subType      = CRM_Contact_BAO_Contact::getContactSubType( $this->_applicantId, "," );
            
            if ( $subType == 'Applicant' ) {
                if ( isset( $this->_applicantId ) ) {
                    $contactinfo['contact_id'] = $this->_applicantId;
                }
                
                if ( isset( $contact->first_name ) ) {
                    $contactinfo['first_name'] = $contact->first_name;
                } else {
                    $contactinfo['first_name'] = '';
                }
                
                if ( isset( $contact->last_name ) ) {
                    $contactinfo['last_name'] = $contact->last_name;
                } else {
                    $contactinfo['last_name'] = '';
                }
                if ( isset( $this->_fid ) ) {
                    $contactinfo['fileid'] = $this->_fid;
                } else {
                    $contactinfo['fileid'] = '';
                }
                
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
                
                if ( isset( $contact->address[1]['supplemental_address_1'] ) ) {
                    $contactinfo['additional_add_1'] = $contact->address[1]['supplemental_address_1'];
                } else {
                    $contactinfo['additional_add_1'] = '';
                }
                if ( $this->_isElectric == 0 ) {
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
                
                if ( isset( $contact->address[1]['postal_code_suffix'] ) ) {
                    $contactinfo['add_on_code'] = $contact->address[1]['postal_code_suffix'];
                } else {
                    $contactinfo['add_on_code'] = '';
                }
                
                if ( isset( $contact->address[1]['country_id'] ) ) {
                    $contactinfo['country'] = CRM_Core_DAO::getFieldValue( 'CRM_Core_DAO_Country', $contact->address[1]['country_id'], 'name', 'id' );
                } else {
                    $contactinfo['country'] = '';
                }
                
                if ( isset( $contact->address[1]['state_province_id'] ) ) {
                    $contactinfo['state_province'] = CRM_Core_DAO::getFieldValue( 'CRM_Core_DAO_StateProvince', $contact->address[1]['state_province_id'], 'name', 'id' );
                } else {
                    $contactinfo['state_province'] = ''; 
                }
                
                if ( isset( $this->_applicantId ) ) {
                    $phone = ereg_replace("[^0-9]", "",CRM_Core_DAO::getFieldValue( 'CRM_Core_DAO_Phone', $this->_applicantId, 'phone', 'contact_id' ) );
                    if($phone != null) {
                        $usPhone = '(' . substr($phone , 0, 3 ) . ')' . substr($phone , 3, 3 ).'-' . substr($phone , 6, 9 ) ;
                        $contactinfo['permanant_telephone'] = $usPhone;
                    } else {
                        $contactinfo['permanant_telephone'] = '';
                    }
                } else {
                    $contactinfo['permanant_telephone'] = '';
                }
                
                $contactinfo['email'] = isset( $contact->email[1]['email'] ) ? $contact->email[1]['email'] : '';
                
                //assign contactinfo to this to be used in .tpl file
                $this->assign( 'contactinfo', $contactinfo );
                
            }
            
            if ( $this->_applicantId && CRM_Core_Permission::check( 'edit_app_summary' ) ) {
                
                $editurl = CRM_Utils_System::url('civicrm/efficiency/applicant/summary/update', 'reset=1&action=update&cid=' . $this->_applicantId );
                
                if( $editurl ) {
                    
                    $this->assign( 'editUrl', $editurl );
                                      
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
        
        if ( isset( $this->_applicantId ) ) {
            $params = array( 'contact_id' => $this->_applicantId,
                             'id'         => $this->_applicantId );
            $ids = array();
            $contact =& CRM_Contact_BAO_Contact::retrieve( $params, $defaults, $ids );
         
            $customParams =array(
                                 'entityID'                     => $this->_applicantId,
                                 'custom_'.$this->_fileFieldid => 1
                                 );
            require_once 'CRM/Core/BAO/CustomValueTable.php';
            $customValue = CRM_Core_BAO_CustomValueTable::getValues( $customParams );
                       
            $defaults['file_identifier'] = $customValue['custom_'.$this->_fileFieldid];
            if ($contact->address){
                $defaults['location[2][address][street_address]'] = $contact->address[1]['street_address'];
                $defaults['location[2][address][supplemental_address_1]'] = $contact->address[1]['supplemental_address_1'];
                $defaults['location[2][address][supplemental_address_2]'] = $contact->address[1]['supplemental_address_2'];
                $defaults['location[2][address][city]'] = $contact->address[1]['city'];
                $defaults['location[2][address][postal_code]'] = $contact->address[1]['postal_code'];
                $defaults['location[2][address][postal_code_suffix]'] = $contact->address[1]['postal_code_suffix'];
                $defaults['location[2][address][country_id]'] = $contact->address[1]['country_id'];
                $defaults['location[2][address][state_province_id]'] = $contact->address[1]['state_province_id'];
                $phone = ereg_replace("[^0-9]", "", $contact->phone[1]['phone']);
                if( $phone != null ) {         
                    $usPhone = '(' . substr($phone , 0, 3 ) . ')' . substr($phone , 3, 3 ).'-' . substr($phone , 6, 9 ) ;
                    $defaults['location[2][phone][1][phone]']                 = $usPhone;
                } else {
                    $defaults['location[2][phone][1][phone]'] = '';
                }
            
               
            }
            if  ( $this->_isElectric == 1 ) {
                if ( isset( $contact->email[1]['email'] ) ) {
                    $defaults['email'] = $contact->email[1]['email'];
                }
            }
        } else {

            $countryId    = CRM_Core_DAO::getFieldValue( 'CRM_Core_DAO_Country', 'Canada', 'id', 'name' );
            $stateProvId  = CRM_Core_DAO::getFieldValue( 'CRM_Core_DAO_StateProvince', 'Ontario', 'id', 'name' );
            $defaults['location[2][address][country_id]']        = $countryId;
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
        $config = CRM_Core_Config::singleton( );
        $config->includeCounty  = null;
              
        $this->add('text', 'first_name' , ts('First Name') ,array('size' => 25), 'required');
        $this->add('text', 'last_name' , ts('Last Name') , array('size' => 25), 'required');

        if ( $this->_isElectric == 1 ) {
            if ( CRM_Core_Permission::check( 'file_identifier_required')){
                $this->add('text', 'file_identifier' , ts('HAP FileID') , array('size' => 25), 'required');
                //primary address field
                self::buildAddressBlock(2 ,
                                        ts( 'Address' ),
                                        ts( 'Permanent Telephone' ),
                                        null, true, true,null);
            } else {
                $this->add('text', 'file_identifier' , ts('HAP FileID') , array('size' => 25));
                //primary address field
                self::buildAddressBlock(2 ,
                                        ts( 'Address' ),
                                        ts( 'Permanent Telephone' ),
                                        null, null, true,null);
            }
            $this->addElement('hidden', 'electric', $this->_isElectric);
            
            $this->add('text', 'email' , ts('Email') , array('size' => 25));
            $this->addRule( 'email', ts('Email is not valid.'), 'email' );
        } else {
            $this->add('text', 'file_identifier' , ts('FileID') , array('size' => 25), 'required');
            //primary address field
            self::buildAddressBlock(2 ,
                                    ts( 'Permanent Address' ),
                                    ts( 'Permanent Telephone' ),
                                    null, true, true,null);
        }
        $this->addElement('hidden', 'file_identifier_id' ,$this->_fileFieldid );
      


        
        parent::buildQuickForm( ); 
        
        $this->addFormRule( array('Efficiency_Form_AddApplicant_Summary', 'formRule' ) );
    }
    
    function buildAddressBlock( $locationId, 
                                $title,
                                $phone,
                                $alternatePhone  = null, 
                                $addressRequired = null,
                                $phoneRequired = null, 
                                $altPhoneRequired = null,
                                $locationName = null ){
        if ( ! $locationName ) {
            $locationName = "location";
        }
        
        $config = CRM_Core_Config::singleton( );
        $attributes = CRM_Core_DAO::getAttribute('CRM_Core_DAO_Address');

        $location[$locationId]['address']['street_address']         =
            $this->addElement('text', "{$locationName}[$locationId][address][street_address]", $title,
                              $attributes['street_address']);
        if( $addressRequired ){
            $this->addRule("{$locationName}[$locationId][address][street_address]" , ts("Please enter the Street Address for %1." , array( 1 => $title)),'required');
        }
        /*( $this->_isElectric == 1 ) ? $addressname = "Mailing Address" : $addressname = "Additional Address 1" ; 
        $location[$locationId]['address']['supplemental_address_1'] =
            $this->addElement('text', "{$locationName}[$locationId][address][supplemental_address_1]", ts($addressname),
            $attributes['supplemental_address_1']);*/
        if( $this->_isElectric == 0 ) { $addressname = "Additional Address 1" ; 
            $location[$locationId]['address']['supplemental_address_1'] =
                $this->addElement('text', "{$locationName}[$locationId][address][supplemental_address_1]", ts($addressname),
                                  $attributes['supplemental_address_1']); }
        ( $this->_isElectric == 1 ) ? $addressRequiredCheck = "" : $addressRequiredCheck = $addressRequired ;
        if( $addressRequiredCheck ){
            $this->addRule("{$locationName}[$locationId][address][supplemental_address_1]" , ts("Please enter the  Address1 for %1." , array( 1 => $title)),'required');
        }
        
        if ( $this->_isElectric == 0 ) {
            $location[$locationId]['address']['supplemental_address_2'] =
                $this->addElement('text', "{$locationName}[$locationId][address][supplemental_address_2]", ts('Additional Address 2'),
                                  $attributes['supplemental_address_2']);
        }
        $location[$locationId]['address']['city']                   =
            $this->addElement('text', "{$locationName}[$locationId][address][city]", ts('City'),
                              $attributes['city']);
        if( $addressRequired ){
            $this->addRule("{$locationName}[$locationId][address][city]" , ts("Please enter the City for %1." , array( 1 => $title)),'required');
        }
        
        $location[$locationId]['address']['postal_code']            =
            $this->addElement('text', "{$locationName}[$locationId][address][postal_code]", ts('Postal Code'),
                              $attributes['postal_code']);
        if( $addressRequired ){
            $this->addRule("{$locationName}[$locationId][address][postal_code]" , ts("Please enter the Postal Code for %1." , array( 1 => $title)),'required');
        }
        
        //  $location[$locationId]['address']['postal_code_suffix']            =
        $this->addElement('text', "{$locationName}[$locationId][address][postal_code_suffix]", ts('Add-on Code'),
                          array( 'size' => 4, 'maxlength' => 12 ));
//         $this->addRule( "{$locationName}[$locationId][address][postal_code_suffix]", ts('Zip-Plus not valid.'), 'positiveInteger' );

       
        $state_province_id = CRM_Core_PseudoConstant::stateProvince();
        
        foreach ( $state_province_id as $key => $value ) {
            if ( $value == 'Ontario' ) {
                $l_provinces[$key]  = $state_province_id[$key];
                
            } 
        }
        
        $location[$locationId]['address']['state_province_id']      =
            $this->addElement('select', "{$locationName}[$locationId][address][state_province_id]", ts('Province'),
                              $l_provinces);
        

        $location[$locationId]['address']['country_id']             =
             $this->addElement('select', "{$locationName}[$locationId][address][country_id]", ts('Country'),
                               array('' => ts('- select -')) + CRM_Core_PseudoConstant::country());
        if( $addressRequired ){
            $this->addRule("{$locationName}[$locationId][address][country_id]" , ts("Please select the Country for %1." , array( 1 => $title)),'required');
        }
        
        $state =  $this->_elementIndex['location[2][address][state_province_id]'];
        $this->_elements[$state]->freeze();
        $state =  $this->_elementIndex['location[2][address][country_id]'];
        $this->_elements[$state]->freeze();

         if ( $phone ) {
             $location[$locationId]['phone'][1]['phone']      = $this->addElement('text',
                                                                                  "{$locationName}[$locationId][phone][1][phone]", 
                                                                                  $phone,
                                                                                  CRM_Core_DAO::getAttribute('CRM_Core_DAO_Phone',
                                                                                                             'phone'));
             if($phoneRequired) {
                 $this->addRule("{$locationName}[$locationId][phone][1][phone]", ts('Please enter a value for %1', array(1 => $phone)), 'required');
             }
             $this->addRule("{$locationName}[$locationId][phone][1][phone]", ts('Please enter a valid number for %1', array(1 => $phone)), 'phone');
         }

         if ( $alternatePhone ) {
             $location[$locationId]['phone'][2]['phone']      = $this->addElement('text',
                                                                                  "{$locationName}[$locationId][phone][2][phone]", 
                                                                                  $alternatePhone,
                                                                                  CRM_Core_DAO::getAttribute('CRM_Core_DAO_Phone',
                                                                                                             
                                                                                                   'phone'));
             if ($alternatePhoneRequired) {
                 $this->addRule("{$locationName}[$locationId][phone][2][phone]", ts('Please enter a value for %1', array(1 => $alternatePhone)), 'required');
             }
             $this->addRule("{$locationName}[$locationId][phone][2][phone]", ts('Please enter a valid number for %1', array(1 => $alternatePhone)), 'phone');
         }
    } 
        
       

    static function formRule( $params ) {
        $errors = array( );
        if ( $params['file_identifier'] ) {
            $dupeParams = array( 
                                'version'                               => 3,
                                'custom_'.$params['file_identifier_id'] => $params['file_identifier'],
                                'is_deleted'                            => 0
                                 );
            $dupeResult = civicrm_api( 'contact','get',$dupeParams );
    
            if ( $dupeResult['id'] ) {
               if ( $params['cid'] ) {
                    if ( $params['cid'] != $dupeResult['id'] ) 
                        $errors['_qf_default'] = ts( 'File Id already exists' ); 
                } else {
                    $errors['_qf_default'] = ts( 'File Id already exists' ); 
                }
            }elseif(!array_key_exists('id',$dupeResult) && !empty($dupeResult['values']) ) {
                 $errors['_qf_default'] = ts( 'File Id already exists' );
            }       
        }
        
        // Check if Electric Version is set
        if ( isset( $params['electric'] ) ) {
            
            // Check if Electric Version is set to true 
            if ( $params['electric'] ) {
                
                /**
                 * If file_identifier is empty
                 * First Name, Last Name & Phone compulsory fields
                 */
                if ( !($params['file_identifier']) ) {
                    if ( !($params['first_name']) ) {
                        $errors['first_name'] = ts('Please enter First Name');
                    }
                    
                    if ( !($params['last_name']) ) {
                        $errors['last_name'] = ts('Please enter Last Name');
                    }
                    
                    if ( !($params['location'][2]['phone'][1]['phone']) ) {
                        $errors['location[2][phone][1][phone]'] = ts('Please enter Permanent Telephone');
                    }
                } else if ( $params['file_identifier'] ) { 
                    
                    /**
                     * If file_identifier is set
                     * All other fields required except Unit
                     */
                    $identifier = $params['file_identifier'];

                    if( strlen( $identifier ) != 11  ){
                        $errors['file_identifier'] = ts( 'HAP File Identifier has to be exactly 11 characters long' );
                    }elseif( ! preg_match( '/^[A-Z]/', substr( $identifier, 0, 3 ) )  ){
                        $errors['file_identifier'] = ts( 'HAP File identifier should be in this format AAANN-NNNNN(AAA11-11111)' );
                    }elseif( ! preg_match( '/^[0-9]/', substr( $identifier, 3, 2 ) )){
                        $errors['file_identifier'] = ts( 'HAP File identifier should be in this format AAANN-NNNNN(AAA11-11111)' );
                    }elseif( ! preg_match( '/^[0-9]/', substr( $identifier, 7, 5 ) )){
                        $errors['file_identifier'] = ts( 'HAP File identifier should be in this format AAANN-NNNNN(AAA11-11111)' );
                    }elseif( $identifier[5] != '-' ){
                        $errors['file_identifier'] = ts( 'The 6th character has to be a hyphen' );
                    }                
                    
                    if ( !($params['first_name']) ) {
                        $errors['first_name'] = ts('Please enter First Name');
                    }
                    
                    if ( !($params['last_name']) ) {
                        $errors['last_name'] = ts('Please enter Last Name');
                    }
                    
                    if ( !($params['location'][2]['phone'][1]['phone']) ) {
                        $errors['location[2][phone][1][phone]'] = ts('Please enter Permanent Telephone');
                    }
                    
                    if ( !($params['location'][2]['address']['street_address']) ) {
                        $errors['location[2][address][street_address]'] = ts('Please enter the House Address');
                    }
                  
                    if ( !($params['location'][2]['address']['city']) ) {
                        $errors['location[2][address][city]'] = ts('Please enter the Municipality');
                    }

                    if ( !($params['location'][2]['address']['postal_code']) ) {
                        $errors['location[2][address][postal_code]'] = ts('Please enter the Postal Code');
                    }

                    if ( !($params['location'][2]['address']['state_province_id']) ) {
                        $errors['location[2][address][state_province_id]'] = ts('Please select the State Province');
                    }

                    if ( !($params['location'][2]['address']['country_id']) ) {
                        $errors['location[2][address][country_id]'] = ts('Please select the Country');
                    }

                }
            }
        } else {

            if ( !($params['location'][2]['address']['city']) ) {
                $errors['location[2][address][city]'] = ts('Please enter the Municipality');
            }
        
            if ( !($params['location'][2]['address']['street_address']) ) {
                $errors['location[2][address][street_address]'] = ts('Please enter the House Address');
            }
        
            if ( !($params['location'][2]['address']['country_id']) ) {
                $errors['location[2][address][country_id]'] = ts('Please select the Country');
            }
        
            /*if ( !($params['location'][2]['address']['supplemental_address_1']) ) {
                $errors['location[2][address][supplemental_address_1]'] = ts('Please enter the Mailing Address');
                }*/
            
            if ( !($params['location'][2]['address']['state_province_id']) ) {
                $errors['location[2][address][state_province_id]'] = ts('Please select the State Province');
            }
            
            if ( !($params['location'][2]['address']['postal_code']) ) {
                $errors['location[2][address][postal_code]'] = ts('Please enter the Postal Code');
            }
            
            $identifier = $params['file_identifier'];
            if( $identifier ) {
                
                if( strlen( $identifier ) != 10  ){
                    $errors['file_identifier'] = ts( 'File Identifier has to be exactly 10 characters long' );
                    
                }elseif( ! preg_match( '/^[A-Z0-9]+$/', substr( $identifier, 0, 4 ) )  ){
                    $errors['file_identifier'] = ts( 'File identifier can consist of only Capital alphanumeric characters' );
                   
                }elseif( $identifier[4] != '-' ){
                    $errors['file_identifier'] = ts( 'The 5th character has to be a hyphen' ); 
                   
                }          
                elseif( ! preg_match( '/^[0-9]+$/', substr( $identifier, 5, 9 ) )){
                    $errors['file_identifier'] = ts( 'File identifier can consist of only numbers after hyphen' ); 

                }         
                
            }
        }

        return $errors;
    }
    
    
    public function postProcess() 
    {
        $info_saved = false;
        // check if dedupe button, if so return.
        $buttonName = $this->controller->getButtonName( );
                    
        if ( $buttonName == '_qf_Summary_upload' || $buttonName == '_qf_Summary_submit' ) {
            
            //get the submitted values in an array
            $params = $this->controller->exportValues( $this->_name );                  
                     
            $params['contact_sub_type']             = 'Applicant';
            $params['contact_type']                 = 'Individual';
            $params['custom_'.$this->_fileFieldid]  = $params['file_identifier'];
            $params['version']                      = 3;
            
            
            if ( isset( $params['cid'] ) ) {
                
                $params['contact_id'] = $params['cid'];
                
                $getphoneParams = array( 'version'            => 3,
                                         'check_permissions'  => false,
                                         'contact_id'         => $params['cid'],
                                         'location_type_id'   => 1 );
                $resultContactId = civicrm_api( 'phone','get', $getphoneParams);
            }
            $contact = civicrm_api( 'contact', 'create', $params );
            $addressMain = array( );

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
           
            $phone = ereg_replace("[^0-9]", "", $params['location'][2]['phone'][1]['phone']);
         
            if ( isset( $params['cid'] ) && isset( $phone ) ) {

                $phoneparams = array( 'version'            => 3,
                                      'contact_id'         => $params['cid'],
                                     );

                $phoneget    = civicrm_api( 'phone','get',$phoneparams );

                $phonecount  = count($phoneget['values']);

                // Check if phone entry exists
                if ( $phonecount >= 1 ) {
                    foreach ( $phoneget['values'] as $pkey => $pval ) {
                        $delparams = array();
                        $delparams = array( 
                                           'contact_id' => $pval['contact_id'],
                                           'id'         => $pval['id'],
                                           'version' => 3,
                                           );
                        
                        require_once 'api/api.php';
                        // delete phone entry
                        $delresult = civicrm_api( 'phone','delete',$delparams );
                    }
                }

                // Add a new phone entry for current contact
                $phoneParams = array( 
                                     'contact_id'       => $contact['id'] ,
                                     'location_type_id' => 1,
                                     'phone'            => $phone,
                                     'is_primary'       => 1,
                                     'version'          => 3,
                                     );
                $phone = civicrm_api( 'phone','create',$phoneParams ); 
            }            
            
            if ( $this->_isElectric == 1 && isset( $contact['id'] ) ) {   
                if ( isset( $params['cid'] ) && isset( $params['email'] ) ) {
                    
                    $getemailParams = array( 'version'            => 3,
                                             'check_permissions'  => false,
                                             'contact_id'         => $params['cid']
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
                    $emailgetResult = civicrm_api( 'email','create',$emailParams );
                }
            }
            
            // This is Group Section
            if(isset($this->_region)){
                $getGroupID = array( 
                                    'version' => 3,
                                    'title' => $this->_region,
                                     );
            
                $groupID = civicrm_api( 'group','get',$getGroupID );
                
                if(array_key_exists('id', $groupID)){
                    $Groupsparams = array( 
                                          'contact_id' => $contact['id'],
                                          'group_id'   =>$groupID['id'] ,
                                          'version'    => 3,
                                           );
                
                    $result = civicrm_api( 'group_contact','create',$Groupsparams );
                }
            }
            
            /*** Add New Participant Status in auto_status column in gcc_applicant - Start ***/
            $newParticipant = null;
            
            require_once 'CRM/Core/BAO/CustomValueTable.php';
            $newParticipant =  CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                            'new_participant', 'value', 'name' );
            if ( $newParticipant && !$this->_actionString == "update" ) {
                if ( $this->_isElectric == 1 ) {
                    if ( empty( $params['file_identifier'] ) ){
                        $newParticipant =  CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                        'applicant', 'value', 'name' );
                    }
                    $auto_status = Efficiency_BAO_Applicant::setFieldValue( 'gcc_applicant', 'auto_status',
                                                                            $contact['id'], $newParticipant );
                }else{
                    $auto_status = Efficiency_BAO_Applicant::setFieldValue( 'gcc_applicant', 'auto_status',
                                                                            $contact['id'], $newParticipant );   
                }
            }
            
            /*** Add New Participant Status in auto_status column in gcc_applicant - End ***/      
            $info_saved = true;
        }
        
        if ( $info_saved ) {
            /*** Delete & Insert records for current user in cache table - Start ***/
            
            $formvalues = 1;        
            
            // delete current user's last form preferences from cache table
            CRM_Core_BAO_Cache::deleteGroup( "gcc_refresh_customer_{$params['cid']}" );
            
            if($this->_actionString == "update") {
                // Insert current user's last selected form preferences into cache table
            CRM_Core_BAO_Cache::setItem( $formvalues, "gcc_refresh_customer_{$params['cid']}", 
                                         'Efficiency_Form_AddApplicant_Files', null );
            }
            
            /*** Delete & Insert records for current user in cache table - End ***/
            
            
            CRM_Core_Session::setStatus( ts(" Applicant Information Saved Successfully. "),false);
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
            //  Temporary Disabled Email notifications on Summary update
            
            /*  require_once "Efficiency/BAO/Applicant.php";
            if ( !empty( $this->_auditor ) ){
                $auditStatus = Efficiency_BAO_Applicant::sendNotificationMail( $this->_applicantId, $this->_auditor );
               
                } */
            
            
        }

        if ( $this->_action & CRM_Core_Action::UPDATE && $this->_actionString == "add" ) {
            $nextTab = Efficiency_Form_AddApplicant_TabHeader::getNextSubPage( $this );
            $nextTab = strtolower($nextTab);
            $qfKey     = $this->get( 'qfKey' ); 
            //CRM_Core_Session::setStatus( ts(" Applicant Information Updated Successfully. "));
            CRM_Utils_System::redirect( CRM_Utils_System::url( "civicrm/efficiency/applicant/{$nextTab}/{$this->_actionString}",
                                                               "reset=1&cid={$contact['id']}&action=update&qfKey={$qfKey}" ) );
            
        } else {
            parent::endPostProcess( );
        }        
    }    
}