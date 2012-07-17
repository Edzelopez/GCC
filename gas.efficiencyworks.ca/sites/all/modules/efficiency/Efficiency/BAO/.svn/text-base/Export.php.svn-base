<?php
class Efficiency_BAO_Export {

    public $fileID;
    protected $_applicant;
    public $_cid;
    public $_contact;
    protected $_measures = array();
    protected $_measures_other;

    /**
     * class constructor
     */
    function __construct($contactID) {
        $this->_cid = $contactID;
        $this->load_contact( $contactID );
        $params = array( 
                        'contact_id' => $contactID,
                        'location_type_id' => 6,
                        'is_primary' => 1,
                        'version' => 3,
                        );
        
        require_once 'api/api.php';
        $result = civicrm_api( 'email','get',$params );
        $applicant = array();
        if( !empty( $result[ 'values' ] ) ){
            $applicant['email'] = $result['values'][$result['id']]['email'];
            
        }
        $this->_applicant      = $this->_getCustomValues($contactID,'GCC_applicant');
        $this->_applicant['email'] = $applicant['email'];
        $this->_measures       = $this->_getCustomValues($contactID,'gcc_measures');
        $this->_measures_other = $this->_getCustomValues($contactID,'gcc_measures_other') ;
        $this->fileID =$this->_applicant['file_identifier'];
        
    }

    /**
     * Function to get the list the export fields
     *
     * @param int $exportContact type of export
     *
     * @access public
     */
    function export() {

        // Get option group labels to translate *_id values
        $optionGroupNames = array(
                                  'ldc',
                                  'central_air',
                                  'gas_util',
                                  'pheat_fuel',
                                  'dhw_fuel',
                                  'house_type',
                                  'income_basis',
                                  'referral',
                                  );
        $optionGroups = array();
        
        foreach ($optionGroupNames as $name) {
            $optionGroups[$name] = CRM_Core_OptionGroup::values($name);
        }

        if  ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ){
            $electric = true;
        }else{
            $electric = false;
        }

        /*** Retrieve Contact info with address - start***/
        $cparams       = array();
        $cdefaults     = array();
        $cparams['id'] = $params['contact_id'] = $this->_contact['id'];
        $contactinfo   = CRM_Contact_BAO_Contact::retrieve( $cparams, $cdefaults, true );
        
        if ( isset( $contactinfo->address[1]['state_province_id'] ) ) {
            //Fetch State / Province name
            $contactinfo->municipality = CRM_Core_DAO::getFieldValue( 'CRM_Core_DAO_StateProvince', 
                                                                      $contactinfo->address[1]['state_province_id'], 
                                                                      'name', 
                                                                      'id' );
        }
        /*** Retrieve Contact info with address - end***/

        // Field names, values for export
        
        if ( $electric == false ){
            $noteParams = array(
                                'entity_table' => 'gcc_applicant',
                                'entity_id'    => $this->_contact['id'],
                                'contact_id'   => $this->_contact['id'],
                                'subject'      => 'note',
                                'version'      => 3
                                );
            require_once 'api/api.php';
            $notes = civicrm_api( 'note','get',$noteParams );

            if ( array_key_exists( 'id', $notes ) )  {
                $notesValue = $notes['values'][$notes['id']]['note'];
            }
            else  {
                $notesValue = '';
            }
            
            $fields['FileID']        = $this->fileID;
            $fields['Name']          = $this->_contact['values'][$this->_contact['id']]['display_name'];
            //$fields['Address']       = $this->_contact['values'][$this->_contact['id']]['street_address'];
            //$fields['Municipality']  = $this->_contact['values'][$this->_contact['id']]['city'];
            //$fields['Postal code']   = $this->_contact['values'][$this->_contact['id']]['postal_code'];
            $fields['Address']       = isset( $contactinfo->address[1]['street_address'] ) ? $contactinfo->address[1]['street_address'] : '';
            $fields['Municipality']  = isset( $contactinfo->address[1]['city'] ) ? $contactinfo->address[1]['city'] : ''; 
            //$fields['Municipality']  = isset( $contactinfo->municipality ) ? $contactinfo->municipality : '';
            $fields['Postal code']   = isset( $contactinfo->address[1]['postal_code'] ) ? $contactinfo->address[1]['postal_code'] : '';
                
            $fields['Phone']         = $this->_contact['values'][$this->_contact['id']]['phone'];
            $fields['Occupants']     = $this->_applicant['occupants'];
            $fields['Adults']        = $this->_applicant['adults'];
            $fields['PLanguage']     = $this->_applicant['planguage'];
            $fields['Pay heat']      = $this->_applicant['is_pay_heat'] ? 'Yes' : 'No';
            $fields['Pay elec']      = $this->_applicant['is_pay_elec'] ? 'Yes' : 'No';
            $fields['LDC']           = $optionGroups['ldc'][$this->_applicant['ldc_id']];
            $fields['LDC Acct']      = $this->_applicant['ldc_acct'];
            $fields['Gas utility']   = $optionGroups['gas_util'][$this->_applicant['gas_util_id']];
            $fields['Gas Acct']      = $this->_applicant['gas_acct'];
            $fields['Central Air']   = $optionGroups['central_air'][$this->_applicant['central_air_id']];
            $fields['Tenure']        = $this->_applicant['tenure'];
            $fields['LL Name']       = isset($this->_contact['Landloard']['display_name'])?$this->_contact['Landloard']['display_name']:' ';
            $fields['LL Address']    = isset($this->_contact['Landloard']['street_address'])?$this->_contact['Landloard']['street_address']:' ';
            $fields['LL City']       = isset($this->_contact['Landloard']['city'])?$this->_contact['Landloard']['city']:'';
            $fields['LL Pcode']      = isset($this->_contact['Landloard']['postal_code'])?$this->_contact['Landloard']['postal_code']:'';
            $fields['LL Phone']      = isset($this->_contact['Landloard']['phone'])?$this->_contact['Landloard']['phone']:'';
            $fields['LL Email']      = isset($this->_contact['Landloard']['email'])?$this->_contact['Landloard']['email']:'';
            $fields['PheatFuel']     = $optionGroups['pheat_fuel'][$this->_applicant['pheat_fuel_id']];
            $fields['DHWfuel']       = $optionGroups['dhw_fuel'][$this->_applicant['dhw_fuel_id']];
            $fields['HouseType']     = $optionGroups['house_type'][$this->_applicant['house_type_id']];
            $fields['IncomeBasis']   = $optionGroups['income_basis'][$this->_applicant['income_basis_id']];
            $fields['Verified By']   = $this->_applicant['verified_by'];
            $fields['Referral']      = $optionGroups['referral'][$this->_applicant['referral_id']];
            $fields['Auditor']       = $this->_contact['auditor'];
            //$fields['CSR Notes']     = isset($this->_contact['note'])?$this->_contact['note']:'';
            $fields['CSR Notes']     = $notesValue;
            $fields['Corrections']   = $this->_applicant['corrections'];
                      
        }else{
            
            $noteParams = array(
                                'entity_table' => 'gcc_applicant',
                                'entity_id'    => $this->_contact['id'],
                                'contact_id'   => $this->_contact['id'],
                                'subject'      => 'note',
                                'version'      => 3
                                );
            require_once 'api/api.php';
            $notes = civicrm_api( 'note','get',$noteParams );

            if ( array_key_exists( 'id', $notes ) ) 
                $notesValue = $notes['values'][$notes['id']]['note'];
            else 
                $notesValue = '';

            $fields['FileID']        = $this->fileID;
            $fields['Name']          = $this->_contact['values'][$this->_contact['id']]['display_name'];
            $fields['Address']       = isset( $contactinfo->address[1]['street_address'] ) ? $contactinfo->address[1]['street_address'] : '';
            $fields['Municipality']  = isset( $contactinfo->municipality ) ? $contactinfo->municipality : '';
            $fields['Postal code']   = isset( $contactinfo->address[1]['postal_code'] ) ? $contactinfo->address[1]['postal_code'] : '';
            $fields['Phone']         = $this->_contact['values'][$this->_contact['id']]['phone'];
            $fields['Occupants']     = $this->_applicant['occupants'];
            $fields['Adults']        = $this->_applicant['adults'];
            $fields['PLanguage']     = $this->_applicant['planguage'];
            $fields['Pay Heat']      = $this->_applicant['is_pay_heat'] ? 'Yes' : 'No';
            $fields['Pay Elec']      = $this->_applicant['is_pay_elec'] ? 'Yes' : 'No';
            $fields['LDC']           = $optionGroups['ldc'][$this->_applicant['ldc_id']];
            $fields['LDC Acct']      = $this->_applicant['ldc_acct'];
            $fields['Gas utility']   = $optionGroups['gas_util'][$this->_applicant['gas_util_id']];
            $fields['Gas Acct']      = $this->_applicant['gas_acct'];
            $fields['Central Air']   = isset($optionGroups['central_air'][$this->_applicant['central_air_id']])?$optionGroups['central_air'][$this->_applicant['central_air_id']]:'none';
            $fields['Tenure']        = $this->_applicant['tenure'];
            $fields['LL Name']       = isset($this->_contact['Landloard']['corporate_name'])?$this->_contact['Landloard']['corporate_name']:'';
            $fields['LL Address']    = isset($this->_contact['Landloard']['street_address'])?$this->_contact['Landloard']['street_address']:'';
            $fields['LL City']       = isset($this->_contact['Landloard']['city'])?$this->_contact['Landloard']['city']:'';
            $fields['LL Pcode']      = isset($this->_contact['Landloard']['postal_code'])?$this->_contact['Landloard']['postal_code']:'';
            $fields['LL Phone']      = isset($this->_contact['Landloard']['phone'])?$this->_contact['Landloard']['phone']:'';
            $fields['PheatFuel']     = $optionGroups['pheat_fuel'][$this->_applicant['pheat_fuel_id']];
            $fields['DHWFuel']       = $optionGroups['dhw_fuel'][$this->_applicant['dhw_fuel_id']];
            $fields['HouseType']     = $optionGroups['house_type'][$this->_applicant['house_type_id']]; 
            $fields['IncomeBasis']   = $optionGroups['income_basis'][$this->_applicant['income_basis_id']];
            $fields['Verified By']   = $this->_applicant['verified_by'];
            $fields['Referral']      = $optionGroups['referral'][$this->_applicant['referral_id']];
            $fields['Auditor']       = $this->_contact['auditor'];
            //$fields['CSR Notes']     = isset($this->_contact['note'])?$this->_contact['note']:''; 
            $fields['CSR Notes']     = $notesValue;
            $fields['Corrections']   = $this->_applicant['corrections'];
            $fields['Lname']         = $this->_contact['values'][$this->_contact['id']]['last_name'];
            $fields['Fname']         = $this->_contact['values'][$this->_contact['id']]['first_name'];
            $fields['Email']         = $this->_contact['values'][$this->_contact['id']]['email'];
            $fields['LL Contact']    = isset($this->_contact['Landloard']['display_name'])?$this->_contact['Landloard']['display_name']:'';
            $fields['LL Email']      = isset($this->_contact['Landloard']['email'])?$this->_contact['Landloard']['email']:'';
            $fields['Unit#']         = $this->_contact['values'][$this->_contact['id']]['postal_code_suffix'];
            $fields['SocialHousing'] = $this->_contact['Landloard']['social_housing'];
            $fields['YearBuilt']     = $this->_applicant['YEAR_BUILT'];
        }
        // CSV column headers
        $output = ",Field Name,Data\r\n";
        
        // Simple CSV escape mechanism
        foreach ($fields as $k => $v) {
            if (preg_match('/[,"\n]/', $v)) {
                $v =  preg_replace('/"/', '""', $v);
                $v = '"' . $v . '"';
            }
            $output .= ",$k,$v,\r\n";
        }
        
        return $output;
    }
    
    /**
     * Function to load contact object
     *
     *
     * @return boolean
     */
    function load_contact($cid) {

        
        require_once 'api/api.php';
        require_once 'CRM/Core/BAO/Note.php';
        
        $params = array( 
                        'contact_id' => $cid,
                        'version' => 3,
                         );
        
        $contact = civicrm_api( 'contact','get',$params );
        $this->_contact = $contact;
        
        $result = civicrm_api( 'relationship','get',$params );
        
        $auditorRelID  = CRM_Core_DAO::getFieldValue( 'CRM_Contact_DAO_RelationshipType', 'Auditor for', 'id', 'name_a_b' );
        $retrofitRelID = CRM_Core_DAO::getFieldValue( 'CRM_Contact_DAO_RelationshipType', 'Retrofit for', 'id', 'name_a_b' );
        $landlordRelID = CRM_Core_DAO::getFieldValue( 'CRM_Contact_DAO_RelationshipType', 'Landlord of', 'id', 'name_a_b' );
        
        foreach($result['values'] as $key => $contact) {
            if (($contact['civicrm_relationship_type_id'] == $auditorRelID) && !$contact['end_date']) {
                $this->_contact['auditor'] = $contact['display_name'];
                
            } elseif (($contact['civicrm_relationship_type_id'] == $retrofitRelID) && !$contact['end_date']) {
                $this->_contact['retrofit'] = $contact['display_name'];
                
            }elseif(($contact['civicrm_relationship_type_id'] == $landlordRelID) && !$contact['end_date'])
                 $this->_contact['Landloard'] = $contact;
        }
        
        $LandlordID =  $this->_contact['Landloard']['cid'];

        
        $paramsLandlord = array( 
                                'contact_id' => $this->_contact['Landloard']['cid'],
                                'version' => 3,
                                );
        
        $landlordContact =  civicrm_api( 'contact','get',$paramsLandlord );
        
        if ( array_key_exists( 'id', $landlordContact ) ){
            if  ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ){ 
                $landlord_custom = $this->_getCustomData( $LandlordID, 'landlord_custom_group','entity_id' ) ;
                $this->_contact['Landloard']['social_housing'] = $landlord_custom['social_housing'];
                $this->_contact['Landloard']['corporate_name'] = $landlord_custom['corporate_name'];
            }else{ 
                
                $this->_contact['Landloard']['display_name'] = $landlordContact['values'][$landlordContact['id']]['display_name'];
                $this->_contact['Landloard']['city'] =  $landlordContact['values'][$landlordContact['id']]['city'];
                $this->_contact['Landloard']['phone'] = $landlordContact['values'][$landlordContact['id']]['phone'];
                
            }
                $this->_contact['Landloard']['street_address'] = $landlordContact['values'][$landlordContact['id']]['street_address'];
                $this->_contact['Landloard']['postal_code']    = $landlordContact['values'][$landlordContact['id']]['postal_code'];
            }
        // Notes Part
        
        $params['contact_id']   = $this->_cid;
        $params['entity_id']    = $this->_cid;
        $params['entity_table'] = 'gcc_applicant';
        $params['version']      = 3;
        
        $note = civicrm_api( 'note','get',$params );
        if(array_key_exists('id',$note )){
            $this->_contact['note'] = $note['values'][$note['id']]['note'];
        }

    }
    
    /**
     * Function to export a file without prompting (used when audit/retrofit is assigned)
     *
     * @return void
     * @access public
     */
    static function doInternalExport($contactID, $filename) {
        require_once 'Efficiency/BAO/Export.php';
       
        $export =& new Efficiency_BAO_Export( $contactID );
        $output = $export->export();
        $config = & CRM_Core_Config::singleton();
        $directoryName = $config->customFileUploadDir . 'Applicant' . DIRECTORY_SEPARATOR . $contactID;
        
        require_once "CRM/Utils/File.php";
        CRM_Utils_File::createDir( $directoryName );
        $filename = $filename .'.csv';
        $writeTo = $directoryName . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($writeTo, $output);
        
        require_once "CRM/Core/DAO/EntityFile.php";
        require_once 'CRM/Core/BAO/File.php';
        
        $fileDAO =& new CRM_Core_DAO_File();
        $fileDAO->uri               = $filename;
        $fileDAO->mime_type         = 'text/x-csv';
        
        if ( $fileDAO->find(true) ) {
            $entityFileDAO =& new CRM_Core_DAO_EntityFile();
            $entityFileDAO->entity_table = 'civicrm_contact';
            //             $entityFileDAO->entity_id    = $contactID;
            $entityFileDAO->file_id      = $fileDAO->id;
            $entityFileDAO->delete( );
            
            $fileDAO->delete( );
        } 
        
        $fileDAO =& new CRM_Core_DAO_File();
        $fileDAO->uri               = $filename;
        $fileDAO->mime_type         = 'text/x-csv';
        $fileDAO->upload_date       = date('Ymdhis'); 
        $fileDAO->save();
        
        $entityFileDAO =& new CRM_Core_DAO_EntityFile();
        $entityFileDAO->entity_table = 'civicrm_contact';
        $entityFileDAO->entity_id    = $contactID;
        $entityFileDAO->file_id      = $fileDAO->id;
        $entityFileDAO->save();
    }
    
    
    /**
     * Helper Function to get Custom Values 
     *
     */
    static function _getCustomValues($contactID,$entity) {
        $data = array();
        require_once 'Efficiency/BAO/Applicant.php';
        require_once 'CRM/Core/OptionGroup.php';
        require_once 'CRM/Core/BAO/CustomField.php';
        require_once 'CRM/Core/BAO/CustomValueTable.php';
        require_once 'CRM/Core/BAO/CustomGroup.php';
        $groups =  Efficiency_BAO_Applicant::getCustomTree('Individual', null, $entity);
         
        $gcc_measures=array();
        foreach($groups as $key => $value){
            $gcc_measures[$key]= 1;
        }
        $gcc_measures['entityID'] = $contactID;
        $measures = CRM_Core_BAO_CustomValueTable::getValues(  $gcc_measures );
        unset($measures['is_error']);
        unset($measures['entityID']);
        
        foreach($measures as $key => $value){
            $key_expload = explode("_", $key);
            $count  =  count($key_expload);
            $oldKey = $key_expload[0].'_'.$key_expload[1];
            foreach($groups as $key1 => $values1 )
                {
                    if($oldKey == $key1){
                        if($count == 3) {
                            $data[$key_expload[2]][$values1] = $value; 
                        }else{
                            $data[$values1] = $value;
                        }
                    }
                    
                }
        }
        
        return $data;
        
    }
    
    
    /**
     * Helper Function to get Custom Data 
     *
     */
    static function _getCustomData($id,$tableName,$columnName) {
        
        require_once 'CRM/Core/BAO/CustomField.php';
      
        $query  = "SELECT * FROM $tableName WHERE $columnName = %1";
        $params = array( 1 => array( $id, 'Integer' ) );
        $dao    =  CRM_Core_DAO::executeQuery( $query, $params );
        $result = array();
        while( $dao->fetch( )){}
        $resultSet = $dao->_resultFields;
        if (!empty($resultSet)) {       
        foreach($resultSet as $key => $value){
            $result[$key] = $dao->$key;
        }
        }else{
            $result = '';
        }
        return $result ;
        
    }

    /**
     * Helper Function to get Custom Data 
     *
     */
    static function _getMeasurs($id,$tableName,$columnName) {
        
        require_once 'CRM/Core/BAO/CustomField.php';
        
        $query  = "SELECT * FROM $tableName WHERE $columnName = %1";
        $params = array( 1 => array( $id, 'Integer' ) );
        $dao    =  CRM_Core_DAO::executeQuery( $query, $params );
        $result = array();
        $resultSet = array();
        while( $dao->fetch( )){
            $resultSet = (array)$dao;
            foreach($dao as $key => $value){
                $result[$resultSet['id']][$key] = $value;
                
            }
        }
       
        return $result ;
        
    }




    
  }
?>
