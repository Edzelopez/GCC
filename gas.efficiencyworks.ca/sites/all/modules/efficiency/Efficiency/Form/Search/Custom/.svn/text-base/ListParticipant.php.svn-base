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

/**
 * Files required
 */

require_once 'CRM/Contact/Form/Search/Interface.php';
require_once 'CRM/Contact/Form/Search/Custom/Base.php';
require_once 'Efficiency/BAO/Applicant.php';
require_once 'CRM/Core/BAO/PrevNextCache.php';
require_once 'CRM/Core/BAO/Cache.php';
require_once 'CRM/Core/Permission.php';
require_once 'CRM/Utils/Request.php';
require_once 'CRM/Contact/BAO/Group.php';
class Efficiency_Form_Search_Custom_ListParticipant 
      extends CRM_Contact_Form_Search_Custom_Base
implements CRM_Contact_Form_Search_Interface {
    
    protected $_formValues;
    protected $statusOptions;
    public    $_defaultvalues = array();
    function __construct( &$formValues ) {
        $this->_formValues = $formValues;

        /*** Check if current user has administer_qa_status permission - Start ***/
        $administer_qa = false;
        if ( CRM_Core_Permission::check( 'administer_qa_status' ) ) {

            /*** Toggle code - Start ***/
            $action = CRM_Utils_Array::value( 'action', $_GET );
            $cid    = CRM_Utils_Request::retrieve( 'cid', 'Int', CRM_Core_DAO::$_nullObject ); 

            if ( $action && $cid ) 
                Efficiency_BAO_Applicant::toggleStatus( $action, $cid );
            /*** Toggle code - End ***/

            $administer_qa = true;
        }
        /*** Check if current user has administer_qa_status permission - End ***/
        
        /**
         * Define the columns for search result rows
         */
        // Check for electric version
        if ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) {
            if ( $administer_qa ) {
                $this->_columns = array( ts('OPA FileID')                    => 'file_identifier',
                                         ts('Client Name')               => 'client_name',
                                         ts('Status')                    => 'auto_status',
                                         ts('Enrolled')                  => 'date_entered',
                                         ts('QA')                        => 'qa_status',
                                         ts("Days<br>Since<br>Enrolled") => 'days_since_enrolled',
                                         ts("Days<br>Since<br>Audit")    => 'days_since_audited',
                                         ts("Billed")                    => 'billed',
                                         ts("ProjectDetails Status")     => 'Status');
            } else {
                $this->_columns = array( ts('FileID')                    => 'file_identifier',
                                         ts('Client Name')               => 'client_name',
                                         ts('Status')                    => 'auto_status',
                                         ts('Enrolled')                  => 'date_entered',
                                         ts("Days<br>Since<br>Enrolled") => 'days_since_enrolled',
                                         ts("Days<br>Since<br>Audit")    => 'days_since_audited',
                                         ts("Billed")                    => 'billed',
                                         ts("ProjectDetails Status")     => 'Status');
            }
        } else {
            if ( $administer_qa ) {
                $this->_columns = array( ts('File-ID')                   => 'file_identifier',
                                         ts('Client Name')               => 'client_name',
                                         ts('Status')                    => 'auto_status',
                                         ts('Enrolled')                  => 'date_entered',
                                         ts('QA')                        => 'qa_status',
                                         ts("ProjectDetails Status")     => 'Status');
            } else {
                $this->_columns = array( ts('File-ID')                   => 'file_identifier',
                                         ts('Client Name')               => 'client_name',
                                         ts('Status')                    => 'auto_status',
                                         ts('Enrolled')                  => 'date_entered',
                                         ts("ProjectDetails Status")     => 'Status');
            }
        }
        /*** Check if force = 1 build query with current user's last selected form preferences - Start ***/
        $session =& CRM_Core_Session::singleton( );
        $this->_uid = $session->get('userID');
        if ( $this->_uid ) {
            // Retrieve current user's last selected form preferences from cache table
            $data = CRM_Core_BAO_Cache::getItem( "list_participant_{$this->_uid}",
                                                 'Efficiency_Form_Search_Custom_ListParticipant', null );
        }
        if ( $data ) {
            $this->_defaultvalues = $data;
        }
        /*** Check if force = 1 build query with current user's last selected form preferences - End ***/
    }
    
    function buildForm( &$form ) {
        global $user;
        /**
         * You can define a custom title for the search form
         */
        $this->setTitle( $user->name );
                
        /**
         * Define the search form fields here
         */
        // Check for electric version
        if ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) {
            $statusOptions = array(  'applicant'                  =>  'Applicant', 
                                     'new_participant'            =>  'New Participant', 
                                     'audit_assigned'             =>  'Audit Assigned',
                                     'retrofit_pending'           =>  'Retrofit Pending',
                                     'no_potential'               =>  'Closed - No potential',
                                     'close_participant_withdraw' =>  'Closed - Participant Withdrew',
                                     'retrofit_completed'         =>  'Retrofit Completed',
                                     'ready_for_QA'               =>  'Ready for QA',
                                     'report_to_LDC'              =>  'Report to LDC',
                                     'project_completed'          =>  'Project Completed' 
                                     );
        } else {
            $statusOptions = array( 'new_participant'            =>  'New Participant', 
                                    'audit_assigned'             =>  'Audit Assigned',
                                    'retrofit_pending'           =>  'Retrofit Pending',
                                    'no_potential'               =>  'Closed - No potential',
                                    'close_participant_withdraw' =>  'Closed - Participant Withdrew',
                                    'retrofit_completed'         =>  'Retrofit Completed',
                                    'project_completed'          =>  'Project Completed'
                                    );
            
        }                               

        $this->_statusOptions = $statusOptions;
        $form->addElement('text', 'file_identifier' , ts('File ID') , array('size' => 25));
        $elements = array ( 'file_identifier' );

        $count=0;
        foreach( $statusOptions as $key => $value ) {
            $count++;
            if ( $count <= 1 ) { 
                $form->addElement('checkbox', 'status_'.$key, 'Show Records', $value);
            } else {
                $form->addElement('checkbox', 'status_'.$key, null, $value);
            }
            $elements[] = 'status_'.$key;
        }
        
        /**
         * If you are using the sample template, this array tells the template fields to render
         * for the search form.
         */
        $form->assign( 'elements', $elements );
        
        // Set default fields as per current user's last form preference
        if ( $this->_defaultvalues ) {
            $form->setDefaults( $this->_defaultvalues );
        }

    }
    
    /**
     * Define the smarty template used to layout the search form and results listings.
     */
    function templateFile( ) {
        $template = CRM_Core_Smarty::singleton( );
        $vars =& $template->_tpl_vars['rows'];

        // Set/add the electric key in template object to identify electric version in tpl file
        if ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) {
            $template->_tpl_vars['electric'] = true;
        }

        if ( $vars ) {
            $this->alter_rows( $vars );
        }
        return 'Efficiency/Form/Search/Custom/ListParticipant.tpl';
    }

    
    /**
     * Construct the search query
     */
    function all( $offset = 0, $rowcount = 0, $sort = null, $includeContactIDs = false, $onlyIDs = false ) {        

        // if ( $onlyIDs ) {
        //     $select  = 'contact_a.id as contact_id'; 
        // } else {
                    
        /*** Check if current user has administer_qa_status permission - Start ***/
        $administer_qa = false;
        if ( CRM_Core_Permission::check( 'administer_qa_status' ) ) {
            $administer_qa = true;
        }
        /*** Check if current user has administer_qa_status permission - End ***/

        // Check for electric version
        if ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) {
            
            if ( $administer_qa ) {
                $select = " DISTINCT( c.id )                           AS contact_id,
                            c.hash,
                            ga.file_identifier,
                            display_name                               AS 'client_name',
                            CONCAT( `first_name`, ' ', `last_name` )   AS '_client_name',
                            ga.Status,
                            ga.date_entered,
                            ga.qa_status,
                            ga.auto_status,
                            DATEDIFF( curdate(), `date_entered` )      AS 'days_since_enrolled',
                            DATEDIFF( curdate(), `audit_completed` )   AS 'days_since_audited',
                            DATE_FORMAT( `retrofit_invoiced`, '%b-%y') AS 'billed'
                          ";
            } else {
                $select = " DISTINCT( c.id )                           AS contact_id,
                            c.hash,
                            ga.file_identifier,
                            display_name                               AS 'client_name',
                            CONCAT( `first_name`, ' ', `last_name` )   AS '_client_name',
                            ga.Status,
                            ga.date_entered,
                            ga.auto_status,
                            DATEDIFF( curdate(), `date_entered` )      AS 'days_since_enrolled',
                            DATEDIFF( curdate(), `audit_completed` )   AS 'days_since_audited',
                            DATE_FORMAT( 'retrofit_invoiced', '%b-%y') AS 'billed'
                          ";
            }
        } else {
            if ( $administer_qa ) {
                $select = " DISTINCT( c.id )                             AS contact_id,
                            c.hash,
                            ga.file_identifier,
                            display_name                             AS 'client_name',
                            CONCAT( `first_name`, ' ', `last_name` ) AS '_client_name',
                            ga.Status,
                            ga.date_entered,
                            ga.qa_status,
                            ga.auto_status
                          ";
            } else {
                $select = " DISTINCT( c.id )                             AS contact_id,
                            c.hash,
                            ga.file_identifier,
                            display_name                             AS 'client_name',
                            CONCAT( `first_name`, ' ', `last_name` ) AS '_client_name',
                            ga.Status,
                            ga.date_entered,
                            ga.auto_status
                          ";
            }
        }
        
        /*** Check if force = 1 build query with current user's last selected form preferences - Start ***/
        // Check if force is set in url
        $force = CRM_Utils_Request::retrieve( 'force', 'Int', CRM_Core_DAO::$_nullObject ); 

        if ( $force ) {
            $session =& CRM_Core_Session::singleton( );
            $this->_uid = $session->get('userID');
            if ( $this->_uid ) {
                // Retrieve current user's last selected form preferences from cache table
                $data = CRM_Core_BAO_Cache::getItem( "list_participant_{$this->_uid}",
                                                     'Efficiency_Form_Search_Custom_ListParticipant', null );
            }

            if ( $data ) {
                // if data is returend set _formValues
                $this->_formValues = $data;
            }
        }
        /*** Check if force = 1 build query with current user's last selected form preferences - End ***/

        $from  = $this->from( );
            
        $where = $this->where( $includeContactIDs ); 
        if ( ! empty( $where ) ) {
            $where = "WHERE $where ";
        }        
        
        $subquery = null;
        
        $new_participant             = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                    'new_participant', 
                                                                    'value', 'name' );
        $audit_assigned              = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                    'audit_assigned', 
                                                                    'value', 'name' );
        $retrofit_pending            = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                    'retrofit_pending', 
                                                                    'value', 'name' );
        $closed_no_potential         = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                    'closed_no_potential', 
                                                                    'value', 'name' );
        $closed_participant_withdrew = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                    'closed_participant_withdrew', 
                                                                    'value', 'name' );
        $retrofit_completed          = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                    'retrofit_completed', 
                                                                    'value', 'name' );
        $project_completed           = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                    'project_completed', 
                                                                    'value', 'name' );
        
        if ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) { 
            $applicant     = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                          'applicant', 
                                                          'value', 'name' );	
            $report_to_LDC = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                          'report_to_LDC', 
                                                          'value', 'name' );
            $ready_for_QA  = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                          'ready_for_QA', 
                                                          'value', 'name' );
        }
        
        //Check if hide rows checkbox(es) is(are) checked to hide rows
        if( isset( $this->_formValues['status_project_completed'] ) || isset( $this->_formValues['status_retrofit_completed'] ) || isset( $this->_formValues['status_retrofit_pending'] ) || isset( $this->_formValues['status_new_participant'] ) || isset( $this->_formValues['status_audit_assigned'] ) || isset( $this->_formValues['status_no_potential'] ) || isset( $this->_formValues['status_close_participant_withdraw'] ) || isset( $this->_formValues['status_applicant'] ) || isset( $this->_formValues['status_ready_for_QA'] ) || isset( $this->_formValues['status_report_to_LDC'] ) ) {
            
            $sub_from = "LEFT JOIN gcc_applicant ga ON ( c.id = ga.entity_id )";

            //Check if Project Completed Status is checked
            if ( isset( $this->_formValues['status_project_completed'] ) ) {
                $sub_where['project_completed'] = " ( ga.auto_status = $project_completed ) ";
            }

            //Check if Retrofit Completed Status is checked
            if ( isset( $this->_formValues['status_retrofit_completed'] ) ) {
                $sub_where['retrofit_completed'] = " ( ga.auto_status = $retrofit_completed ) ";
            }

            //Check if Retrofit Pending Status is checked
            if ( isset( $this->_formValues['status_retrofit_pending'] ) ) {

                $sub_where['retrofit_pending'] = " ( ga.auto_status = $retrofit_pending ) ";
            }
            
            //Check if Audit Assigned Status is checked
            if ( isset( $this->_formValues['status_audit_assigned'] ) ) {
                $sub_where['audit_assigned'] = " ( ga.auto_status = $audit_assigned ) ";
            }
            
            //Check if Closed - No potential is checked
            if ( isset( $this->_formValues['status_no_potential'] ) ) {
                $sub_where['no_potential'] = " ( ga.auto_status = $closed_no_potential ) ";
            }

            //Check if Closed - Participant withdrew is checked
            if ( isset( $this->_formValues['status_close_participant_withdraw'] ) ) {

                $sub_where['close_participant_withdraw']     = " ( ga.auto_status = $closed_participant_withdrew ) ";
            }

            //Check if New Participant Status is checked
            if ( isset( $this->_formValues['status_new_participant'] ) ) {
                
                $sub_where[] = " ( ga.auto_status = $new_participant ) ";
            }            
            
            //Check if Applicant is checked
            if ( isset( $this->_formValues['status_applicant'] ) ) {

                $sub_where[] = " ( ga.auto_status = $applicant ) ";
            }
            
            //Check if Ready for QA is checked
            if ( isset( $this->_formValues['status_ready_for_QA'] ) ) {

                $sub_where[] = " ( ga.auto_status = $ready_for_QA ) ";
            }
            
            //Check if Report to LDC is checked
            if ( isset( $this->_formValues['status_report_to_LDC'] ) ) {

                $sub_where[] = " ( ga.auto_status = $report_to_LDC ) ";
            }

            if ( $sub_where )
                $sub_where = implode( ' OR ', $sub_where ); // implode where clause with OR
            
            //Build sub query
            $subquery  = " AND c.id IN ( SELECT c.id from civicrm_contact c $sub_from WHERE $sub_where )" ;
            
        }

        if ( $subquery ) {
            //Add subquery if any of the checkboxes for status are checked
            $sql = " SELECT $select FROM $from $where $subquery GROUP BY c.id";
        } else {
            $sql = " SELECT $select FROM $from $where GROUP BY c.id";
        }

        if ( ! empty( $sort ) ) {
            if ( is_string( $sort ) ) {
                $sql .= " ORDER BY $sort ";
            } else {
                $sql .= ' ORDER BY ' . trim( $sort->orderBy() );
            }
        } else {
            $sql .= ' ORDER BY ga.file_identifier DESC ';
        }

        if ( $rowcount > 0 && $offset >= 0 ) {
            $sql .= " LIMIT $offset, $rowcount ";
        }        

        /*** Delete & Insert records for current user in cache table - Start ***/
        $session =& CRM_Core_Session::singleton( );
        $this->_uid = $session->get('userID');
        $formvalues = $this->_formValues;
        unset( $formvalues['qfKey'] );        
        
        // delete current user's last form preferences from cache table
        CRM_Core_BAO_Cache::deleteGroup( "list_participant_{$this->_uid}" );
        
        // Insert current user's last selected form preferences into cache table
        CRM_Core_BAO_Cache::setItem( $formvalues, "list_participant_{$this->_uid}", 
                                     'Efficiency_Form_Search_Custom_ListParticipant', null );
        /*** Delete & Insert records for current user in cache table - End ***/
        
        /**
         * Call prevnextData function to insert entries 
         * in civicrm_prevnext_cache table 
         * for previous next buttons
         **/
        $this->prevnextData( $sql );

        return $sql;

    }
    
	/**
     * FROM Clause
     **/
    function from( )
    {
        $session =& CRM_Core_Session::singleton( );
        
        //Retreive current user's userid
        $this->_uid = $session->get('userID');
        $subType    = CRM_Contact_BAO_Contact::getContactSubType( $this->_uid, "," );

        if ( $subType == 'Auditor' || $subType == 'Retrofit' ) {
            return " civicrm_contact c 
                       JOIN gcc_applicant ga 
                           ON ( c.id = ga.entity_id ) 
                       JOIN civicrm_relationship r
                           ON ( c.id = r.contact_id_b )
                       LEFT JOIN gcc_measures_other gmo 
                           ON ( c.id = gmo.entity_id )
                       LEFT JOIN gcc_misc gms 
                           ON ( c.id = gms.entity_id ) ";
        } else if ( $subType == 'CSR' || $subType == 'Outreach' ) {
            return " civicrm_contact c
                        JOIN civicrm_group_contact gc
                            ON ( c.id = gc.contact_id )
                        JOIN civicrm_group g
                            ON ( gc.group_id = g.id )                         
                        JOIN gcc_applicant ga 
                            ON ( c.id = ga.entity_id )
                        LEFT JOIN gcc_measures_other gmo 
                           ON ( c.id = gmo.entity_id )
                        LEFT JOIN gcc_misc gms 
                           ON ( c.id = gms.entity_id ) "; 
        } else {
            return " civicrm_contact c                         
                        JOIN gcc_applicant ga 
                            ON ( c.id = ga.entity_id )
                        LEFT JOIN gcc_measures_other gmo 
                           ON ( c.id = gmo.entity_id )
                        LEFT JOIN gcc_misc gms 
                           ON ( c.id = gms.entity_id ) ";        
        }
    }
    
    /**
     * WHERE clause is an array built from any required JOINS 
     * plus conditional filters based on search criteria field values
     **/
    function where( $includeContactIDs = false ) {
        $clauses = array( );

        $session =& CRM_Core_Session::singleton( );
        
        //Retreive current user's userid
        $this->_uid = $session->get('userID');
        $subType    = CRM_Contact_BAO_Contact::getContactSubType( $this->_uid, "," );

        if ( $subType == 'Auditor' || $subType == 'Retrofit' ) {
            $clauses[] = " r.contact_id_a = $this->_uid ";
            if ( $subType == 'Auditor' )
                $RelID  = CRM_Core_DAO::getFieldValue( 'CRM_Contact_DAO_RelationshipType', 
                                                       'Auditor for', 'id', 'name_a_b' );
            else if ( $subType == 'Retrofit' )
                $RelID = CRM_Core_DAO::getFieldValue( 'CRM_Contact_DAO_RelationshipType', 
                                                      'Retrofit for', 'id', 'name_a_b' );

            $clauses[] = " r.relationship_type_id = $RelID ";
            $cluases[] = " r.is_active = 1 ";
            $clauses[] = " r.end_date IS NULL "; // Means Realtionship did not end yet
        } else if ( $subType == 'CSR' || $subType == 'Outreach' ) {
            $regionID     = CRM_Core_BAO_CustomField::getCustomFieldID( 'Region' );         
            $customParams = array(
                                  'version'          => 3,
                                  'entityID'         => $this->_uid,
                                  "custom_$regionID" => 1
                                  );
            require_once 'CRM/Core/BAO/CustomValueTable.php';
            $value        = CRM_Core_BAO_CustomValueTable::getValues( $customParams );            
            if ( isset( $value["custom_$regionID"] ) ) {
                $region   = $value["custom_$regionID"];
                $grpParam = array( 'title' => $region );
                $groupID  = CRM_Contact_BAO_Group::getGroups( $grpParam );

                if ( $groupID[0]->id ) {
                    $clauses[] = " gc.group_id = {$groupID[0]->id} ";
                    $clauses[] = " gc.status = 'Added' ";
                }                
            }
        } else {
            //Group params array to get the logged in user's group
            // $params = array( 
            //                 'contact_id' => $this->_uid,
            //                 'version'    => 3,
            //                  );
        
            // //Retreive logged in user's groups
            // $result = civicrm_api( 'group_contact','get', $params );
        
            // $loggedinUserGrps          = array();
            // $loggedinUserGrps_imploded = '';

            // if ( isset( $result['values'] ) ) {

            //     foreach ( $result['values'] as $grpKey => $grpVal ) {
            //         $loggedinUserGrps[$grpKey] = $grpVal['group_id'];                         
            //     }
            
            //     if ( $loggedinUserGrps ) {
            //         $loggedinUserGrps_imploded = implode( ",", $loggedinUserGrps );
            //     }

            // }

            // if ( $loggedinUserGrps_imploded ) {

            //     $clauses[] = " gc.group_id IN ( {$loggedinUserGrps_imploded} ) ";
            //     $clauses[] = " gc.status = 'Added' ";
        
            // }
        }
        
        if ( $this->_uid ) {

            $clauses[] = " c.id not in ({$this->_uid}) ";
            $clauses[] = " c.contact_sub_type = 'Applicant' ";
            $clauses[] = " c.first_name IS NOT NULL ";
            $clauses[] = " c.last_name IS NOT NULL ";
        }
        
        if( isset( $this->_formValues['status_project_completed'] ) || isset( $this->_formValues['status_retrofit_completed'] ) || isset( $this->_formValues['status_retrofit_pending'] ) || isset( $this->_formValues['status_new_participant'] ) || isset( $this->_formValues['status_audit_assigned'] ) || isset( $this->_formValues['status_no_potential'] ) || isset( $this->_formValues['status_close_participant_withdraw'] ) || isset( $this->_formValues['status_applicant'] ) || isset( $this->_formValues['status_ready_for_QA'] ) || isset( $this->_formValues['status_report_to_LDC'] ) ) {
                $clauses[] = " ga.auto_status IS NOT NULL ";
        }
        
        //$file_identifier = null;
        if ( isset( $this->_formValues['file_identifier'] ) ) { 
            $file_identifier = $this->_formValues['file_identifier'];
            if ( $file_identifier ) {
                $clauses[] = " ga.file_identifier LIKE '%{$file_identifier}%' ";
            }                   
        }

        $clauses[] = " c.is_deleted = 0 ";
        
        return implode( ' AND ', $clauses );
    }
    
    /**
     * Functions below generally don't need to be modified
     **/
    function count( ) {
        $sql = $this->all( );
        $dao =& CRM_Core_DAO::executeQuery( $sql,
                                            CRM_Core_DAO::$_nullArray );
        return $dao->N;
    }
    
    function contactIDs( $offset = 0, $rowcount = 0, $sort = null ) {
        return $this->all( $offset, $rowcount, $sort,  false, true );
    }
    
    function &columns( ) {                
        return $this->_columns;
    }
    
    function setTitle( $title ) {
        if ( $title ) {
            CRM_Utils_System::setTitle( $title );
        }
    }
    
    function summary( ) {
        return null;
    }

    /**
     * Function to alter rows
     * used for adding new links 
     * & unsetting rows when Hide Rows checkbox selected
     * @param $row containing all rows to be displayed for search
     **/
    function alter_rows( &$row ) {
        if ( $row ) {

            // Cleanup civicrm_prevnext_cache table for list_participant
            //CRM_Core_BAO_PrevNextCache::deleteItem( null, 'list_participant', 'civicrm_contact' );
            
            // delete current user's last form preferences from cache table
            //CRM_Core_BAO_Cache::deleteGroup( "gcc_list_participant_prevnext_{$this->_uid}" );

            /*
             if ( isset( $status_checked ) )
                $status_checked = $status_checked; 
            else 
                $status_checked = false;
            */

            foreach ( $row as $key => $val ) { 
                if ( isset( $row[$key] ) ) {
                    $view_edit_participant_url    = CRM_Utils_System::url('civicrm/efficiency/applicant/view', 
                                                                          'cid='."{$val['contact_id']}".'&reset=1');
                    if ( CRM_Core_Permission::check( 'upload_app_FAST' ) ){
                        $upload_fat_url               = CRM_Utils_System::url('civicrm/efficiency/applicant/import', 
                                                                              'cid='."{$val['contact_id']}".'&reset=1');
                        if ( defined('CIVICRM_EFFICIENCY_ELECTRIC') && CIVICRM_EFFICIENCY_ELECTRIC ) {
                            $fat_fast = 'FAST';
                        } else {
                            $fat_fast = 'FAT';                        
                        }

                        $applicant_links              = "<span><a class='action-item action-item-first' href='".$upload_fat_url."'>Upload {$fat_fast}</a></span>";
                        $row[$key]['action']          = $applicant_links; //add new links in search

                    } else {
                        unset( $row[$key]['action']);
                    }

                        $row[$key]['client_name'] = "<span><a class='action-item action-item-first' href='".$view_edit_participant_url."'>{$row[$key]['client_name']}</a></span>";
                                       
                    if( isset( $val['contact_id'] ) ) {

                        /*** Set value to Status column - Start ***/
                        $row[$key]['auto_status'] =  Efficiency_BAO_Applicant::getStatusLabel( $val['contact_id'] );
                        /*** Set value to Status column - End ***/ 

                        if ( CRM_Core_Permission::check( 'administer_qa_status' ) ) {
                            
                            if ( $row[$key]['qa_status'] == 'Review' ) {
                                $csid = Efficiency_BAO_Applicant::getListParticipantSearchID();
                                $toggleUrl = CRM_Utils_System::url('civicrm/contact/search/custom', 
                                                                   '&csid='.$csid.'&reset=1&force=1'
                                                                   .'&action=toggle&cid='."{$val['contact_id']}");

                                $row[$key]['qa_status'] = "<span><a class='action-item action-item-first' 
                                                             href='".$toggleUrl."'>{$row[$key]['qa_status']}</a>
                                                           </span>";        
                            }                    
                        }
                    }
                    
                    // Check for electric version
                    if ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) {
                        // Retrieve audit_completed date from gcc_measures_other table
                        $audit_completed = array();
                        $audit_completed[$val['contact_id']] = Efficiency_BAO_Applicant::getFieldValue( 'gcc_measures_other' , 'audit_completed', $val['contact_id'] );
                        $todays_date = strtotime( date("Y-m-d H:i:s") ); // get current date
                        if( $audit_completed[$val['contact_id']] ) {
                            $row[$key]['days_since_enrolled'] = "<span class='crm-center'></span>";
                        } else {
                            if ( $val['days_since_enrolled'] >= 21 ) {
                                $row[$key]['days_since_enrolled'] = 
                                    "<span class='crm-psearch'>{$val['days_since_enrolled']}</span>";
                            } else if ( $val['days_since_enrolled'] < 21 ) {
                                $row[$key]['days_since_enrolled'] = 
                                    "<span class='crm-center'>{$val['days_since_enrolled']}</span>";
                            } 
                        }
                        /*** Set Ellapsed days since enrolled date - Start ***/
                        // if ( isset( $row[$key]['date_entered'] ) ) {
                        //     // $date_entered = strtotime( $row[$key]['date_entered'] );                        
                        //     // $days_since_enrolled_days = round( abs( $todays_date - $date_entered ) /60/60/24 );

                        //     // if ( $days_since_enrolled_days >= 21 && empty( $audit_completed ) ) {
                        //     //     $row[$key]['days_since_enrolled'] = 
                        //     //         "<span class='crm-psearch'>$days_since_enrolled_days</span>";
                        //     // } else if ( $days_since_enrolled_days < 21 && empty( $audit_completed ) ) {
                        //     //     $row[$key]['days_since_enrolled'] = 
                        //     //         "<span class='crm-center'>$days_since_enrolled_days</span>";
                        //     // }
                        //     if ( $val['days_since_enrolled'] >= 21 && empty( $audit_completed ) ) {
                        //         $row[$key]['days_since_enrolled'] = 
                        //             "<span class='crm-psearch'></span>";
                        //     } else if ( $val['days_since_enrolled'] < 21 && empty( $audit_completed ) ) {
                        //         $row[$key]['days_since_enrolled'] = 
                        //             "<span class='crm-center'></span>";
                        //     } else {
                        //          $row[$key]['days_since_enrolled'] = 
                        //             "<span class='crm-center'></span>";
                        //     }
                        // }
                        /*** Set Ellapsed days since enrolled date - End ***/

                        /*** Set Ellapsed days since Audited date - Start ***/
                        if ( $row[$key]['auto_status'] == 'Retrofit Pending' ) {
                            // if ( $audit_completed[$val['contact_id']] ) {
                                // $audit_completed         = strtotime( $audit_completed );
                                // $days_since_audited_days = round( abs( $todays_date - $audit_completed ) /60/60/24 );
                            
                                // if ( $days_since_audited_days >= 21 ) {
                                //     $row[$key]['days_since_audited'] = 
                                //         "<span class='crm-psearch'>$days_since_audited_days</span>";
                                // } else if ( $days_since_audited_days < 21 ) {
                                //     $row[$key]['days_since_audited'] = 
                                //         "<span class='crm-center'>$days_since_audited_days</span>";
                                // }
                                if ( $val['days_since_audited'] >= 21 ) {
                                    $row[$key]['days_since_audited'] = 
                                        "<span class='crm-psearch'>{$val['days_since_audited']}</span>";
                                } else if ( $val['days_since_audited'] < 21 ) {
                                    $row[$key]['days_since_audited'] = 
                                        "<span class='crm-center'>{$val['days_since_audited']}</span>";
                                }
                            // }
                        } else {
                            $row[$key]['days_since_audited'] = 
                                        "<span class='crm-center'></span>";
                        }
                        /*** Set Ellapsed days since Audited date - End ***/

                        /*** Set Billed Column - Start ***/
                        // Retrieve retrofit_invoiced date from gcc_misc table
                        $retrofit_invoiced = array();
                        $retrofit_invoiced[$val['contact_id']] = Efficiency_BAO_Applicant::getFieldValue( 'gcc_misc' ,
                                                                                      'retrofit_invoiced',
                                                                                      $val['contact_id'] );
                        if ( $retrofit_invoiced[$val['contact_id']] ) {
                            //$row[$key]['billed'] = date( 'M-y', strtotime( $retrofit_invoiced ) );
                            $row[$key]['billed'] = $val['billed'];
                        }
                        /*** Set Billed Column - End ***/
                    } // End of If CIVICRM_EFFICIENCY_ELECTRIC
                    
                    /*** Insert into civicrm_prevnext_cache - Start ***/
                    /* $prevnextValues = array();
                    if ( isset( $this->_formValues['status_project_completed'] ) || isset( $this->_formValues['status_retrofit_completed'] ) || isset( $this->_formValues['status_retrofit_pending'] ) || isset( $this->_formValues['status_new_participant'] ) || isset( $this->_formValues['status_audit_assigned'] ) || isset( $this->_formValues['status_applicant'] ) || isset( $this->_formValues['status_ready_for_QA'] ) || isset( $this->_formValues['status_report_to_LDC'] ) || isset( $this->_formValues['status_no_potential'] ) || isset( $this->_formValues['status_close_participant_withdraw'] ) ) {

                        $prevnext_status = array();
                        $status_checked  = true;

                        if ( isset( $this->_formValues['status_project_completed'] ) )
                            $prevnext_status[] = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                     'project_completed', 
                                                                     'value', 'name' );
                        
                        if ( isset( $this->_formValues['status_retrofit_completed'] ) )
                            $prevnext_status[] = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                     'retrofit_completed', 
                                                                     'value', 'name' );
                        
                        if ( isset( $this->_formValues['status_retrofit_pending'] ) )
                            $prevnext_status[] = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                     'retrofit_pending', 
                                                                     'value', 'name' );
                        
                        if ( isset( $this->_formValues['status_new_participant'] ) )
                            $prevnext_status[] = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                     'new_participant', 
                                                                     'value', 'name' );
                        
                        if ( isset( $this->_formValues['status_audit_assigned'] ) )
                            $prevnext_status[] = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                     'audit_assigned', 
                                                                     'value', 'name' );
                        
                        if ( isset( $this->_formValues['status_applicant'] ) )
                            $prevnext_status[] = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                     'applicant', 
                                                                     'value', 'name' );
                        
                        if ( isset( $this->_formValues['status_ready_for_QA'] ) )
                            $prevnext_status[] = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                     'ready_for_QA', 
                                                                     'value', 'name' );
                        
                        if ( isset( $this->_formValues['status_report_to_LDC'] ) )
                            $prevnext_status[] = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                     'report_to_LDC', 
                                                                     'value', 'name' );
                        
                        if ( isset( $this->_formValues['status_no_potential'] ) )
                            $prevnext_status[] = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                     'closed_no_potential', 
                                                                     'value', 'name' );
                        
                        if ( isset( $this->_formValues['status_close_participant_withdraw'] ) )
                            $prevnext_status[] = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                     'closed_participant_withdrew', 
                                                                     'value', 'name' );

                        $prevnext_status = implode( ",", $prevnext_status );

                        $prevnextValues[] = " ( 'civicrm_contact', {$val['contact_id']},
                                            {$this->_uid}, 'list_participant','{$prevnext_status}' 
                                          ) ";
                    } else {
                        $prevnextValues[] = " ( 'civicrm_contact', {$val['contact_id']},
                                            {$this->_uid}, 'list_participant','no_status' 
                                          ) ";
                    }
                    $pnid = CRM_Core_BAO_PrevNextCache::setItem( $prevnextValues );*/
                    /*** Insert into civicrm_prevnext_cache - End ***/
                    //unset($row[$key]['auto_status']);
                } // End of if isset ( $row[$key] )
            } // End of foreach 
            
            // Insert current user's last selected form preferences into cache table
            /*$prevnextdata = null;
            if ( $status_checked ) {
                $prevnextdata = 'status';
                CRM_Core_BAO_Cache::setItem( $prevnextdata, "gcc_list_participant_prevnext_{$this->_uid}", 
                                             'Efficiency_Form_Search_Custom_ListParticipant_prevnext', null );
            } else {
                $prevnextdata = 'no_status';
                CRM_Core_BAO_Cache::setItem( $prevnextdata, "gcc_list_participant_prevnext_{$this->_uid}", 
                                             'Efficiency_Form_Search_Custom_ListParticipant_prevnext', null );
                                             }*/

        } // End of if ( $row )
    }    
    
    function prevnextData( $query ) {
        if( $query ) {

            /*** Check if LIMIT exists in query - Start ***/
            $limit = stristr( $query, 'LIMIT' );
            
            // Remove LIMIT from query if LIMIT exists
            if ( $limit ) {
                $query = substr( $query, 0, (strripos( $query, 'LIMIT' ) - 1 ) );
            }

            /*** Check if LIMIT exists in query - End ***/

            $dao   = CRM_Core_DAO::executeQuery( $query );

            $conut = 0;
            while( $dao->fetch() ) {

                /*
                $result[$count]['file_identifier'] = $dao->file_identifier;
                $result[$count]['client_name']     = $dao->client_name;
                $result[$count]['auto_status']     = $dao->auto_status;
                $result[$count]['date_entered']    = $dao->date_entered;
                $result[$count]['qa_status']       = $dao->qa_status;
                $result[$count]['Status']          = $dao->Status;
                */
                $result[$count]['contact_id']      = $dao->contact_id;
                /*
                $result[$count]['checkbox']        = $dao->checkbox;
                $result[$count]['action']          = $dao->action;
                */
                $count++;
            }
            
            if ( $result ) {
                // Cleanup civicrm_prevnext_cache table for list_participant
                CRM_Core_BAO_PrevNextCache::deleteItem( null, "list_participant_{$this->_uid}", 'civicrm_contact' );
                
                // delete current user's last form preferences from cache table
                CRM_Core_BAO_Cache::deleteGroup( "prevnext_{$this->_uid}" );

                if ( isset( $status_checked ) )
                    $status_checked = $status_checked; 
                else 
                    $status_checked = false;

                foreach ( $result as $key => $val ) {
                    if ( isset( $result[$key] ) ) {
                        
                        /*** Insert into civicrm_prevnext_cache - Start ***/
                        $prevnextValues = array();
                        if ( isset( $this->_formValues['status_project_completed'] ) || isset( $this->_formValues['status_retrofit_completed'] ) || isset( $this->_formValues['status_retrofit_pending'] ) || isset( $this->_formValues['status_new_participant'] ) || isset( $this->_formValues['status_audit_assigned'] ) || isset( $this->_formValues['status_applicant'] ) || isset( $this->_formValues['status_ready_for_QA'] ) || isset( $this->_formValues['status_report_to_LDC'] ) || isset( $this->_formValues['status_no_potential'] ) || isset( $this->_formValues['status_close_participant_withdraw'] ) ) {
                            
                            $prevnext_status = array();
                            $status_checked  = true;
                            
                            if ( isset( $this->_formValues['status_project_completed'] ) )
                                $prevnext_status[] = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                                  'project_completed', 
                                                                                  'value', 'name' );
                            
                            if ( isset( $this->_formValues['status_retrofit_completed'] ) )
                                $prevnext_status[] = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                                  'retrofit_completed', 
                                                                                  'value', 'name' );
                            
                            if ( isset( $this->_formValues['status_retrofit_pending'] ) )
                                $prevnext_status[] = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                                  'retrofit_pending', 
                                                                                  'value', 'name' );
                            
                            if ( isset( $this->_formValues['status_new_participant'] ) )
                                $prevnext_status[] = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                                  'new_participant', 
                                                                                  'value', 'name' );
                            
                            if ( isset( $this->_formValues['status_audit_assigned'] ) )
                                $prevnext_status[] = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                                  'audit_assigned', 
                                                                                  'value', 'name' );
                            
                            if ( isset( $this->_formValues['status_applicant'] ) )
                                $prevnext_status[] = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                                  'applicant', 
                                                                                  'value', 'name' );
                            
                            if ( isset( $this->_formValues['status_ready_for_QA'] ) )
                                $prevnext_status[] = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                                  'ready_for_QA', 
                                                                                  'value', 'name' );
                            
                            if ( isset( $this->_formValues['status_report_to_LDC'] ) )
                                $prevnext_status[] = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                                  'report_to_LDC', 
                                                                                  'value', 'name' );
                            
                            if ( isset( $this->_formValues['status_no_potential'] ) )
                                $prevnext_status[] = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                                  'closed_no_potential', 
                                                                                  'value', 'name' );
                            
                            if ( isset( $this->_formValues['status_close_participant_withdraw'] ) )
                                $prevnext_status[] = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                                  'closed_participant_withdrew', 
                                                                                  'value', 'name' );
                            
                            $prevnext_status = implode( ",", $prevnext_status );
                            
                            $cachekey = "list_participant_{$this->_uid}";
                            $prevnextValues[] = " ( 'civicrm_contact', {$val['contact_id']},
                                            {$this->_uid}, '{$cachekey}','{$prevnext_status}' 
                                          ) ";
                        } else {
                            $cachekey = "list_participant_{$this->_uid}";
                            $prevnextValues[] = " ( 'civicrm_contact', {$val['contact_id']},
                                            {$this->_uid}, '{$cachekey}','no_status' 
                                          ) ";
                        }
                        $pnid = CRM_Core_BAO_PrevNextCache::setItem( $prevnextValues );
                        
                        /*** Insert into civicrm_prevnext_cache - End ***/
                        
                    } // end of if( $result[$key] )
                    
                } // end of foreach

                // Insert current user's last selected form preferences into cache table
                $prevnextdata = null;
                if ( $status_checked ) {
                    $prevnextdata = 'status';
                    CRM_Core_BAO_Cache::setItem( $prevnextdata, "prevnext_{$this->_uid}", 
                                                 'Efficiency_Form_Search_Custom_ListParticipant_prevnext', null );
                } else {
                    $prevnextdata = 'no_status';
                    CRM_Core_BAO_Cache::setItem( $prevnextdata, "prevnext_{$this->_uid}", 
                                                 'Efficiency_Form_Search_Custom_ListParticipant_prevnext', null );
                }
                                
            } // end of if ( $result )

        } else {
            
        } // end of else of if( $query )

    }
}

