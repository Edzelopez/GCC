
<?php

require_once 'CRM/Utils/Date.php';
require_once 'CRM/Core/ShowHideBlocks.php';
require_once 'Efficiency/Form/AddApplicant.php';
require_once 'CRM/Utils/Request.php';
require_once 'CRM/Contact/BAO/Contact.php';
require_once 'api/api.php';
require_once 'Efficiency/Form/AddApplicant/TabHeader.php';
require_once 'CRM/Core/Permission.php';
require_once 'Efficiency/BAO/Export.php';
require_once 'Efficiency/BAO/Applicant.php';

/**
 * This class generates form components for relationship
 * 
 */
class Efficiency_Form_AddApplicant_Projectdetails extends Efficiency_Form_AddApplicant
{    
  
    protected $_retrofitID;
    protected $_show   = null;
    static    $_links  = null;
    protected $_measuresOtherID;
    
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
        $url = CRM_Utils_System::url('civicrm/efficiency/applicant/projectdetails/view', 
                                     'reset=1&cid=' . $this->_applicantId );
        
        $session->pushUserContext( $url );

        $dateFields    = array('xm_workorder_issued', 'xm_installed', 'xm_verified', 'xm_pay_authorized');
        $measures      = Efficiency_BAO_Export::_getCustomValues($this->_applicantId,'gcc_measures');
        $requiedToShow = array('installed', 'costs', 'kwh', 'npv', 'funder');
        $this->_applicantStatus = '';
        if ( is_array( $measures ) ) {
            $links =& self::links( );
            ( $links )?$action = array_sum( array_keys( $links ) ):$action ='';
            //  $action = array_sum( array_keys( $links ) );
            foreach( $measures as $key => $val ) {
                $realLinks = $links;
                // A PM can never be editied
                if ( $measures[$key]['measures'] == 'PM' ) {
                    unset( $realLinks[CRM_Core_Action::UPDATE] );
                } else if ( $measures[$key]['measures'] == 'XM' && !CRM_Core_Permission::check( 'edit_app_project_mesears' ) ) {
                    // An XM can only be edited by Admin and Retrofit Manager
                    unset( $realLinks[CRM_Core_Action::UPDATE] ); 
                }
                
                // unset current key if necessary fields are null
                // we don't want that to be displayed
                
                $anyFieldTrue = false;
                foreach ( $requiedToShow as $fld ) {
                    if ( $val[$fld] ) {
                        $anyFieldTrue = true;
                    }
                }
                
                if ( !$anyFieldTrue ) {
                    unset( $measures[$key] );
                    continue;
                }
                
                if ( $measures[$key]['measures'] == 'XM' ) {
                    $params   = array( 'measures_id' => $key );
                    $retrofit = Efficiency_BAO_Export::_getCustomValues($this->_applicantId,'gcc_retrofit');
                  
                    if ( !empty( $retrofit ) ){ 
                        foreach( $retrofit as $rkey => $rvalue ) {
                            if( $rvalue['measures_id'] == $key ){
                                foreach( $dateFields as $field ) {
                                    $measures[$key][$field] = $rvalue[$field];
                                }
                            }
                        } 
                    }
                    unset( $defaults );
                }
                
                $measures[$key]['action'] = CRM_Core_Action::formLink($realLinks,
                                                                      $action, 
                                                                      array('mid' => $key,
                                                                            'cid' => $this->_applicantId));
                }
        }

        if ( is_array( $measures ) ) {
            $this->assign( 'rows', $measures );
            $this->assign( 'show', $this->_show );
        }
        
        // also add summary information (other measures)
        $params         = array( 'applicant_id' => $this->_applicantId );
        $ids            = array( );
        $measuresOther  = Efficiency_BAO_Export::_getCustomValues( $this->_applicantId,'gcc_measures_other' ) ;
        
        $this->assign( 'summary', $measuresOther );
        $this->assign( 'cid', $this->_applicantId );
        $this->assign( 'role',$this->_role );
        $this->assign( 'mid', $this->_measuresID );
        
        
        //gcc_measures_other
        $viewfields       =  array();
        $customGroupName  =  "gcc_measures_other";
        $this->_mo        =  CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_CustomGroup", $customGroupName, 'id', 'name' );
        $entityType       =  CRM_Contact_BAO_Contact::getContactType( $this->_applicantId );
        $entitySubType    =  CRM_Contact_BAO_Contact::getContactSubType( $this->_applicantId );
        $this->_moTree    =& CRM_Core_BAO_CustomGroup::getTree( $entityType, $this, $this->_applicantId, 
                                                                $this->_mo, $entitySubType );
        
        if ( $this->_action & CRM_Core_Action::VIEW ) {
            $viewfields         = CRM_Core_BAO_CustomGroup::buildCustomDataView( $this, $this->_moTree, 
                                                                                 false, null, "dnc_" );
            $this->_moTree      = CRM_Core_BAO_CustomGroup::formatGroupTree( $this->_moTree, 1, $this );
            
            $software           =  CRM_Core_BAO_CustomField::getCustomFieldID( 'software' );
            $audit_completed    =  CRM_Core_BAO_CustomField::getCustomFieldID( 'audit_completed' );
            $retrofit_completed =  CRM_Core_BAO_CustomField::getCustomFieldID( 'retrofit_completed' );
            $base_gas_m3        =  CRM_Core_BAO_CustomField::getCustomFieldID( 'base_gas_m3' );
            $wxauditdate        =  CRM_Core_BAO_CustomField::getCustomFieldID( 'wxauditdate' );
            $qaveriaudit        =  CRM_Core_BAO_CustomField::getCustomFieldID( 'qaveriaudit' );
            
            if ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) {
                foreach ($viewfields[$this->_mo]  as $key1 => $fields  ) {
                    foreach ( $fields['fields']  as $fkey => $fvalue  ) {
                        if ( $fkey != $software && $fkey != $audit_completed && $fkey != $retrofit_completed && $fkey != $base_gas_m3  && $fkey != $wxauditdate && $fkey != $qaveriaudit ) {
                            unset($viewfields[$this->_mo][$key1]['fields'][$fkey]);
                        }
                    }
                }

                /*** Change the sequence of fiels in gcc_measures_other - Start ***/
                $view_Data = array();
                $moID                           = Efficiency_BAO_Applicant::getFieldValue( 'gcc_measures_other',
                                                                                           'id', $this->_applicantId );
                if ( empty( $moID ) ) 
                    $moID = 0;
                $view_Data[$audit_completed]    = $viewfields[$this->_mo][$moID]['fields'][$audit_completed];
                $view_Data[$wxauditdate]        = $viewfields[$this->_mo][$moID]['fields'][$wxauditdate];
                $view_Data[$qaveriaudit]        = $viewfields[$this->_mo][$moID]['fields'][$qaveriaudit];
                $view_Data[$retrofit_completed] = $viewfields[$this->_mo][$moID]['fields'][$retrofit_completed];
                $view_Data[$software]           = $viewfields[$this->_mo][$moID]['fields'][$software];
                $view_Data[$base_gas_m3]        = $viewfields[$this->_mo][$moID]['fields'][$base_gas_m3];
                /*** Change the sequence of fiels in gcc_measures_other - End ***/
                $viewfields[$this->_mo][$moID]['fields'] = $view_Data;

            } else {
                foreach ($viewfields[$this->_mo]  as $key1 => $fields  ) {
                    foreach ( $fields['fields']  as $fkey => $fvalue  ) {
                        if ( $fkey != $software && $fkey != $audit_completed && $fkey != $retrofit_completed && $fkey != $base_gas_m3 ) {
                            unset($viewfields[$this->_mo][$key1]['fields'][$fkey]);
                        }
                    }
                }
            }
        }
       
        
        $this->_moTree = CRM_Core_BAO_CustomGroup::formatGroupTree( $this->_moTree, 1, $this );

        //gcc_misc
        
        $customGroupName  = "gcc_misc";
        $this->_misc      = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_CustomGroup", $customGroupName, 'id', 'name' );
        $entityType       = CRM_Contact_BAO_Contact::getContactType( $this->_applicantId );
        $entitySubType    = CRM_Contact_BAO_Contact::getContactSubType( $this->_applicantId );
        $this->_miscTree  = & CRM_Core_BAO_CustomGroup::getTree( $entityType, $this, $this->_applicantId, 
                                                                $this->_misc, $entitySubType );
        
        if( CRM_Core_Permission::check( 'view_project_invoiced' ) ){
            if ( $this->_action & CRM_Core_Action::VIEW ) {
                $viewfields1  = CRM_Core_BAO_CustomGroup::buildCustomDataView( $this, $this->_miscTree, false, null, "dnc_" );
                $retrofit_invoiced =  CRM_Core_BAO_CustomField::getCustomFieldID( 'retrofit_invoiced' );
                $audit_invoiced    =  CRM_Core_BAO_CustomField::getCustomFieldID( 'audit_invoiced' );
                
                foreach ( $viewfields1[$this->_misc]  as $key1 => $fields  ) {
                    foreach ( $fields['fields'] as $fkey => $fvalue  ) {
                        if ( $fkey != $retrofit_invoiced || $fkey != $audit_invoiced ){
                            $viewfields[$this->_misc][$key1]['fields'][$fkey] = $fvalue;
                           
                        }
                    }
                }
            }
        }
        $this->assign( 'viewData', $viewfields );
 
        $this->_miscTree  = CRM_Core_BAO_CustomGroup::formatGroupTree( $this->_miscTree, 1, $this );
        
        //gcc_retrofit
        $customGroupName     =  "gcc_retrofit";
        $this->_retrofit     =  CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_CustomGroup", $customGroupName, 'id', 'name' );
        $entityType          =  CRM_Contact_BAO_Contact::getContactType( $this->_applicantId );
        $entitySubType       =  CRM_Contact_BAO_Contact::getContactSubType( $this->_applicantId );
        $this->_retrofitTree =& CRM_Core_BAO_CustomGroup::getTree( $entityType, $this, $this->_applicantId, 
                                                                    $this->_retrofit, $entitySubType );
        $this->_retrofitTree =  CRM_Core_BAO_CustomGroup::formatGroupTree( $this->_retrofitTree, 1, $this );
        
        $this->_cid          = CRM_Utils_Request::retrieve( 'cid', 'Int', CRM_Core_DAO::$_nullObject );       

        //Get Staus form gcc_applicant        
         if ( $this->_cid ) {
            //Get Staus form gcc_applicant for current cid
            $this->_applicantStatus = Efficiency_BAO_Applicant::getFieldValue( 'gcc_applicant',
                                                                               'auto_status',
                                                                               $this->_cid );
            $params = array( 
                            'name'    => 'project_details_status',
                            'version' => 3,
                             );
            //Get Staus form gcc_applicant for current cid
            // Option Groups id for project_details_status// Retrieve Option Values for project_details_status Option Group
            $status = civicrm_api( 'option_group', 'get', $params );
            $status_values       = array();
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

                if ( $status_values && $this->_applicantStatus ) {
                    foreach ( $status_values as $skey => $sval ) {
                        if ( $sval['name'] == 'automatic' || $sval['name'] == 'closed_no_potential' || $sval['name'] == 'closed_participant_withdrew' ) {
                            if ( $sval['value'] == $this->_applicantStatus ) {
                                $this->_statusLabel = $sval['label'];// Set Status for View
                            }
                        }
                    }                                       
                }
            }
            
            if ( isset( $this->_statusLabel ) ) {
                $this->assign( 'Status' , $this->_statusLabel );
            } else {
                $this->assign( 'Status' , 'Automatic' );
            }
         }

         if ( CRM_Core_Permission::check( 'edit_app_project_status' ) ) {
             $statusURL = 1;
             $this->assign( 'statusURL' , $statusURL );
         }
         
         if ( CRM_Core_Permission::check( 'edit_app_project_date' ) ){
             $dateURL = 1;
             $this->assign( 'dateURL' , $dateURL );
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

        if ( $this->_contextView == 'audit' ){ 
            CRM_Core_BAO_CustomGroup::setDefaults( $this->_moTree, $this->_defaultmoTree );
            CRM_Core_BAO_CustomGroup::setDefaults( $this->_miscTree, $this->_defaultmiscTree );
            $moTree   = $this->_defaultmoTree;
            $miscTree = $this->_defaultmiscTree;
            $Title= 'Dates';
            $this->assign('Title',$Title);
            
            if ( $moTree ){
                foreach( $moTree as $key => $value ){
                    $defaults[$key] = $value;
                }
            }
            if ( $miscTree ){
                foreach( $miscTree as $key => $value ){
                    $defaults[$key] = $value;
                }
            }
        }elseif ( $this->_contextView == 'measure' ){
            $result = Efficiency_BAO_Export::_getCustomData($this->_measuresID ,'gcc_retrofit','measures_id') ;
            $result1 = Efficiency_BAO_Export::_getCustomData($this->_measuresID ,'gcc_measures','id') ;

            $Title= 'Measures Block';
            $this->assign('Title',$Title);
            $mesureTitle= $result1['name'];
            $this->assign('MesureTitle',$mesureTitle);
            
            if ( ! empty( $result ) ){
                foreach( $result as $key => $value ){
                    if($key != 'id' && $key != 'entity_id' && $key != 'measures_id'){
                        ($result['id'])?$id =  '_' . $result['id']:$id ='';
                        $fieldID = CRM_Core_BAO_CustomField::getCustomFieldID( $key  );
                        $mesureCustom = 'custom_' . $fieldID . $id;
                        
                        $date =  CRM_Utils_Date::setDateDefaults( $value );
                        $defaults[$mesureCustom] = $date[0];
                    }
                }
            }
        } 
        
        if ( $this->_statusLabel && $this->_applicantStatus ) {
            $defaults['project_details_status'] = $this->_applicantStatus;
        } else {
            $defaults['project_details_status'] = 0;
        }          
        
        return $defaults;
       
    }
    
    function &links()
        {
            if (! (self::$_links)) {
                $url  = 'civicrm/efficiency/applicant/projectdetails/update';
                $urlQ = 'context=measure&';
                $this->assign( 'url', $url );
                $this->assign( 'urlQ', $urlQ );
                
                if ( CRM_Core_Permission::check( 'edit_app_project' ) ) {
                    self::$_links = array(
                                          CRM_Core_Action::UPDATE  => array(
                                                                            'name'  => ts('Edit'),
                                                                            'url'   => $url,
                                                                            'qs'    => $urlQ .'action=update&mid=%%mid%%&show=1&cid=%%cid%%&reset=1',
                                                                            'title' => ts('Edit Audit Details') 
                                                                            ),
                                          );
                }
            }
            return self::$_links;
        }
    
    
    /**
     * Function to actually build the form
     *
     * @return void
     * @access public
     */
    public function buildQuickForm( ) 
    {        
        
        $fieldsArray = array();

        if ( $this->_contextView == 'audit' ){ 

            foreach ( $this->_moTree[$this->_mo]['fields']  as $fkey => $fvalue  ) { 
                
                if (  $fvalue['column_name'] != 'retrofit_completed' && $fvalue['column_name'] != 'base_gas_m3' ) {
                    
                    unset( $this->_moTree[$this->_mo]['fields'][$fkey] );
                }else{
                    $fieldsArray[$fvalue['column_name']] =  $this->_moTree[$this->_mo]['fields'][$fkey];
                }
            }
            
            
            if ( !CRM_Core_Permission::check( 'edit_project_basic_m3' ) ){
                $fieldKey = $fieldsArray['base_gas_m3']['id'];
                unset( $this->_moTree[$this->_mo]['fields'][$fieldKey]);
            }
            
        //     if( !CRM_Core_Permission::check( 'edit_project_audit_completed' ) ){
//                 $fieldKey = $fieldsArray['audit_completed']['id'];
//                 unset( $this->_moTree[$this->_mo]['fields'][$fieldKey] );
//             }
            if( !CRM_Core_Permission::check( 'edit_project_retrofit_completed' ) ){
                $fieldKey = $fieldsArray['retrofit_completed']['id'];
                unset( $this->_moTree[$this->_mo]['fields'][$fieldKey] );
            }
            
            foreach ( $this->_miscTree[$this->_misc]['fields']  as $fkey => $fvalue  ) {
                
                    if (  $fvalue['column_name'] == 'audit_invoiced' ||  $fvalue['column_name'] == 'retrofit_invoiced' ){
                        $this->_moTree[$this->_mo]['fields'][$fkey] = $fvalue ;
                        $fieldsArray[$fvalue['column_name']] = $fvalue;
                    }
            }
              
            
            if( ! CRM_Core_Permission::check( 'edit_project_audit_invoiced' ) ){
                $fieldKey = $fieldsArray['audit_invoiced']['id'];
                unset( $this->_moTree[$this->_mo]['fields'][$fieldKey] );
            }
            if( ! CRM_Core_Permission::check( 'edit_project_retrofit_invoiced' ) ){
                $fieldKey = $fieldsArray['retrofit_invoiced']['id'];
                unset( $this->_moTree[$this->_mo]['fields'][$fieldKey] );
            }
            
            if ( isset( $this->_moTree ) ) {
                
                CRM_Core_BAO_CustomGroup::buildQuickForm( $this, $this->_moTree );
            }
            
            $this->addFormRule( array( 'Efficiency_Form_AddApplicant_Projectdetails', 'formRule' ));
        }elseif ( $this->_contextView == 'measure' ){
            
            $result = Efficiency_BAO_Export::_getCustomData( $this->_measuresID ,'gcc_retrofit','measures_id' ) ;
          
            foreach ( $this->_retrofitTree[$this->_retrofit]['fields']  as $fkey => $fvalue  ) {
                
                if ( $fvalue['column_name'] == 'measures_id' ){
                    ( isset( $result['id'] ) )?$id =  '_' . $result['id']:$id ='';                
                    $fieldID = CRM_Core_BAO_CustomField::getCustomFieldID(  $fvalue['column_name'] );
                    $mesureCustom = 'custom_' . $fieldID . $id;
                    $this->add( 'hidden',$mesureCustom , $this->_measuresID );
                    unset($this->_retrofitTree[$this->_retrofit]['fields'][$fkey] );
                    
                }else{
                    
                    ( isset( $result['id'] ) )?$id =  '_' . $result['id']:$id =''; 
                    $fieldID = CRM_Core_BAO_CustomField::getCustomFieldID(  $fvalue['column_name'] );
                    $customField = 'custom_' . $fieldID . $id;
                    $this->_retrofitTree[$this->_retrofit]['fields'][$fkey]['element_name'] = $customField;
                }
            }
            
            
            if ( isset( $this->_retrofitTree ) ) {
                
                CRM_Core_BAO_CustomGroup::buildQuickForm( $this, $this->_retrofitTree );
            }   
        } 
        
        if ( CRM_Core_Permission::check( 'edit_app_project_status' ) ) {
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
                        if ( $sval['name'] == 'automatic' || $sval['name'] == 'closed_no_potential' || $sval['name'] == 'closed_participant_withdrew' ) {
                            $status_options[$sval['value']] = $this->createElement( 'radio', null,
                                                                                    ts('Project Status'), 
                                                                                    $sval['label'], 
                                                                                    $sval['value'] );
                        }
                    }                    
                }
            }
            //Add Radios to Group
            $this->addGroup( $status_options, 'project_details_status', ts('Project Status'));
            $this->addFormRule( array('Efficiency_Form_AddApplicant_Projectdetails', 'formRule' ) ); 
        }
        parent::buildQuickForm( );         
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
    static function formRule( $params, $files, $self) {
        $error = $errors = array();
      
        if ( !( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) ) {
     
            $retrofit_completed  =  $Retrofit  =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'retrofit_completed' );
            $template  =& CRM_Core_Smarty::singleton( );     
            $measuresDate = $template->get_template_vars( 'rows' );
            $dateFields    = array('xm_workorder_issued', 'xm_installed', 'xm_verified', 'xm_pay_authorized');
            foreach ( $measuresDate as $keys => $fields ) {
                foreach ( $dateFields as $values ) {
                    if ( !CRM_Utils_Array::value($values, $measuresDate[$keys]) ) {
                        $flagDate = 1;
                    }
                }
            }
            foreach($params as $key =>$value){
                if ( stristr($key, $retrofit_completed) && $flagDate ) {
                    $flagDateFinal = 1;
                }
            }
                      
            require_once "CRM/Core/DAO/EntityFile.php";
                        
            foreach ( $params as $key =>$value ) {
                if ( stristr( $key, $Retrofit ) ) {
                    $retrofit_completed = $value;
                    break;
                }
            }
            ( $retrofit_completed ) ? $retrofit_flag = 0 : $retrofit_flag = 1;
            
            $measuresOther  = Efficiency_BAO_Export::_getCustomData( $params['cid'],'gcc_measures_other','entity_id' ) ;
        
            if ( $retrofit_completed ){
            
                $flagPartAB = $flagLLRelease = $flagLDC = $flagEnbridge  = $flagP = $flagL = '';
                $bld = 0;
                require_once "CRM/Core/DAO/File.php";
                require_once 'Efficiency/Form/AddApplicant/Files.php';
                // File Information in filesRows Array
                $entityFileDAO            =& new CRM_Core_DAO_EntityFile();
                $entityFileDAO->entity_id = $params['cid'];
                $entityFileDAO->find( );
               
                //get all file names which are uploaded
                while ( $entityFileDAO->fetch() ) {
                    $fileDAO =& new CRM_Core_DAO_File();
                    $fileDAO->id = $entityFileDAO->file_id;
                    if ( $fileDAO->find(true) ) {
                        $fileName = Efficiency_Form_AddApplicant_Files::getFileName($fileDAO->uri);
                        if ( stristr($fileName, '.bld') ) {
                            $bld ++;
                        }                
                        if ( stristr( $fileName, 'PartAB' ) ) 
                            $flagPartAB       = true;
                        if ( stristr( $fileName, 'LLRelease' ) ) 
                            $flagLLRelease    = true;
                    }
                }            
                      
                //$status = Efficiency_BAO_Applicant::getAppStatus( $params['cid'] );
        
                //check funder value is set as Enbridge or not
        
                $measures    = Efficiency_BAO_Export::_getCustomValues( $params['cid'], 'gcc_measures');
          
                foreach( $measures as $key => $value ){
            
                    if ( stristr( $value['funder'],'LDC' ) )
                        $flagLDC = true;
                    if ( stristr( $value['funder'],'Enbridge' ) )
                        $flagEnbridge = true;
            
                }
                /*** Check if two BLD files present | Extended measures dates START***/
                if ( $flagDateFinal && $retrofit_completed && $flagEnbridge ) {
                    $error[] = "Retrofit dates incomplete"; 
                }
                if ( $bld < 2 && $retrofit_completed && $flagEnbridge && $flagDateFinal ) {
                    $error[] = "Two BLD files needed";
                }
                /*** Check if two BLD files present | | Extended measures dates END***/

                //check Landloard name is set or not
                $lparams = array( 'contact_id' => $params['cid'],
                                  'id'         => $params['cid'] );
                require_once "CRM/Contact/BAO/Contact.php";
                $landloard = NULL;
                CRM_Contact_BAO_Contact::retrieve( $lparams, $defaults  );
                if (is_array($defaults)) {
                    $relationship = CRM_Utils_Array::value( 'relationship', $defaults );
                    if ( is_array($relationship) ){
                        $landlordRelID = CRM_Core_DAO::getFieldValue( 'CRM_Contact_DAO_RelationshipType',
                                                                      'Landlord of', 'id', 'name_a_b' );
                        foreach($relationship['data'] as $key => $val) {
                            if($val['relationship_type_id'] == $landlordRelID){
                                empty($val['name'])?$landloard = $val['display_name']:$landloard =  $val['name']; 
                            }
                        }
                    } 
                }
            
                //check that file is having string name as PartAB if funder value is set as Enbridge
      
                if ( $flagEnbridge ) { 
                    if ( !$flagPartAB ){
                        $flagP = 1;
               
                    }
                }
 
                // Retrieve Tenure value for applicant
                $tenure = Efficiency_BAO_Applicant::getFieldValue( 'gcc_applicant', 'tenure', $params['cid'] );

                //check that file is having string name as LLRelease if Landloard name is set
                if ( !empty( $landloard ) ){ 
                    // Check that flagLLRelease for missing LLRelease file is set & tenure is rental
                    if ( !$flagLLRelease && $tenure == 'rental' ) {
                        $flagL = 1;
                    }
                }
                
                if ( $flagL ) {
                    $error[] = 'Landlord release file missing' ;
                }

                if ( $flagP ) {
                    $error[] = "PartAB file missing";
                }
                if ( $error ) {
                    foreach ( $error as $key => $value ) {
                        ( $key == 0 ) ? $errors['_qf_default'] = $value : $errors['_qf_default'] .= '<li>' . $value;
                    }
                }
            }
            // exit;
            return $errors;
        }
    
        if ( array_key_exists( '_qf_Projectdetails_upload', $params ) ) {
            
            /*** Validation for Set Status - Start ***/
            $app_status = Efficiency_BAO_Applicant::getFieldValue( 'gcc_applicant', 'auto_status', $params['cid'] );
            
            if ( isset( $params['project_details_status'] ) && $app_status ) {
                if ( $app_status > $params['project_details_status'] ) {
                    $error['project_details_status'] = 'Cannot set status of lower priority than current status';
                }
            }
            /*** Validation for Set Status - End ***/
            
        }
        
        return $error;

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
        if ( $buttonName == '_qf_Projectdetails_submit' ) {

            if ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) {

                $job_completed_id = CRM_Core_BAO_CustomField::getCustomFieldID( 'retrofit_completed' );
                $job_completed_value = null;
                
                if ( $job_completed_id && $params ) {
                    foreach ( $params as $key => $val ) {
                        if ( strstr( $key, "custom_{$job_completed_id}" ) ) {
                            $job_completed_value = $val;
                        }
                    }
                }

                if( $job_completed_value ) {
                    // Set Ready for QA status for applicant in auto_status column in gcc_applicant table
                    $ready_for_QA_opt_id = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                        'ready_for_QA', 'value', 'name' );
                    if ( $ready_for_QA_opt_id ) {
                        Efficiency_BAO_Applicant::setFieldValue( 'gcc_applicant', 'auto_status', 
                                                                 $params['cid'] , $ready_for_QA_opt_id );
                    }
                }
            }

            CRM_Core_BAO_CustomValueTable::postProcess( $params,
                                                        $fields,
                                                        'gcc_retrofit',
                                                        $params['cid'],
                                                        'Individual' );

            CRM_Core_Session::setStatus( ts(" Information Updated Successfully. "));

            // Set Status in auto_status in gcc_applicant table
            if ( isset( $params['cid'] ) ) {
                if( $job_completed_value ) {   
                    Efficiency_BAO_Applicant::setStatus( $params['cid'] , false );
                } else {
                    Efficiency_BAO_Applicant::setStatus( $params['cid'] , true );
                }
            }
            
        } else if ( $buttonName == '_qf_Projectdetails_upload' && isset( $params['project_details_status'] ) ) {
            if ( isset( $params['cid'] ) ) {

                if ( isset( $params['project_details_status'] ) ) {
                    $updated_rows = Efficiency_BAO_Applicant::setFieldValue( 'gcc_applicant' , 'auto_status', 
                                                                             $params['cid']  , 
                                                                             $params['project_details_status']
                                                                             );
                    
                    // Set status if Automatic option is selected
                    if ( $params['project_details_status'] == 0 ) {
                        Efficiency_BAO_Applicant::setStatus( $params['cid'] , true );
                    }

                }
                
                $cid = $params['cid'];
                $class = strtolower( CRM_Utils_String::getClassName( $this->_name ) );
                CRM_Utils_System::redirect( CRM_Utils_System::url( "civicrm/efficiency/applicant/{$class}/view",
                                                                   "reset=1&action=view&cid={$cid}".
                                                                   "&selectedChild={$class}" ) );
            }
            
        } else {
            return;
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
        return ts('Measures');
    }
    
}

?>

