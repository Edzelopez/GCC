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

require_once 'api/v3/Relationship.php';
require_once 'api/v3/Phone.php';
require_once 'api/v3/Contact.php';
require_once 'api/v3/Email.php';
require_once 'api/v3/Address.php';
require_once 'CRM/Core/Form.php';
require_once 'CRM/Core/BAO/CustomValueTable.php';
require_once 'CRM/Contact/BAO/Contact.php';
require_once 'Efficiency/BAO/Applicant.php';
require_once 'CRM/Core/BAO/PrevNextCache.php'; 
require_once 'CRM/Core/DAO.php';
require_once 'Efficiency/BAO/Applicant.php';
require_once 'CRM/Core/BAO/Cache.php';
/**
 * This class generates form components for processing Event  
 * 
 */
class Efficiency_Form_AddApplicant extends CRM_Core_Form
{
    
    protected $_first = false;
    
    /** 
     * Function to set variables up before form is built 
     *                                                           
     * @return void 
     * @access public 
     */ 
    function preProcess( ) 
    {
        global $base_url;
        
        $session = CRM_Core_Session::singleton();
        $this->_uid         = $session->get('userID');
        $this->_applicantId = CRM_Utils_Request::retrieve( 'cid', 'Positive', $this, false, 0, 'REQUEST' );
        $this->_contextView = CRM_Utils_Request::retrieve( 'context', 'String', $this, false, 0, 'REQUEST' );

        $this->_show        = CRM_Utils_Request::retrieve( 'show', 'Positive', $this, false, 0, 'REQUEST');
        $this->_measuresID  = CRM_Utils_Request::retrieve( 'mid', 'Positive', $this, false, 0, 'REQUEST');
        
        if ( ! $this->_measuresID ) {
            $this->_measuresID = $this->get( 'mid' );
        }
        
        if ( ! $this->_applicantId ) {
            $this->_applicantId = $this->get( 'cid' );
        }
        
        if ( ! $this->_contextView ) {
            $this->_contextView = $this->get( 'context' );
        }
        
        if ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) {
            $this->_isElectric = CIVICRM_EFFICIENCY_ELECTRIC;            
            $this->_fileName = 'FAST';
        } else {
            $this->_isElectric = 0;
            $this->_fileName = 'FAT';
        }
        $this->assign( 'iselectric', $this->_isElectric );
        $this->assign( 'fileName', $this->_fileName );
        
        $this->_action = CRM_Utils_Request::retrieve('action', 'String', $this, false, 'update', 'REQUEST' );
        $this->assign( 'action', $this->_action );
        
        $args = explode( '/', $_GET['q'] );
        $this->_actionString = array_pop($args);             
        $this->assign( 'actionString', $this->_actionString );

        //FIXME: add applicant not found check
        
        $this->add( 'hidden', 'cid', $this->_applicantId );

        $this->auditorRelID  = CRM_Core_DAO::getFieldValue( 'CRM_Contact_DAO_RelationshipType', 'Auditor for', 'id', 'name_a_b' );
        $this->retrofitRelID = CRM_Core_DAO::getFieldValue( 'CRM_Contact_DAO_RelationshipType', 'Retrofit for', 'id', 'name_a_b' );
        $this->landlordRelID = CRM_Core_DAO::getFieldValue( 'CRM_Contact_DAO_RelationshipType', 'Landlord of', 'id', 'name_a_b' );
        
        /*** Delete contact on Cancel button when actionString is add ***/
        if ( $this->_actionString == 'add' ) {
            $cancelAddApplicantUrl = "$base_url/civicrm/efficiency/applicant/add?reset=1&action=delete&cid={$this->_applicantId}";
            $this->assign( 'cancelAddApplicantUrl', $cancelAddApplicantUrl );
        }
        /*** Delete contact on Cancel button when actionString is add ***/
            
        // If action is delete & actionString is add call function to delete contact & redirect 
        if ( $this->_action == 8 && $this->_actionString == 'add' ) {
            self::cancelAddApplicant( $this->_applicantId, $this->_actionString );
        }
        
        // To get FileIdentifire of selected Applicant.
        if ( $this->_applicantId && $this->_uid ) {
            $this->_fid   = Efficiency_BAO_Applicant::getFieldValue( 'gcc_applicant', 'file_identifier', $this->_applicantId );

            // Retrieve current user's last selected list participant form preferences from cache table
            $listCache = CRM_Core_BAO_Cache::getItem( "prevnext_{$this->_uid}",
                                                      'Efficiency_Form_Search_Custom_ListParticipant_prevnext',
                                                      null );
            
            $where = 1;
            /*
            if ( $listCache == 'status' ) {
                // Retrieve current status of the applicant
                $_status = Efficiency_BAO_Applicant::getFieldValue( 'gcc_applicant', 'auto_status', $this->_applicantId );
                $where   = " data IN ( $_status  )";
            } else {
                $where   = " data = 'no_status' ";
            }
            */

            /*** Previous / Next - Start ***/
            //Retrieve previous & next participants from previuous_next_cache table
            $null = null;
            
            $pos = CRM_Core_BAO_PrevNextCache::getPositions( "list_participant_$this->_uid",
                                                             $this->_applicantId,
                                                             $this->_uid, 
                                                             $null,
                                                             null,
                                                             $where );

            $prev['prev'] = $next['next'] = null;
            foreach ( array( 'prev', 'next' ) as $position ) {            
                if ( !empty( $pos[$position] ) ) {
                    if ( $pos[$position]['id1'] ) {
                        $urlParam = "cid={$pos[$position]['id1']}&reset=1";
                        if ( array_key_exists( $position , $prev ) ) 
                            {
                                // Build Previous url
                                $prev['prev'] = CRM_Utils_system::url( 'civicrm/efficiency/applicant/view', $urlParam );
                                $prev['prev'] = "<a class='previous' href='{$prev['prev']}'><img src='".$base_url."/sites/all/modules/civicrm/css/left.png'/></a>";
                            } 
                        else if ( array_key_exists( $position , $next ) ) 
                            {
                                // Build Next url
                                $next['next'] = CRM_Utils_system::url( 'civicrm/efficiency/applicant/view', $urlParam );
                                $next['next'] = "<a class='next' href='{$next['next']}'><img src='".$base_url."/sites/all/modules/civicrm/css/right.png'/></a>";
                            }
                    }
                }
            }
            
            $title = "<h3 class='applicant_title'>".Efficiency_BAO_Applicant::getFieldValue( 'gcc_applicant' ,
                                                                     'file_identifier',
                                                                     $this->_applicantId ).' / '
                           .CRM_Core_DAO::getFieldValue( 'CRM_Contact_DAO_Contact',
                                                         $this->_applicantId,
                                                         'display_name',
                                                         'id' ) . 
                     ' : [ ' .Efficiency_BAO_Applicant::getStatusLabel( $this->_applicantId ) . ' ]'
                     ."</h3>";
            
            // Set Title with Previous / Next Links
            if ( isset( $prev['prev'] ) && isset( $next['next'] ) ) {
                CRM_Utils_System::setTitle( $title."<div class='prev_next' align='right' style='position: relative; 
                                            float: right; font-size:15px !important; padding-bottom:10px;'>"
                                            .$prev['prev']."    ".$next['next']."</div>" );                
            } elseif ( isset( $prev['prev'] ) ) {
                CRM_Utils_System::setTitle( $title."<div class='prev_next' align='right' style='position: relative;
                                            float: right; font-size:15px !important; padding-bottom:10px;'>"
                                            .$prev['prev']."</div>" );
            } elseif ( isset( $next['next'] ) ) {
                CRM_Utils_System::setTitle( $title."<div class='prev_next' align='right' style='position: relative; 
                                            float: right; font-size:15px !important; padding-bottom:10px;'>"
                                            .$next['next']."</div>" );
            } else {
                CRM_Utils_System::setTitle( $title );

            }
            /*** Previous / Next - End ***/
        } else {
            $title = "<h3 class='applicant_title'>Add Participant</h3>";
            CRM_Utils_System::setTitle( $title );
        }

        //FIXME: add subtype check of applicant
        
        $session =& CRM_Core_Session::singleton( );
        $this->_loggedInUserID = $session->get('userID');
        $this->_role = strtolower(CRM_Contact_BAO_Contact::getContactSubType( $this->_loggedInUserID, "," ));

        $groupGetparams = array('contact_id' => $this->_loggedInUserID,
                                'version' => 3,);
       
        $result = civicrm_api( 'group_contact','get',$groupGetparams );
        
        if(array_key_exists('id', $result)){
                $this->_loggedInUserGroupID = $result['values'][$result['id']]['group_id'];
                $this->_loggedInUserGroupName = $result['values'][$result['id']]['title'];
            }
        $Region  =  'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID( 'Region' );
        $customParams =array(
                             'version'  => 3,
                             'entityID' => $this->_loggedInUserID,
                             $Region    => 1
                             );
        require_once 'CRM/Core/BAO/CustomValueTable.php';
        $value = CRM_Core_BAO_CustomValueTable::getValues( $customParams );

        if(array_key_exists($Region, $value)){
            $this->_region = $value[$Region];
        }


        /*** Check if force = 1 build query with current user's last selected form preferences - Start ***/
       
        if ( $this->_applicantId ) {
            // Retrieve current user's last selected form preferences from cache table
            $data = CRM_Core_BAO_Cache::getItem( "gcc_refresh_customer_{$this->_applicantId}",
                                                 'Efficiency_Form_AddApplicant_Files', null );
        }
        if ( $data ) {
            $this->assign('refreshCustomer',$data);
        }
        /*** Check if force = 1 build query with current user's last selected form preferences - End ***/
                       
        require_once 'Efficiency/Form/AddApplicant/TabHeader.php';
        Efficiency_Form_AddApplicant_TabHeader::build( $this );
    }
    
    /** 
     * Function to build the form 
     * 
     * @return None 
     * @access public 
     */ 
    public function buildQuickForm( )  
    { 
        $className = CRM_Utils_String::getClassName( $this->_name );
        $buttons   = array( );
        
        if ( $className != "Note" && $this->_actionString =='add' ) {
            $buttons[] = array ( 'type'      => 'upload',
                                 'name'      => ts('Save & Next'),
                                 'spacing'   => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                                 'isDefault' => true   );
            $buttons[] = array ( 'type'      => 'cancel',
                                 'name'      => ts('Cancel') );
        } else if ( $this->_actionString == 'update' ) {
            if ( $className != "Projectdetails" && $className != "Assignaudit" ){
                $buttons[] = array ( 'type'      => 'upload',
                                     'name'      => ts('Save & Next'),
                                     'spacing'   => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                                     'isDefault' => true   );
            } else {
                $buttons[] = array ( 'type'      => 'upload',
                                     'name'      => ts('Set Status'),
                                     'spacing'   => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                                     'isDefault' => true   );
                
            }
            
            if ( $className == "Assignaudit" ) {
                $buttons[] = array ( 'type'           => 'submit',
                                     'name'           => ts('Assign'),
                                     'spacing'        => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' );
                
                //Add Button to save QA fields only for electric version
                if ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) {
                    $buttons[] =  array ( 'type'      => 'submit',
                                          'name'      => ts('Save QA'),
                                          'subName'   => 'saveqa' ,
                                          'spacing'   => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' );
                }

            } else {
                $buttons[] = array ( 'type'      => 'submit',
                                     'name'      => ts('Save & View'),
                                     'spacing'   => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' );
            }
            
            $buttons[] = array ( 'type'      => 'cancel',
                                 'name'      => ts('Cancel') );
            
        } else if ( ( $className == "Projectdetails" || $className == "Assignaudit" ) && $this->_actionString == 'view' ) {
            
            $buttons[] = array ( 'type'      => 'upload',
                                 'name'      => ts('Set Status'),
                                 'spacing'   => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                                 'isDefault' => true   );
            
        } 
        $this->addButtons( $buttons );
    }
    
    function endPostProcess( )
    {
        $button    = $this->controller->getButtonName('submit');
        $className = CRM_Utils_String::getClassName( $this->_name );
        $nextTab   = Efficiency_Form_AddApplicant_TabHeader::getNextSubPage( $this, $className );
        $nextTab   = strtolower($nextTab);
        //$qfKey = $this->get( 'qfKey' );
        Efficiency_BAO_Applicant::setStatus( $this->_applicantId );
        
        // make submit buttons keep the current working tab opened.
        if ( $className == "Note" && $this->_actionString == "add" && $this->_action & CRM_Core_Action::UPDATE ) {
            CRM_Utils_System::redirect( CRM_Utils_System::url( "civicrm/efficiency/applicant/view",
                                                               "reset=1&action=view&cid={$this->_applicantId}" ) );
            
        } else if ( $this->_action & CRM_Core_Action::UPDATE ) {
            
            if ( $this->controller->getButtonName('submit') == "_qf_{$className}_upload" ) {
                if ( $className != "Projectdetails" ){
                    CRM_Utils_System::redirect( CRM_Utils_System::url( "civicrm/efficiency/applicant/{$nextTab}/{$this->_actionString}",
                                                                       "reset=1&action={$this->_actionString}&cid={$this->_applicantId}" ) );
                }else{
                    $class = strtolower($className);
                    CRM_Utils_System::redirect( CRM_Utils_System::url( "civicrm/efficiency/applicant/{$class}/view",
                                                                       "reset=1&action=view&cid={$this->_applicantId}&selectedChild={$class}" ) );
                    
                }
            } else if ( $this->controller->getButtonName('submit') == "_qf_{$className}_submit") {
                $class = strtolower($className);
                CRM_Utils_System::redirect( CRM_Utils_System::url( "civicrm/efficiency/applicant/{$class}/view",
                                                                   "reset=1&action=view&cid={$this->_applicantId}&selectedChild={$class}" ) );
            } else if ( $this->controller->getButtonName('submit') == "_qf_{$className}_submit_saveqa") {
                $class = strtolower($className);
                CRM_Utils_System::redirect( CRM_Utils_System::url( "civicrm/efficiency/applicant/{$class}/view",
                                                                   "reset=1&action=view&cid={$this->_applicantId}&selectedChild={$class}" ) );
            }
        }
        
    }
    
    function getTemplateFileName( ) {
        if ( $this->controller->getPrint( ) == CRM_Core_Smarty::PRINT_NOFORM || 
             $this->_applicantId <= 0 ||
             ( $this->_action & CRM_Core_Action::DELETE ) ) {
            return parent::getTemplateFileName( );
        } else {
            return 'CRM/common/TabHeader.tpl';
        }
    }


    
    /**
     * Function to obtain region of a given contact
     *
     * @return string
     * @access public
     */
    static function getRegion( $contactID ) 
    {
        $groupGetparams = array('contact_id' => $contactID,
                                'version' => 3,);
        $result = civicrm_api( 'group_contact','get',$groupGetparams );
       
        if(array_key_exists('id', $result)){
            $region = $result['values'][$result['id']]['title'];
        }else{
            $region = '';
        }
        return $region;
    }
    

 /**
     * Function to obtain the contacts who belong to a given region
     *
     * @return array
     * @access public
     */
    static function getRegionContacts( $region ) 
    {
        $role = '';
        $sql = 'select entity_id from civicrm_value_gcc_custom_group where region ="'.$region .'"';
        $dao =& CRM_Core_DAO::executeQuery( $sql,
                                            CRM_Core_DAO::$_nullArray );
                 
        $regionContacts = array();
        while ($dao->fetch()) {

           
            $params = array( 
                            'id' =>$dao->entity_id ,
                            'version' => 3,
                             );
            $contact = civicrm_api( 'contact','get',$params );
                  
            if ( array_key_exists( 'contact_sub_type', $contact['values'][$contact['id']] ) ){
                $role = $contact['values'][$contact['id']]['contact_sub_type'];
               
                if (strtolower($role[0]) == 'auditor') {
                    
                    $regionContacts['Auditor'][$dao->entity_id] = $contact['values'][$contact['id']]['display_name'];
                }
                if (strtolower($role[0]) == 'retrofit') {
                    
                    $regionContacts['Retrofit'][$dao->entity_id] = $contact['values'][$contact['id']]['display_name'];
                }
            } 
        }
        
        return $regionContacts;
       
    }
  
    /**
     * Function to cancel add applicant and delete related contact if it is created 
     * @param contact id of the the contact created
     * @param action string to check for. It should be add to delete contact (if present) & redirect to List Participants.
     **/
    function cancelAddApplicant( $contactID, $actionString ) {
        
        if ( $actionString == "add" ) { 

            if ( $contactID ) { 
                $params = array( 
                                'contact_id' => $contactID,
                                'version'    => 3,
                                 );
                
                $result = civicrm_api( 'relationship', 'get', $params );
                if ( !empty( $result['values'] ) ) {
                        foreach ( $result['values'] as $key => $value ) {
                            // Check for contact whose is Landlord of current user
                            if ( $value['relationship_type_id'] == 12 ) {
                                // Delete Contact which is related to current user( Relationship -Landlord of current user )
                                $landlordDeleted = CRM_Contact_BAO_Contact::deleteContact( $value['contact_id_a'], 
                                                                                           false, 1 );
                            }
                        }  

                }
                
                // Delete current user
                $contact_deleted = CRM_Contact_BAO_Contact::deleteContact( $contactID, false, 1 );
            }

            $csid = Efficiency_BAO_Applicant::getListParticipantSearchID();

            if ( $csid ) {
                CRM_Utils_System::redirect( CRM_Utils_System::url( "civicrm/contact/search/custom",
                                                                   "csid=$csid"."&reset=1&force=1" ) );
            }
        }           
    }
}

