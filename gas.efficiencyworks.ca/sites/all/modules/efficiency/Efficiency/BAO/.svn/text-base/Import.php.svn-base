<?php
require_once 'CRM/Core/BAO/CustomField.php';
require_once 'api/api.php';
require_once 'CRM/Core/Session.php';
require_once 'CRM/Core/DAO.php';
//Start and end element numbers for the measures section of the datasheet
define('G_START', 4);
define('G_END', 25);
define('E_START', 4);
define('E_END', 26);
/**
 * This class is for importing from FAT via csv.
 *
 */
class Efficiency_BAO_Import {
    
    protected $_cid; //contactID
    protected $_data = array(); //array to hold the csv data
    protected $_contact; //hold contact object
    protected $_applicant; //hold applicant object
    
    function __construct( $contactID ) {
        $this->_cid = $contactID;
        $this->load_contact();
        // $this->load_applicant();
    }
    
    function validate($files, $FileId, $param) {
        
        if ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) {
            
            require_once "CRM/Core/DAO/File.php";
            require_once 'Efficiency/Form/AddApplicant/Files.php';
            //  Ensure upon UPLOAD FAST that a file with string "partconsent" is already present or being uploaded
            
            $flagPartconsent = false;
            // File Information in filesRows Array
            $entityFileDAO            =& new CRM_Core_DAO_EntityFile();
            $entityFileDAO->entity_id = $param;
            $entityFileDAO->find( );
            //get all file names which are uploaded
            while ( $entityFileDAO->fetch() ) {
                $fileDAO =& new CRM_Core_DAO_File();
                $fileDAO->id = $entityFileDAO->file_id;
                if ( $fileDAO->find(true) ) {
                    $fileName = Efficiency_Form_AddApplicant_Files::getFileName($fileDAO->uri);
                    if(stristr( $fileName, 'PARTCONSENT' )) {
                        $flagPartconsent       = true;
                    }
                }
            }
            if(!((stristr($files['uploadFileOther0']['name'], 'PARTCONSENT')) || (stristr($files['uploadFileOther1']['name'], 'PARTCONSENT')) || (stristr($files['uploadFileOther2']['name'], 'PARTCONSENT'))  || (stristr($files['uploadFileOther3']['name'], 'PARTCONSENT')))) {
                if(!$flagPartconsent) {
                    return array( 'uploadFileOther0' => 'Participant Retrofit Consent file missing');
                }
            }
        }
        if ($files['uploadFile']['size'] == 0) {
            return array('uploadFile' => 'CSV file required');
        }
        if ($files['uploadFileFAST']['size'] == 0) {
            return array('uploadFile' => 'FAST file required');
        }
        if (substr_compare($files['uploadFile']['name'],$FileId, 0, 9) != 0) {
            return array('uploadFile' => 'Filename error:'.$files['uploadFile']['name']);
        }
        if (substr_compare($files['uploadFileFAST']['name'],$FileId, 0, 9) != 0) {
            return array('uploadFileFAST' => 'Filename error:'.$files['uploadFileFAST']['name']);
        }
        for ( $count = 0; $count <= 4; $count++ ) {
            $fileName = $files['uploadFileOther'.$count]['name'];
            if ( isset($fileName) ) {
                if ( substr_compare($fileName,$FileId, 0, 9) != 0 ) {                                                                                               
                    return array('uploadFileOther'.$count => 'Filename error:'.$fileName);                                                                                
                } 
            }
        }
        $file = fopen($files['uploadFile']['tmp_name'], "r");
        while (($line = fgetcsv($file, 0, ",")) !== FALSE) {
            $data[] = $line;
            
        }
        
        //check the array to make sure it has proper amount of rows and columns
        if ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) {
            $M_data =  E_START+E_END;
        }else{
            $M_data =  G_START+G_END;
        }
        
        if ( count( $data ) == $M_data ) {
            for ($i = 0;$i < count($data);$i++) {
                if ($i == 2 || $i == 26) {
                    $i++;
                }
                if (count($data[$i]) < 14 || count($data[$i]) > 20) {
                    $errors['uploadFile'] = 'Invalid number of columns in row ' . $i;
                    return $errors;
                }
            }
        } else {
            $errors['uploadFile'] = 'Invalid row count';
            return $errors;
        }
        
        if ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) {
            
            $file = fopen($files['uploadFile']['tmp_name'], "r");
            while (($line = fgetcsv($file, 0, ",")) !== FALSE) {
                $_dataCheck[] = $line;
            }      
            
            // update gcc_measures
            foreach( range(E_START ,E_END  ) as $i ) {
                if ( $_dataCheck[$i][1] ) {
                    // $measuresXM      = ( $_dataCheck[$i][13] == 'B' ) ? 'PM' : 'XM';
                    // if (  $measuresXM == 'XM' ){
                    $measures[$i]['name']         = $_dataCheck[$i][1];
                    $measures[$i]['measure']      = ( $_dataCheck[$i][13] == 'B' ) ? 'PM' : 'XM';    
                    $measures[$i]['funder']       = $_dataCheck[$i][2];
                    
                    //  }
                }
            }
            $FREEZER = '';
            foreach( $measures as $key => $value){
                
                if ( stristr( $value['name'], 'REFRIGERATOR' )  && stristr( $value['funder'],'LDC' ) )
                    $REFRIGERATOR      = true;
                if ( stristr( $value['name'], 'FREEZER' )  && stristr( $value['funder'],'LDC' ) ) 
                    $FREEZER           = true;  
            }
            
            $fridge1_kwh   = (double)$_dataCheck[1][16];
            if ( $fridge1_kwh == 0 && $REFRIGERATOR ){
                $errors['uploadFile'][] = 'Fridge baseline consumption needed in FAST';
            }
            
            $freezer_kwh   = (double)$_dataCheck[1][17];
            if ( $freezer_kwh == 0  && $FREEZER ){
                $errors['uploadFile'][] = 'Freezer baseline consumption needed in FAST';
            }
            
            $education     =(String) $_dataCheck[22][19];
          
            if ( $education == '0' ){
                $errors['uploadFile'][] = 'Education Field must be completed';
            }
            
        }  
        
        
        
        /** Fix for -    Prevent uploading xls, csv files to other upload fields, with a user message to upload those
         to the designated fields. Start **
         
         $restrictFormat = array("csv","xls");
         foreach( $files as $fileKey => $fileValue) {
         
         if(! empty($fileValue['name']))
         {
         if( strstr( $fileKey, 'uploadFileOther' )) {
         $getExtention = $fileInfo['extension'];
                        
         $fileInfo = pathinfo($fileValue['name']);
         $getExtention = $fileInfo['extension'];
         if (in_array($getExtention, $restrictFormat)) {
         $errors[$fileKey] = 'Invalid Format';
         return $errors;
         }
         if (substr_compare($fileValue['name'],$FileId, 0, 9) != 0) {
         $errors[$fileKey] = 'Filename error:'.$fileInfo['filename'].".".$fileInfo['extension'];
         return $errors;
         }
         }
         }           
         }
        
         /** Fix for -    Prevent uploading xls, csv files to other upload fields, with a user message to upload those
         to the designated fields. End **/
        if ( empty( $errors['uploadFile'] ) ){
            return true;
        }else{
            foreach ( $errors['uploadFile'] as $key => $value ) {
                ( $key == 0 ) ? $error['_qf_default'] = $value : $error['_qf_default'] .= '<li>' . $value;
                
            }
            return $error;
        }
    }
    
    /**
     * Function to get the list the export fields
     *
     * @param int $exportContact type of export
     *
     * @access public
     */
    function import( $filename, $cid ) {
        $file = fopen($filename['name'], "r");
        
        while (($line = fgetcsv($file, 0, ",")) !== FALSE) {
            $this->_data[] = $line;            
        }
        
        $fieldID     = CRM_Core_BAO_CustomField::getCustomFieldID( 'File_Identifier' );
            
        // if( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ){
        //     $file_identifire_CSV = $this->_data[1][14];
        //     $file_identifier =  $this->_data[1][14];
        // }else{
        
        $customParams =array(
                             'entityID'         => $cid,
                             'custom_'.$fieldID => 1
                             );
        require_once 'CRM/Core/BAO/CustomValueTable.php';
        $customValue = CRM_Core_BAO_CustomValueTable::getValues( $customParams );
        
            $file_identifier = $customValue['custom_'.$fieldID];
            if ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) {
                $file_identifire_CSV = $this->_data[1][14];
            } else{ 
                $file_identifire_CSV = $this->_data[1][12];
            }
            // } 
            if ( $file_identifier == $file_identifire_CSV ) {
                if( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) { 
                    self::create_contact( $cid );
                    self::create_auditor($cid);
                    self::create_landlord( $cid );
                    self::create_household( $cid );
                    self::update_gcc_measures( $cid );
                    
                } else {
                    $this->update_gcc_applicant( $cid );
                    $this->update_gcc_measures( $cid );
                }
               


                if ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) {
                    $audit_completed_id = CRM_Core_BAO_CustomField::getCustomFieldID( 'audit_completed', 'gcc_measures_other' );
                    $audit_completed_params = array( 
                                                    'version'                               => 3,
                                                    'entity_id'                             => $cid,
                                                    "return.custom_{$audit_completed_id}" => 1
                                                     );
                
                    //Retreive custom field's value in the custom table
                    $result['audit_completed'] = @civicrm_api( 'custom_value', 'get', $audit_completed_params );
                    $audit_completed = null;
                    if ( isset( $result['audit_completed']['values'] ) ) {
                        $audit_completed       = $result['audit_completed']['values'][$audit_completed_id]['latest'];
                    }
                    
                    $flagXM = false;
                    $measures      = Efficiency_BAO_Export::_getCustomValues($this->_cid ,'gcc_measures');
                    if ( is_array( $measures ) ) {
                        foreach( $measures as $key => $val ) {
                            if ( $measures[$key]['measures'] == 'XM' ) {
                                $flagXM = true;
                            }
                        }
                    }
                    // Set Ready for QA status for applicant in auto_status column in gcc_applicant table
                    $ready_for_QA_opt_id = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                        'ready_for_QA', 'value', 'name' );
                    
                    if($audit_completed) {
                        if ( $ready_for_QA_opt_id && !$flagXM) {
                            Efficiency_BAO_Applicant::setFieldValue( 'gcc_applicant', 'auto_status', 
                                                                     $cid , $ready_for_QA_opt_id );
                        }
                        else {
                            Efficiency_BAO_Applicant::setStatus( $cid , true );
                        }
                    }
                } else {
                    // Set status for applicant in auto_status column in gcc_applicant table 
                    Efficiency_BAO_Applicant::setStatus( $cid );                
            }
                
                drupal_set_message('File was imported successfully');
            } else {
                drupal_set_message('FileID Mismatch', 'error');
                return false;
            }
    }
    
    /**
     * Function to load contact object
     *
     *
     * @return boolean
     */
    function load_contact() {
      
        require_once 'CRM/Contact/DAO/Contact.php';
        $dao = &new CRM_Contact_DAO_Contact();
        
        $dao->id = $this->_cid;
        
        if ($dao->find(true)) {
            $this->_contact = $dao;
            return true;
        }
        drupal_set_message('Loading Contact Failed', 'error');
        return false;
    }
    
    /**
     *
     *
     */
    function create_contact( $cid ) {
        
        $params = array();
        
        $fileID          =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'File_Identifier' );
        $params['contact_sub_type'] = 'Applicant';
        $params['contact_type']     = 'Individual';
        $params[$fileID]            = $this->_data[1][14];        
        $params['first_name']       = $this->_data[3][18] ;
        $params['last_name']        = $this->_data[4][18];
        $params['email']            = ( $this->_data[10][18] ) ? $this->_data[10][18] : 'null';
        $params['contact_id']       = $cid;
        $params['version']          = 3;
        
        $contact = civicrm_api( 'contact', 'create', $params );
        
        // Get email entries
        $getemailParams = array( 'version'            => 3,
                                 'check_permissions'  => false,
                                 'contact_id'         => $this->_contact->id
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
        //if ( !empty( $this->_data[10][18] ) ){
        
        $emailParams = array( 
                             'contact_id'       => $cid,
                             'location_type_id' => 1,
                             'email'            => $this->_data[10][18] ? $this->_data[10][18] : 'null',
                             'is_primary'       => 1,
                             'version'          => 3,
                             );
        
        // Create new email entry for current contact
        $result = civicrm_api( 'email','create',$emailParams );
        //}
        
        // Get phone entrie(s)
        $getphoneParams = array( 'version'            => 3,
                                 'check_permissions'  => false,
                                 'contact_id'         => $this->_contact->id,
                                 );
        $resultContactId = civicrm_api( 'phone','get', $getphoneParams);

        $phonecount = count( $resultContactId['values'] );

        // Check if phone entry exists
        if ( $phonecount >= 1 ) { 
            
            foreach ( $resultContactId['values'] as $pkey => $pval ) {
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

        // Create a new phone entry
        $phoneParams = array(
                             'contact_id'       => $this->_contact->id,
                             'location_type_id' => 1,
                             'phone'            => ( $this->_data[9][18] ) ? $this->_data[9][18] : 'null',
                             'is_primary'       => 1,
                             'version'          => 3,
                             );
        //$phoneParams[ 'id' ]  =  $resultContactId['id'];
        $phone = civicrm_api( 'phone','create',$phoneParams );
        
        $addressMain = array( );
        
        $addressMain['contact_id']       = $cid;
        $addressMain['location_type_id'] = 1;
        $addressMain['version']          = 3;
        $addressMain['is_primary']       = 1;
        
        $addressGet = civicrm_api( 'address','get', $addressMain );

        if ( $addressGet['id'] ) {
            $addressMain['id'] = $addressGet['id'];
        }
        $countryId    = CRM_Core_DAO::getFieldValue( 'CRM_Core_DAO_Country', 'Canada', 'id', 'name' );
        $stateProvId  = CRM_Core_DAO::getFieldValue( 'CRM_Core_DAO_StateProvince', 'Ontario', 'id', 'name' );
        $addressMain['street_address'] = ( $this->_data[5][18] ) ? $this->_data[5][18] : 'null' ;
        $addressMain['city'] =  ( $this->_data[7][18] ) ? $this->_data[7][18] : 'null';
        $addressMain['postal_code'] = ( $this->_data[8][18] ) ? $this->_data[8][18] : 'null' ;
        $addressMain['state_province_id'] = $stateProvId;
        $addressMain['country_id'] = $countryId;
        $addressMain['postal_code_suffix'] =  ( $this->_data[6][18] ) ? $this->_data[6][18] : 'null';
        
        $address  = civicrm_api( 'address', 'create', $addressMain );
        $this->_contact = $cid;
        //drupal_set_message('Loading Contact Failed', 'error');
        // return false;
    }
    
    /**
     * Function to load contact object
     *
     *
     * @return boolean
     */
    function create_landlord($cid) {
        
        $landlordRelID = CRM_Core_DAO::getFieldValue( 'CRM_Contact_DAO_RelationshipType', 'Landlord of', 'id', 'name_a_b' );
        //check Landloard name is set or not
        $params = array( 'contact_id' => $cid,
                         'id'         => $cid );
        require_once "CRM/Contact/BAO/Contact.php";
        $landlord = '';
       
        CRM_Contact_BAO_Contact::retrieve( $params, $defaults, $cid  );
        if (is_array($defaults)) {
            $relationship = CRM_Utils_Array::value( 'relationship', $defaults );
            if ( is_array($relationship) ){
                foreach($relationship['data'] as $key => $val) {
                    if($val['relationship_type_id'] == $landlordRelID){
                        !empty($val['contact_id_a']) ? $landlord = $val['contact_id_a']: $landlord =  '';
                     }
                }
            } 
        }
        
        // Landloard Part is here.
        
        $Social_Housing           =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'Social_Housing' );
        $of_unitsSH               =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( '#of_unitsSH' );
        $SH_blanketeligibility    =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'SH_blanketeligibility' );
        $LL_payHeat               =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'LL_payHeat' );
        $LL_payElec               =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'LL_payElec' );
        $LL_heat_metered          =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'LL_heat_metered' );
        $LL_elec_metered          =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'LL_elec_metered' );
        $Corporate_Name           =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'Corporate_Name' );
        
        //landlord_custom_group
        
        $lcg = array();
        $lcg[$Social_Housing]        = $this->_data[11][19];
        // $lcg[$of_unitsSH]            = "" ;//$this->_data[][];
        //  $lcg[$SH_blanketeligibility] = "" ;//$this->_data[][];
        //  $lcg[$LL_payHeat]            = "";
        // $lcg[$LL_payElec]            = "";
        // $lcg[$LL_heat_metered]       = "" ;//$this->_data[][];
        // $lcg[$LL_elec_metered]       = "" ;
        $lcg[$Corporate_Name]        = $this->_data[15][18] ;
        
        $landlordParams['contact_sub_type'] = 'Landlord';
        $landlordParams['contact_type']     = 'Individual';
        // $landlord_name = explode(' ',$this->_data[16][18]);
        //  $landlordParams['first_name']       = $landlord_name[0] ;
        // $landlordParams['last_name']        = $landlord_name[1];
        // $landlordParams['legal_name']       = $this->_data[16][18];
        $landlordParams['first_name']       = ( $this->_data[16][18] ) ? $this->_data[16][18] : 'null';
        $landlordParams['last_name']       = '';
        $landlordParams['email']            = ( $this->_data[21][18] ) ? $this->_data[21][18] : 'null';
        $landlordParams['version']          = 3;
        
        if( ! empty( $landlord ) ){
            
            $landlordParams['contact_id'] = $landlord;
            
            $getphoneParams = array( 'version'            => 3,
                                     'check_permissions'  => false,
                                     'contact_id'         => $landlordParams['contact_id'],
                                     'location_type_id'   => 1 );
            $resultContactId = civicrm_api( 'phone','get', $getphoneParams);
        }
        $landlord_contact  = civicrm_api( 'contact', 'create', $landlordParams );
        $emailParamsPrimary = array( 
                                    'contact_id' => $landlord,
                                    'location_type_id' => 6,
                                    'is_primary' => 1,
                                    'version' => 3,
                                    );
        $getPrimaryEmail = civicrm_api( 'email','get',$emailParamsPrimary );
        if( !empty( $getPrimaryEmail['values'] ) ) { 
            $emailParamsPrimary = array( 
                                        'contact_id' => $landlord,
                                        'location_type_id' => 6,
                                        'id' => $getPrimaryEmail['id'],
                                        'is_primary' => 1,
                                        'version' => 3,
                                        );
            civicrm_api( 'email','delete',$emailParamsPrimary );
        }
        $emailParams = array( 
                             'contact_id' => $landlord,
                             'location_type_id' => 6,
                             'email' => $landlordParams['email'] ,
                             'is_primary' => 1,
                             'version' => 3,
                             );
        $result = civicrm_api( 'email','create',$emailParams );
        $fields = array();
        $lcg['version']   = '3';
        $lcg['entity_id'] = $landlord_contact['id'];
        
        $landlordCustomValue = civicrm_api( 'custom_value', 'create', $lcg );
        
        $addressMain = array( );
        
        $addressMain['contact_id']       = $landlord_contact['id'] ;
        $addressMain['location_type_id'] = 1;
        $addressMain['version']          = 3;
        $addressMain['is_primary']       = 1;
        
        $addressGet = civicrm_api( 'address','get', $addressMain );
        
        if ( $addressGet['id'] ) {
            $addressMain['id'] = $addressGet['id'];
        }
        $countryId    = CRM_Core_DAO::getFieldValue( 'CRM_Core_DAO_Country', 'Canada', 'id', 'name' );
        $stateProvId  = CRM_Core_DAO::getFieldValue( 'CRM_Core_DAO_StateProvince', 'Ontario', 'id', 'name' );
        $addressMain['street_address'] = $this->_data[17][18] ;
        $addressMain['city'] =  $this->_data[18][18];
        $addressMain['postal_code'] = $this->_data[19][18];
        $addressMain['state_province_id'] = $stateProvId;
        $addressMain['country_id'] = $countryId;
        
        $address  = civicrm_api( 'address', 'create', $addressMain );
        
        $phoneParams = array( 
                             'contact_id'       => $landlord_contact['id'] ,
                             'location_type_id' => 1,
                             'phone'            => ( $this->_data[20][18] ) ? $this->_data[20][18] : 'null' ,
                             'is_primary'       => 1,
                             'version'          => 3,
                              );
        
        if ( isset( $this->_contact ) ) {
            $phoneParams['id'] = $resultContactId['id'];
        }
        $phone = civicrm_api( 'phone','create',$phoneParams );
        
        $rel['contact_id_a']         = $landlord_contact['id'];
        $rel['contact_id_b']         = $cid;
        $rel['relationship_type_id'] = 12;
        $rel['is_active']            = 1;
        $rel['version']              = 3;
        
        $relationship  = civicrm_api( 'relationship', 'create', $rel );
        
    }
    
    /**
     * Function to load contact object
     *
     *
     * @return boolean
     */
    function create_household($cid) {
        require_once 'CRM/Core/BAO/CustomValueTable.php';
        
        $file_identifier        =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'File_Identifier' );
        $date_entered_id        =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'Date_Application_Entered' );
        $occupants              =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'occupants' );
        $adults                 =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'adults' );
        $planguage              =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'planguage' );
        $is_pay_heat            =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'Pay_heating_bill' );
        $is_pay_elec            =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'Pay_electric_bill' );
        $ldc_id                 =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'LDC' );
        $ldc_acct               =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'LDC_Acct' );
        $gas_util_id            =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'Gas_Util' );
        $gas_acct               =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'Gas_Acct' );
        $tenure                 =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'Tenure' );
        $pheat_fuel_id          =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'Primary_Heating_Fuel' );
        $dhw_fuel_id            =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'DHW_Fuel' );
        $house_type_id          =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'House_Type' );
        $YEAR_BUILT             =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'YEAR_BUILT' );
        $income_basis_id        =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'Income_Basis' );
        $verified_by            =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'Verified_By' );
        $referral_id            =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'Referral' );
        $Outreach_Agency        =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'Outreach_Agency' );
        $landlord_audit_consent =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'Landlord_Audit_Consent' );
        $qa_status              =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'QA_status' );
        $Outreach_PMT           =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'Outreach_PMT' );
        $Auditor2QA             =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'Auditor2QA' );
        $Other_FUNDS            =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'Other_FUNDS' );
        $Referrals_CSR          =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'Referrals_CSR' );
        $Referrals_auditor      =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'Referrals_auditor' );
        $Referrals_concatenate  =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'Referrals_concatenate' );
        $CFM50_before           =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'CFM50_before' );
        $CONFIRM_NEW_PART       =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'CONFIRM_NEW_PART' );
        $CORE_SHELL_DATE        =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'CORE_SHELL_DATE' );
        $Status                 =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'Status' );

        // $customParams = array(
        //                       'entityID'      => $cid,
        //                       $date_entered   => 1
        //                       );
        
        // $customValue = CRM_Core_BAO_CustomValueTable::getValues( $customParams );
        // $date_entered = $customValue[$date_entered];
                  
        // // save entered-date from getting reset.
        // if ( $date_entered == '0000-00-00' ) {
        //     // unset to avoid confusion 
        //     $date_entered = "null";
        // } else {
        //     $date_entered  = date("Ymd", strtotime( $date_entered ));
        // }
               
        
        ( $this->_data[7][19] )  ? $gas_util_id_value      =  CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", $this->_data[7][19], 'value', 'label' )   : $gas_util_id_value  = 'null';  
        
        ( $this->_data[12][19] )  ? $pheat_fuel_id_value    =  CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", $this->_data[12][19], 'value', 'label' ) : $pheat_fuel_id_value = 'null';
        
        ( $this->_data[13][19] )  ? $dhw_fuel_id_value      =  CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", $this->_data[13][19], 'value', 'label' ) : $dhw_fuel_id_value = 'null';
        
        ( $this->_data[14][19] )  ? $house_type_id_value    =  CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", $this->_data[14][19], 'value', 'label' ) : $house_type_id_value = 'null';
        
        ( $this->_data[16][19] )  ? $income_basis_id_value  =  CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", $this->_data[16][19], 'value', 'label' ) : $income_basis_id_value =  'null';
        
        ( $this->_data[18][19] )  ? $referral_id_value      =  CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", $this->_data[18][19], 'value', 'label' ) : $referral_id_value = 'null';
        
        // ( $this->_data[5][19] )  ? $ldc_id_value           =  CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", $this->_data[5][19], 'value', 'label' )   : $ldc_id_value = 'null';
        
        $params['entity_id']            = $cid;
        $params[$file_identifier]       = $this->_data[1][14];
        // $params[$date_entered_id]       = $date_entered;
        $params[$occupants]             = (int)$this->_data[11][18];
        $params[$adults]                = (int)$this->_data[12][18];
        $params[$planguage]             = $this->_data[13][18];
        $params[$is_pay_heat]           = ( $this->_data[3][19] == 'Yes' ) ? 1 : 0;
        $params[$is_pay_elec]           = ( $this->_data[4][19] == 'Yes' ) ? 1 : 0; 
        $params[$ldc_id]                = ( $this->_data[5][19] ) ? $this->_data[5][19] : NULL;
        $params[$ldc_acct]              = $this->_data[6][19]; 
        $params[$gas_util_id]           = $gas_util_id_value;
        $params[$gas_acct]              = $this->_data[8][19];
        $params[$tenure]                = $this->_data[10][19];
        $params[$pheat_fuel_id]         = $pheat_fuel_id_value;
        $params[$dhw_fuel_id]           = $dhw_fuel_id_value;
        $params[$house_type_id]         = $house_type_id_value;
        $params[$YEAR_BUILT]            = (int)$this->_data[15][19];
        $params[$income_basis_id]       = $income_basis_id_value;
        $params[$verified_by]           = $this->_data[17][19];
        $params[$referral_id]           = $referral_id_value;
        // $params[$Outreach_Agency]       = ''; //$this->_data[][];     
        // $params[$landlord_audit_consent]= ''; //$this->_data[][];     
        // $params[$qa_status]             = ''; //$this->_data[][];
        // $params[$Outreach_PMT]          = ''; //$this->_data[][];
        $params[$Auditor2QA]            = $this->_data[27][19];
        $params[$Other_FUNDS]           = (int)$this->_data[20][19];
        // $params[$Referrals_CSR]         = ''; //$this->_data[][];
        $params[$Referrals_auditor]     = $this->_data[21][19];
        // $params[$Referrals_concatenate] = ''; //$this->_data[][];
        $params[$CFM50_before]          = (int)$this->_data[1][15];
        // $params[$CONFIRM_NEW_PART]      = ''; //$this->_data[][];
        $params[$CORE_SHELL_DATE]       = date( "Ymd", strtotime( $this->_data[25][19] ) );
        // $params[$Status]                = (int)'';
        $params['version']   = 3;
      
        $householdCustomValue = civicrm_api( 'custom_value', 'create', $params );

        /*** Update ldc_id field in gcc applicant ***/
        Efficiency_BAO_Applicant::setFieldValue( 'gcc_applicant', 'ldc_id', 
                                                 $params['entity_id'] , $this->_data[5][19] );

    }
    
   
    /**
     * Function responsible for updating gcc_applicant table data
     */
    function update_gcc_applicant( $cid ) {
    
        require_once 'CRM/Core/OptionGroup.php';
        require_once 'CRM/Core/BAO/CustomField.php';
        require_once 'CRM/Core/BAO/CustomValueTable.php';
        $customGroupName = "GCC_Applicant";

        $corrections     =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( "Auditor_s_Notes" );
        $central_air_id  =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( "Central_Air" );
        $dateEntered     =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( "Date_Application_Entered");
        
        $customParams = array(
                              'entityID'      => $cid,
                              $dateEntered    => 1,
                              $corrections    => 1,
                              $central_air_id => 1
                              );
        
        $customValue = CRM_Core_BAO_CustomValueTable::getValues( $customParams );
        $date_entered = $customValue[$dateEntered];
        $customValue[ $corrections] = $this->_data[1][1];
        
        // match csv value against OptionGroup label to get the id...
        ( $this->_data[1][2] ) ? $central_air_value           =  CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", $this->_data[1][2], 'value', 'name' ): $gas_util_id_value  = 'null';
        
        // $central_air_value           =  CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", $this->_data[1][2], 'value', 'name' );
       //  $customValue[$central_air_id ] = 5; // default to Other
//         foreach(CRM_Core_OptionGroup::values('central_air') as $id => $label) {
//             if (preg_match('/' . trim($this->_data[1][2]) . '/i', $label)) {
//                 $customValue[$central_air_id ] = $id;
//                 break;
//             }
//         }
        
        // save entered-date from getting reset.
        if ( $date_entered ) {
            if ( $date_entered == '0000-00-00' ) {
                // unset to avoid confusion 
                $date_entered = "null";
            } else {
                $date_entered  = date("Ymd", strtotime( $date_entered ));
            }
        }
        
        $Params =array(
                       'entityID'       => $cid,
                       $dateEntered     => $date_entered,
                       $corrections     => $this->_data[1][1],
                       $central_air_id  => $central_air_value 
                       );
        
        return  CRM_Core_BAO_CustomValueTable::setValues( $Params );
         
         
    }
 

    /**
     *Create Auditor Here. start
     *
     **/
    function create_auditor($cid){
        $auditor = (String) $this->_data[19][19];
        if ( !empty( $auditor ) &&  $auditor != '0' ){
            $auditor = explode(' ',$auditor);
        
            $contact = array();
            ( $auditor[0] ) ? $params['first_name'] = $auditor[0] : $params['first_name'] = 'null';
            ( $auditor[1] ) ? $params['last_name'] = $auditor[1] : $params['last_name'] = 'null';
            if ( $params['first_name'] != 'null' ){ 
                $auditorRelID  = CRM_Core_DAO::getFieldValue( 'CRM_Contact_DAO_RelationshipType', 'Auditor for', 'id', 'name_a_b' );
                $params['contact_sub_type']             = 'Auditor';
                $params['contact_type']                 = 'Individual';
                $params['version']                      = 3;
           
                $contact = civicrm_api( 'contact', 'get', $params );
            }
              
            $today     = explode('-', date('Y-m-d'));
            $yesterday = date('Y-m-d',mktime(0, 0, 0, $today[1], $today[2]-1, $today[0]));
            $doneExport = 0;
            $auditorRelation = '';
        
            require_once 'CRM/Contact/BAO/Relationship.php';
            require_once 'Efficiency/BAO/Export.php';
   
            if( array_key_exists('id',$contact ) ){
                  
                // creating auditor household relationship
                $relID  = $auditorRelID; //need to generalize
            
                $ids = array('contact' => $cid );
            
                $relationshipParams = array();
                $relationshipParams['relationship_type_id'] = $relID.'_b_a';
                $relationshipParams['is_active']            = 1;
            
                $relationship =& new CRM_Contact_DAO_Relationship( );
                $relationship->contact_id_b         = $cid;
                $relationship->relationship_type_id = $relID;
                $relationship->end_date             = "NULL";
            
                if ($relationship->find(true)) {
                    //time to change the end_date of this relationship, to make it a past relationship.
                    if ($relationship->contact_id_a != $contact['id']) {
                        $relationship->end_date = CRM_Utils_Date::isoToMysql($yesterday);
                        $relationship->is_active = 1;
                        $relationship->save();
                        $auditorRelation = 
                            CRM_Contact_BAO_Relationship::add($relationshipParams, $ids, $contact['id']);
                    }
                
                } else {
                    //add a new relationship
                    $auditorRelation = CRM_Contact_BAO_Relationship::add($relationshipParams, $ids, $contact['id']);
                }
            
                $fid  = $this->_data[1][14];
       
                if ($auditorRelation && ($relationship->contact_id_a != $auditorRelation->contact_id_a)) {
                
                    // if first & new relationship, copy the file in sql/FAT.xls dir to upload dir as File_ID.xls
                    if (!$relationship->contact_id_a) {
                        require_once 'Efficiency/Form/AddApplicant/Assignaudit.php';
                        Efficiency_Form_AddApplicant_Assignaudit::doFileCopy($cid,$fid );
                    
                    }
                
                    // do export
                    Efficiency_BAO_Export::doInternalExport( $cid, $fid );
                    CRM_Core_Session::setStatus( ts(" Export Done."));
                    $doneExport = 1;
                
                    $session =& CRM_Core_Session::singleton( );
                    $this->_loggedInUserID = $session->get('userID');
                    // send mail to new auditor
                    $auditStatus = Efficiency_BAO_Applicant::sendMail( $this->_loggedInUserID, $contact['id'] );
                    if ($auditStatus) {
                        CRM_Core_Session::setStatus( ts("Mail sent to new Auditor."));
                    } else {
                        CRM_Core_Session::setStatus( ts("Auditor's Email-Id not found."));
                    }
                }
            }
        }
    }
    
    
    /**
     * Function to update the gcc_measures & gcc_measures_other tables
     *  in the data base with the info imported from the csv file
     */
    function update_gcc_measures( $cid ) {
        
        require_once 'Efficiency/BAO/Applicant.php';
        require_once 'Efficiency/BAO/Export.php';
        require_once 'CRM/Core/OptionGroup.php';
        require_once 'CRM/Core/BAO/CustomField.php';
        require_once 'CRM/Core/BAO/CustomValueTable.php';
               
        $retroGroupID  = $grpID = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_CustomGroup",'gcc_retrofit' , 'id', 'name' );
        $name         = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'name' );
     
        $params =array(
                       'entityID' => $cid,
                       $name => 1,
                       );
        $presult  = CRM_Core_BAO_CustomValueTable::getValues( $params ); // retrieve custom values from gcc_measures
        unset($presult['is_error']);
        unset($presult['entityID']);
        foreach ( $presult as $key => $value ) {
            $split = explode('_', $key);
            $keys[end($split)] = $value; //get the ids before update
        }

        $groupID  = $grpID = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_CustomGroup",'gcc_measures' , 'id', 'name' );
        self::deleteCustomValue($cid,$groupID );
        
        $name         = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'name' );
        $measure      = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'measures' );
        $funder       = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'funder' );
        $installed    = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'installed' );
        $costs        = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'costs' );
        $kwh          = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'kwh' );
        $kw_s         = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'kw_s' );
        $kw_w         = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'kw_w' );
        $m3saved      = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'm3saved' );
        $l_oil        = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'l_oil' );
        $l_propane    = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'l_propane' );
        $npv          = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'npv' );
        $life_profile = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'life_profile' );
        $measures = array();

        if ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) {
            $M_data_start = E_START;
            $M_data_end   = E_END;
        } else {
            $M_data_start = G_START;
            $M_data_end   = G_END;
        }


        // update gcc_measures
        foreach( range( $M_data_start, $M_data_end ) as $i ) {
            
            if ( $this->_data[$i][1] ) {
                
                $measures['entity_id']   = $cid;
                $measures[$name]         = $this->_data[$i][1];
                $measures[$measure]      = ($this->_data[$i][13] == 'B') ? 'PM' : 'XM';        
                $measures[$funder]       = $this->_data[$i][2];
                $measures[$installed]    = (int)$this->_data[$i][3];
                $measures[$costs]        = (double)$this->_data[$i][4];
                $measures[$kwh]          = (double)$this->_data[$i][5];
                $measures[$kw_s]         = (double)$this->_data[$i][6];
                $measures[$kw_w]         = (double)$this->_data[$i][7];
                $measures[$m3saved]      = (double)$this->_data[$i][8];
                $measures[$l_oil]        = (double)$this->_data[$i][9];
                $measures[$l_propane]    = (double)$this->_data[$i][10];
                $measures[$npv]          = (double)$this->_data[$i][11];
                $measures[$life_profile] = $this->_data[$i][12];
                             
                CRM_Core_BAO_CustomValueTable::postProcess( $measures,
                                                            $fields,
                                                            'gcc_measures',
                                                            $cid,
                                                            'Individual' );
             
            }
        }

        $Params =array(
                       'entityID' => $cid,
                       $name => 1,
                       );
        $fresult  = CRM_Core_BAO_CustomValueTable::getValues( $Params ); // retrieve custom values of gcc_measures
        unset($fresult['is_error']);
        unset($fresult['entityID']);
        foreach ( $fresult as $fkey => $fvalue ) {
            $fsplit = explode('_', $fkey);
            $finalKeys[end($fsplit)] = $fvalue; 
        }
        $intercept = array();
        foreach ( $keys as $index => $field ) {
            foreach($finalKeys as $findex => $ffield) {
                if($field == $ffield) {
                    $intercept[$index] = $findex; // form associative array of old and new keys
                }
            }
        }
        foreach ( $intercept as $k => $v ) {
            $sql = "UPDATE gcc_retrofit SET measures_id = %1 WHERE measures_id = %2 AND entity_id = %3";
            $params = array(1 => array($v, 'Integer'), 2 => array($k, 'Integer'), 3 => array($cid, 'Integer'));
            CRM_Core_DAO::executeQuery($sql, $params); // updating retrofit dates on import of CSV
        }
        /*
         *updateing gcc_measures_other
         */
          $mo = array();

        $audit_completed       = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'audit_completed' );
        $retrofit_completed    = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'retrofit_completed' );
        $audit_type_id         = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'audit_type_id' );
        $wac                   = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'wac' );
        $computers             = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'computers' );
        $fridges               = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'fridges' );
        $frzrs                 = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'frzrs' );
        $shhd_flow             = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'shhd_flow' );
        $shhd_flow_after       = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'shhd_flow_after' );
        $software              = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'software' );
        $bm_costs              = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'bm_costs' );
        $bm_kwh                = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'bm_kwh' );
        $bm_kw_s               = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'bm_kw_s' );
        $bm_kw_w               = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'bm_kw_w' );
        $bm_trc                = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'bm_trc' );
        $xm_costs              = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'xm_costs' );
        $xm_kwh                = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'xm_kwh' );
        $xm_kw_s               = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'xm_kw_s' );
        $xm_kw_w               = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'xm_kw_w' );
        $xm_trc                = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'xm_trc' );
        $potential_costs       = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'potential_costs' );
        $potential_kwh         = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'potential_kwh' );
        $potential_kw_s        = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'potential_kw_s' );
        $potential_kw_w        = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'potential_kw_w' );
        $potential_m3saved     = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'potential_m3saved' );
        $potential_l_oil       = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'potential_l_oil' );
        $potential_l_propane   = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'potential_l_propane' );
        $potential_npv         = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'potential_npv' );
        $job_costs             = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'job_costs' );
        $job_kwh               = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'job_kwh' );
        $job_kw_s              = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'job_kw_s' );
        $job_kw_w              = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'job_kw_w' );
        $job_m3saved           = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'job_m3saved' );
        $job_l_oil             = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'job_l_oil' );
        $job_l_propane         = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'job_l_propane' );
        $job_npv               = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'job_npv' );
        $basic_m3              = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'basic_m3' );
        $extended_m3           = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'extended_m3' );
        $job_bcr               = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'job_bcr' );
        $ft2                   = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'ft2' );
        $h_s_cost       = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'H_S_cost' );
        $htg_sys       = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'htg_sys' );
      
                
        if( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ){
            
            $h_s_checkdone  = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'H_S_checkDone' );
            $education      = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'Education' );
            $fridge1_kwh    = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'Fridge1_kwh' );
            $freezer_kwh    = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'Freezer_kwh' );
            $d2d_date       = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'D2D_date' );
            $wxauditdate    = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'WxAuditDATE' );
            $qaveriaudit    = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'QAVeriAudit' );
            $qa_phone_date  = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'QA_phone_date' );
            $ext_wxcosts    = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'Ext_WxCosts' );
            $wx_costs       = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'WX_COSTS' );
            $h_s_cost       = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'H_S_cost' );
            
            // make sure retrofit_completed date doesn't get reset.
            $d2d_date_value =  $this->_data[24][19];
            switch($d2d_date_value){
            case '0-0-0':
            case  "":
                $mo[$d2d_date]  = "null";
            break;   
            default:
                $mo[$d2d_date]  = date("Ymd", strtotime($d2d_date_value));
            }
            
            $wxauditdate_value =  $this->_data[25][19];
            switch($wxauditdate_value){
            case '0-0-0':
            case  "":
                $mo[$wxauditdate]  = "null";
            break;   
            default:
                $mo[$wxauditdate]  = date("Ymd", strtotime($wxauditdate_value));
            }
            
            $qaveriaudit_value =  $this->_data[26][19];
            switch($qaveriaudit_value){
            case '0-0-0':
            case  "":
                $mo[$qaveriaudit]  = "null";
            break;   
            default:
                $mo[$qaveriaudit]  = date("Ymd", strtotime($qaveriaudit_value));
            }

            $mo[$education]     = $this->_data[22][19];
            $mo[$fridge1_kwh]   = (double)$this->_data[1][16];
            $mo[$freezer_kwh]   = (double)$this->_data[1][17];
            // $mo[$d2d_date]      = date("Ymd", strtotime($this->_data[24][19]));
            $mo[$ext_wxcosts]   = (double)$this->_data[29][19];
            $mo[$h_s_cost]      = (double)$this->_data[23][19];
            // $mo[$wxauditdate]   = date("Ymd", strtotime($this->_data[25][19]));
            // $mo[$qaveriaudit]   = date("Ymd", strtotime($this->_data[26][19]));
            $mo[$wx_costs]      = (double)$this->_data[29][4];
            $mo[$qa_phone_date] = "";
            $mo[$h_s_checkdone] = 1;
        
        }
        
      
        $Params =array(
                       'entityID' => $cid,
                       $retrofit_completed => 1,
                       
                       );
        
        
        $result  = CRM_Core_BAO_CustomValueTable::getValues( $Params );
        
        // make sure retrofit_completed date doesn't get reset.
        if ( $result[$retrofit_completed] ) {
            if ( $result[$retrofit_completed] == '0000-00-00' ) {
                // unset to avoid confusion 
                $mo[$retrofit_completed]  = "null";
            } else {
                $mo[$retrofit_completed]  = date("Ymd", strtotime($result[$retrofit_completed]));
                
            }
        }
        
        $mo[$audit_completed]  = 'null';
        if( !empty( $this->_data[1][3] ) ) {
            $mo[$audit_completed]     = date("Ymd", strtotime($this->_data[1][3]));
        }
        
        $mo['entity_id'] = $cid;
        //$mo[$audit_completed]     = date("Ymd", strtotime($this->_data[1][3]));
        $mo[$audit_type_id]       = (float)$this->_data[1][4]; // this is now audit completion time in hours
        $mo[$wac]                 = (int)$this->_data[1][5];
        $mo[$computers]           = (int)$this->_data[1][6];
        $mo[$fridges]             = (int)$this->_data[1][7];
        $mo[$frzrs]               = (int)$this->_data[1][8];
        $mo[$shhd_flow]           = (double)$this->_data[1][9];
        $mo[$shhd_flow_after]     = (double)$this->_data[1][10];
        $mo[$software]            = $this->_data[1][11];
        $mo[$bm_costs]            = (double)$this->_data[3][4];
        $mo[$bm_kwh]              = (double)$this->_data[3][5];
        $mo[$bm_kw_s]             = (double)$this->_data[3][6];
        $mo[$bm_kw_w]             = (double)$this->_data[3][7];
        $mo[$bm_trc]              = (double)$this->_data[3][11];
        $mo[$xm_costs]            = (double)$this->_data[26][4];
        $mo[$xm_kwh]              = (double)$this->_data[26][5];
        $mo[$xm_kw_s]             = (double)$this->_data[26][6];
        $mo[$xm_kw_w]             = (double)$this->_data[26][7];
        $mo[$xm_trc]              = (double)$this->_data[26][11];
        $mo[$potential_costs]     = (double)$this->_data[27][4];
        $mo[$potential_kwh]       = (double)$this->_data[27][5];
        $mo[$potential_kw_s]      = (double)$this->_data[27][6];
        $mo[$potential_kw_w]      = (double)$this->_data[27][7];
        $mo[$potential_m3saved]   = (double)$this->_data[27][8];
        $mo[$potential_l_oil]     = (double)$this->_data[27][9];
        $mo[$potential_l_propane] = (double)$this->_data[27][10];
        $mo[$potential_npv]       = (double)$this->_data[27][11];
        $mo[$job_costs]           = (double)$this->_data[28][4];
        $mo[$job_kwh]             = (double)$this->_data[28][5];
        $mo[$job_kw_s]            = (double)$this->_data[28][6];
        $mo[$job_kw_w]            = (double)$this->_data[28][7];
        $mo[$job_m3saved]         = (double)$this->_data[28][8];
        $mo[$job_l_oil]           = (double)$this->_data[28][9];
        $mo[$job_l_propane]       = (double)$this->_data[28][10];
        $mo[$job_npv]             = (double)$this->_data[28][11];
        $mo[$basic_m3]            = (int)$this->_data[3][8];
        $mo[$extended_m3]         = (int)$this->_data[26][8];
   
        if( ! (defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC) ){
            $mo[$h_s_cost]      = (double)$this->_data[1][15];
            $mo[$htg_sys]      = $this->_data[1][14];
        }
        
        if(count($this->_data[$i]) == 14 ) {
            $mo[$job_bcr]         = NULL;
        } else {
            $mo[$job_bcr]         = (double)$this->_data[28][14];
        }
        $mo[$ft2]                   = (int)$this->_data[1][13];
    
        CRM_Core_BAO_CustomValueTable::postProcess( $mo,
                                                    $fields,
                                                    'gcc_measures_other',
                                                    $cid,
                                                    'Individual' );

        /*** Create/Update Auditor Notes - Start ***/
        $auditornotes    =  array('entity_table' => 'gcc_applicant',
                             'entity_id'    => $cid,
                             'contact_id'   => $cid,
                             'subject'      => 'auditornotes',
                             'version'      => 3 
                             );
        
        $getNote = civicrm_api( 'note','get',$auditornotes);
        
        if(array_key_exists('id',$getNote )){
            $auditornotes['id'] = $getNote['id'];
            
        }
        $auditornotes['note']         = ( $this->_data[1][1] ) ? $this->_data[1][1] : 'null';
        $createNnote = civicrm_api( 'note','create',$auditornotes);
        /*** Create/Update Auditor Notes - End ***/
        
         /**
          *Create H & S Note Here. Start
          *
          **/
        if( !( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC )  ){
            $hsnotes    =  array('entity_table' => 'gcc_applicant',
                                 'entity_id'    => $cid,
                                 'contact_id'   => $cid,
                                 'subject'      => 'hsnotes',
                                 'version'      => 3 
                                 );
            
            $getNote = civicrm_api( 'note','get',$hsnotes);
            
            if(array_key_exists('id',$getNote )){
                $hsnotes['id'] = $getNote['id'];
            }
            $hsnotes['note']         = ( $this->_data[1][16] ) ? $this->_data[1][16] : 'null';
       
            $createNnote = civicrm_api( 'note','create',$hsnotes);
        }
        /**
          *Create H & S Note Here. End
          *
          **/
    }
    
    /**
     * Function to delet option value give an option value and custom group id
     * 
     * @param int $customValueID custom value ID
     * @param int $customGroupID custom group ID
     *
     * @return void
     * @static
     */
    static function deleteCustomValue( $customValueID, $customGroupID ) {
        // first we need to find custom value table, from custom group ID
        $tableName = CRM_Core_DAO::getFieldValue( 'CRM_Core_DAO_CustomGroup', $customGroupID, 'table_name' );
        
        // delete custom value from corresponding custom value table
        $sql = "DELETE FROM {$tableName} WHERE entity_id = {$customValueID}";
        CRM_Core_DAO::executeQuery( $sql );
    }
}
?>
