<?php
require_once 'CRM/Core/Permission.php';
require_once 'CRM/Core/Form.php';
require_once 'CRM/Contact/BAO/Contact.php';
require_once 'CRM/Contact/BAO/Group.php';

class Efficiency_Form_Report extends CRM_Core_Form
{
    protected $_formFields     = null;
    protected $_selectFields   = array();
    protected $_statusField    = array();
    protected $_optionGroups   = array();
    protected $_optionValues   = array();
    protected $_measuresValueColumns = array();
    
    /**
     * Function to set variables up before form is built
     *
     * @return void
     * @access public
     */
    public function preProcess()
    {
        if ( ! $this->_selectFields ) {

            if ( ! ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) ) {
                $this->_selectFields = array(
                                             'display_name'       => array(
                                                                           'title' => 'Name',
                                                                           'tableAlias' => 'cc'
                                                                           ),
                                             'street_address'     => array(
                                                                           'title' => 'Address',
                                                                           'tableAlias' => 'ca'
                                                                           ),
                                             'city'               => array(
                                                                           'title' => 'Muni',
                                                                           'tableAlias' => 'ca'
                                                                           ),
                                             'postal_code'        => array(
                                                                           'title' => 'Pcode',
                                                                           'tableAlias' => 'ca'
                                                                           ),
                                             ); 
                
            } else {
                $this->_selectFields = array(
                                             'first_name'       => array(
                                                                           'title' => 'FName',
                                                                           'tableAlias' => 'cc'
                                                                           ),
                                             'last_name'       => array(
                                                                         'title' => 'LName',
                                                                         'tableAlias' => 'cc'
                                                                         ),
                                             'street_address'     => array(
                                                                           'title' => 'Address',
                                                                           'tableAlias' => 'ca'
                                                                           ),
                                             'city'               => array(
                                                                           'title' => 'Muni',
                                                                           'tableAlias' => 'ca'
                                                                           ),
                                             ); 
                
                
            }
            
        }
        CRM_Utils_System::setTitle( $this->getExportFileName( ) );
    }
    
    /**
     * Function to actually build the form
     *
     * @return void
     * @access public
     */
    public function buildQuickForm( ) 
    {

        /******* Fix for Reports date search dropdowns add years 2007, 2008, 2009 Start *****/
        
        $minOffset = date('Y') - 2007;
        foreach ( $this->_formFields as $field => $arr ) {

            switch ($field) {
                // date fields
            case 'audit_completed'   :
            case 'audit_invoiced'    :
            case 'retrofit_completed':
            case 'retrofit_invoiced' :
                
                //  $this->add('date', "{$field}_low", ts( $arr['title'] . ' - From' ), 
                //                            CRM_Core_SelectValues::date('manual', $minOffset, 3));
                //                 $this->add('date', "{$field}_high", ts( 'To' ), 
                //                            CRM_Core_SelectValues::date('manual', $minOffset, 3)); 
                
                $this->addDate("{$field}_low" , ts($arr['title'] . ' - From'), false, array( 'formatType' => 'activityDate') );
                $this->addDate("{$field}_high" , ts('To'), false, array( 'formatType' => 'activityDate') );
                               
                               /******* Fix for Reports date search dropdowns add years 2007, 2008, 2009 End *****/
                               
                               break;
                               
                               //select fields
            case 'ldc_id':
                $this->addSelect( 'ldc', ts( 'LDC' ), null );
                break;
                
                //checkbox fields
            case 'audit_completed_blank':
            case 'audit_invoiced_blank':
                $this->addElement('checkbox', $field, $arr['title'], $arr['desc']);
            break;
           
            default:
                if (isset($arr['hidden'])) {
                    $this->add('hidden', "$field", $arr['hidden']); 
                } else {
                    $this->add('text', "$field", ts( $arr['title'] )); 
                }
                break;
            }
        }
        if ( $this->_statusOptions ) {
            $this->assign('isgas', 1);
            foreach ($this->_statusOptions as $data => $stat ){
                switch ($data) {
                case 'new_participant':
                    $this->addElement('checkbox', $data, $stat['title'], $stat['desc']);
                    break;
                case 'status_audit_assigned':
                    $this->addElement('checkbox', $data, $stat['title'], $stat['desc']);
                    break;
                case 'status_retrofit_pending':
                    $this->addElement('checkbox', $data, $stat['title'], $stat['desc']);
                    break;
                case 'status_no_potential':
                    $this->addElement('checkbox', $data, $stat['title'], $stat['desc']);
                    break;
                case 'status_close_participant_withdraw':
                    $this->addElement('checkbox', $data, $stat['title'], $stat['desc']);
                    break;
                case 'status_retrofit_completed':
                    $this->addElement('checkbox', $data, $stat['title'], $stat['desc']);
                    break;
                case 'status_project_completed':
                    $this->addElement('checkbox', $data, $stat['title'], $stat['desc']);
                    break;
                }
            }
        }
        $buttons  =  array(
                           array ( 'type'      => 'submit',
                                   'name'      => ts('Search'),
                                   'subName'   => 'search',
                                   'isDefault' => true   ),
                           array ( 'type'      => 'submit',
                                   'name'      => ts('Generate Report'),
                                   'subName'   => 'generate' ),
                           );
        $this->addButtons( $buttons );
    }
    
    /**
     * process the form after the input has been submitted and validated
     *
     * @access public
     * @return void
     */
    public function postProcess() 
    {
        $params = $this->controller->exportValues( $this->_name );
       
        $rows   = $this->getAppDetails( $params );
               
        $header = $this->getHeaderSequence();
        $file   = $this->getExportFileName();
        
        // clean up the numerically indexed headers for the Gas LDC Final Report
        if ($file == ts('Gas LDC Final Report')) {
            foreach ($header as $key => $value) {
                if (preg_match('/(.*)\-[0-9]{1,2}$/', $value, $m)) {
                    $header[$key] = $m[1];
                } 
            }
        }
        
        // if both Audit Billed (audit_invoiced) and Retrofit Invoiced (retrofit_invoiced)
        // dates are not set then drop those results
        if ($file == ts('Enbridge Report')) {
            foreach ($rows as $key => $data) {
                if (!$data['Audit Billed'] && !$data['Retrofit Invoiced']) {
                    unset($rows[$key]);
                } 
            }
            ksort($rows);
        }
        
        if ( CRM_Utils_Array::value( '_qf_' . $this->_name . '_submit_generate', $_POST ) ) {
            require_once 'CRM/Core/Report/Excel.php';
            CRM_Core_Report_Excel::writeCSVFile( $this->getExportFileName( ), $header, 
                                                 $rows, $this->getTitleHeader($params) );
            exit();
        }
        
        $this->assign('searchDone', true);
        $this->assign('rows', $rows);
        $this->assign('count', count($rows));
    }
    
    /**
     * Get org name and id for all the orgs this contact
     * is "employed" with along with his status
     */
    public function getAppDetails( $params )
    {

        $query  =  $this->getQuery($params);
     
        $dao    =& CRM_Core_DAO::executeQuery( $query, CRM_Core_DAO::$_nullArray );
             
        $results = array();
        while ( $dao->fetch( ) ) {

            $result = array();
            
            // get participant status; whether we be automatic or manually set via the
            // drupal status control module
            if (!empty($this->_statusField)) {
                require_once 'Efficiency/BAO/Applicant.php';
                $result[$this->_statusField['title']] =Efficiency_BAO_Applicant::getStatusLabel( $dao->id );
            }
            
            // select fields as columns of report
            foreach ($this->_selectFields as $field => $arr) {

                if (isset($dao->$field)) {
                    if ($field == 'audit_completed' || $field == 'retrofit_completed') {
                        $result[$arr['title']] = CRM_Utils_Date::customFormat($dao->$field, '%d/%m/%Y');
                    } elseif ($field == 'audit_invoiced' || $field == 'retrofit_invoiced') {
                        $result[$arr['title']] = CRM_Utils_Date::customFormat($dao->$field, '%d/%m/%Y');
                    } elseif (in_array($field, array('xm_kw_s','xm_kw_w','bm_kw_s','bm_kw_w'))) {
                        $result[$arr['title']] = round($dao->$field, 3);
                    } else {
                        $result[$arr['title']] = $dao->$field;
                    }
                }
            }
          
            // option groups as columns
            if (! empty($this->_optionGroups)) {

                foreach ($this->_optionGroups as $field => $arr) {
                 
                    if ($dao->$field && isset($arr['options'][$dao->$field])) {

                        $result[$arr['title']] = $arr['options'][$dao->$field];
                                             
                    } elseif (isset($arr['zero value']) && ($dao->$field == '0')) {
                        $result[$arr['title']] = $arr['zero value'];
                    }
                }
            }
            
            // option values as columns
            if (! empty($this->_optionValues)) {
                foreach ($this->_optionValues as $field => $arr) {
                    foreach ($arr['options'] as $option => $optArr) {
                        $result[$optArr['title']] = ($optArr['value'] == $dao->$field) ? '1' : '';
                    }
                }
            }

            // measure fields/values as columns
            if (! empty($this->_measuresValueColumns)) {
                
                foreach ($this->_measuresValueColumns as $field => $arr) {
                    $count=0;
                                                
                    if ( !empty($arr['fields']) ) {
                        $select = implode(',', array_keys($arr['fields']));
                        $select = ",$select";
                    }else{
                        $select = '';
                    }
                    foreach ($arr['values'] as $value => $title) {
                        if ( $dao->id ) {
                            $queryC = "select id, installed $select FROM gcc_measures WHERE entity_id=$dao->id and $field like '{$value}'";
                            
                            $daoC   =& CRM_Core_DAO::executeQuery( $queryC, CRM_Core_DAO::$_nullArray );
                            $count++;
                            
                            while ($daoC->fetch()) {
                                if (! isset($arr['noValueHeader'])) {
                                }
                                if ( isset( $daoC->installed ) ) {
                                    $result[$title] = $daoC->installed;
                                }

                                if (! empty($arr['fields'])) {
                                    foreach ($arr['fields'] as $tableCol => $colTitle) {
                                        $result[$colTitle . '-' . $count] = $daoC->{$tableCol};
                                    }
                                }
                                //only one record expected
                                break;
                            }
                        }
                    }
                }
            }  
                      
            $result = $this->getIntoSequence($result);
            
            $results[] = $result;
        }
        return $results;
    }

    public function getQuery( $params ) 
    {
        $session = & CRM_Core_Session::singleton();
        $uid  = $session->get('userID');

        $where  = $this->buildWhereClause($params);
        $select = $this->buildSelectClause();
        $from = $this->buildFromClause();

        $query = "
SELECT DISTINCT(cc.id) AS id,$select
FROM $from 
WHERE $where AND cc.id <> $uid
GROUP BY cc.id
ORDER BY ga.file_identifier ASC";
           
         return $query;
         
    }

    public function buildWhereClause( $params ) 
    {
        $where = array();
        $auditClause = $retrofitClause = array();
        
        if ( ! ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) ) {
            
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
        }
        
        // Retreive current user's userid
        $session =& CRM_Core_Session::singleton( );
        $this->_uid = $session->get('userID');

        // Retrieve current user's contactsubtype
        $subType    = CRM_Contact_BAO_Contact::getContactSubType( $this->_uid, "," );

        // Check if subtype is Auditor or Retrofit
        if ( $subType == 'Auditor' || $subType == 'Retrofit' ) {

            $where[] = " cr.contact_id_a = $this->_uid ";

            if ( $subType == 'Auditor' ) {
                $RelID  = CRM_Core_DAO::getFieldValue( 'CRM_Contact_DAO_RelationshipType', 
                                                       'Auditor for', 'id', 'name_a_b' );
            } else if ( $subType == 'Retrofit' ) {
                $RelID = CRM_Core_DAO::getFieldValue( 'CRM_Contact_DAO_RelationshipType', 
                                                      'Retrofit for', 'id', 'name_a_b' );
            }

            // Add relationship where clause for Auditor & Retrofit
            $where[] = " cr.relationship_type_id = $RelID ";

        } else if ( $subType == 'CSR' ) {
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
                    $where[] = " grpcont.group_id = {$groupID[0]->id} ";
                    $where[] = " grpcont.status = 'Added' ";
                }                
            }
        }

        $where[] = " cc.is_deleted = 0 ";
        $subquery = null;
        $sub_where = array();

        foreach($params as $key => $val) {

            if ($key != 'qfKey') {
                if (substr($key, -4) == '_low' || substr($key, -5) == '_high') {
                    if (substr($key, -4) == '_low') {
                        $fieldName = substr($key, 0, -4);
                        $op     = '>=';
                    } elseif (substr($key, -5) == '_high') {
                        $fieldName = substr($key, 0, -5);
                        $op     = '<=';
                    }
                    $date    = CRM_Utils_Date::processDate( $val );
                    if($fieldName == 'audit_invoiced' && $date) {
                        $auditClause[] = $this->_formFields[$fieldName]['tableAlias'] . ".{$fieldName} $op '$date'";
                    } else if($fieldName == 'retrofit_invoiced' && $date) {
                        $retrofitClause[] = $this->_formFields[$fieldName]['tableAlias'] . ".{$fieldName} $op '$date'";
                    } else {
                        if (!isset($params[$fieldName . '_blank']) && $date) {
                            $where[] = $this->_formFields[$fieldName]['tableAlias'] . ".{$fieldName} $op '$date'";
                        }
                    }
                } elseif ($val) {
                    if (substr($key, -6) == '_blank') {
                        $where[] = $this->_formFields[$key]['tableAlias'] . "." . substr($key, 0, -6) . " IS NULL";
                    } elseif (substr( $key, -3, 3 ) == '_id') {
                        $where[] = $this->_formFields[$key]['tableAlias'] . ".{$key} = {$val}";
                    } elseif ( !stristr($key, 'status_') ) {
                        $where[] = $this->_formFields[$key]['tableAlias'] . ".{$key} like \"%{$val}%\"";
                    }
                }
                /** For the status block **/
                if ( ! ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) ) {
                    $sub_from = "LEFT JOIN gcc_applicant ga ON ( c.id = ga.entity_id )";

                    switch( $key ) {
                    case 'status_audit_assigned' :
                        if ( CRM_Utils_Array::value('status_audit_assigned', $params) ) {
                            $sub_where['status_audit_assigned'] = "( ga.auto_status = $audit_assigned )";
                        }
                    case 'status_retrofit_pending' :
                        if ( CRM_Utils_Array::value('status_retrofit_pending', $params) ) {
                            $sub_where['status_retrofit_pending'] = "( ga.auto_status = $retrofit_pending )"; 
                        }
                    case 'status_no_potential' :
                        if ( CRM_Utils_Array::value('status_no_potential', $params) ) {
                            $sub_where['status_no_potential'] = "( ga.auto_status = $closed_no_potential )"; 
                        }
                    case 'status_close_participant_withdraw' :
                        if ( CRM_Utils_Array::value('status_close_participant_withdraw', $params) ) {
                            $sub_where['status_close_participant_withdraw'] = "( ga.auto_status = $closed_participant_withdrew )"; 
                        }
                    case 'status_retrofit_completed' :
                        if ( CRM_Utils_Array::value('status_retrofit_completed', $params) ) {
                            $sub_where['status_retrofit_completed'] = "( ga.auto_status = $retrofit_completed )"; 
                        }
                    case 'status_project_completed' :
                        if ( CRM_Utils_Array::value('status_project_completed', $params) ) {
                            $sub_where['status_project_completed'] = "( ga.auto_status = $project_completed )"; 
                        }
                    }
                }
            }
        }
        if ( $sub_where ) {
            $sub_where = implode( ' OR ', $sub_where );
        }
        $subquery  = " AND cc.id IN ( SELECT c.id from civicrm_contact c $sub_from WHERE $sub_where )" ;
        $session = & CRM_Core_Session::singleton();
        $uid  = $session->get('userID');
        //$role = CRM_Core_DAO::getFieldValue( 'CRM_Contact_DAO_Contact', $uid, 'contact_sub_type', 'id' );
        $role = strstr( CRM_Core_DAO::getFieldValue( 'CRM_Contact_DAO_Contact', $uid, 'contact_sub_type', 'id' ), 'Admin' );
        $reg  = "SELECT region FROM civicrm_value_gcc_custom_group WHERE entity_id = $uid";
        $region = & CRM_Core_DAO::singleValueQuery($reg);
        $region ="'$region'";
        $grp   = "SELECT id FROM civicrm_group WHERE title LIKE $region";
        $grpid = & CRM_Core_DAO::singleValueQuery($grp);
        
        // if ( $role != "Admin" && !empty($grpid) ) {
//             $where[] = "( grpcont.group_id IN($grpid) )";
//         }

        if ( !$role && !empty($grpid) ) {
            $where[] = "( grpcont.group_id IN($grpid) )";
        }

        $where = implode(' AND ', $where);
        $auditClause = implode(' AND ', $auditClause);
        $retrofitClause = implode(' AND ', $retrofitClause);
       
        if( $auditClause && $retrofitClause && !empty($where) ) {
            $where .= ' AND ( ' . $auditClause . ' OR ' . $retrofitClause .' ) ';
        } else{
            
            if( $auditClause  && !empty($where)){
                $where .= ' AND ' . $auditClause ;
            } else{
                 $where .= $auditClause ;
            }
            
            if( $retrofitClause  && !empty($where)){
                $where .= ' AND ' . $retrofitClause ;
            }else{
                 $where .= $retrofitClause ;
            }
        }
        if ( $sub_where ) {
            $where .= $subquery;
        }
        return $where ? $where : true;
       
    }

    public function buildSelectClause( ) 
    {
        foreach (array($this->_selectFields, $this->_optionGroups, $this->_optionValues) as $fields) {
             if (! empty($fields)) {
                foreach($fields as $key => $val) {
                    if($key == 'full_name') {
                        $select[] = "{$val['tableAlias']}.display_name as {$key}";
                    }
                    if ( ! ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) ) {
                        if ( $val['name'] ) {
                            $select[] = "{$val['tableAlias']}.{$val['name']} as {$key}";
                        }elseif ( $val['aggregate'] ) {
                            if( $val['aggregate'] == "MIN" )
                                $select[] = "DATE_FORMAT( MIN( {$val['tableAlias']}.{$key} ),'%d-%m-%Y') as {$key}";
                        }elseif (!isset($val['noSelect']) && isset($val['tableAlias']) && $key != 'full_name' ) {
                            $select[] = "{$val['tableAlias']}.{$key} as {$key}";
                        }
                    }else{
                        if (!isset($val['noSelect']) && isset($val['tableAlias']) && $key != 'full_name' ) {
                            $select[] = "{$val['tableAlias']}.{$key} as {$key}";
                        }                            
                    }
                }
             }
        }
        $select = implode(', ', $select);

        return $select;
    }
    
    public function getIntoSequence( $array ) 
    {
        $result    = array();
        $sequence = $this->getResultSequence();
        
        foreach ($sequence as  $key=>$value) {
            (isset($array[$value]) )?$result[$value] = $array[$value]:$result[$value]= '';
            
        }
        
        return $result;
        
    }
    
    public function buildFromClause( ) 
    {
        $from = "gcc_applicant ga
LEFT JOIN civicrm_contact cc ON (ga.entity_id = cc.id)
LEFT JOIN gcc_measures_other gmo ON (cc.id = gmo.entity_id)
LEFT JOIN gcc_misc gmi ON (cc.id = gmi.entity_id)
LEFT JOIN civicrm_group_contact cgc ON (cc.id = cgc.contact_id)
LEFT JOIN civicrm_group cg ON (cgc.group_id = cg.id)
LEFT JOIN civicrm_address ca ON (cc.id = ca.contact_id AND ca.is_primary = 1)
LEFT JOIN civicrm_phone   cp ON (cc.id = cp.contact_id AND cp.is_primary = 1)
LEFT JOIN civicrm_relationship cr ON (cc.id = cr.contact_id_b)
LEFT JOIN civicrm_contact contact_auditor ON (cr.contact_id_a = contact_auditor.id) 
LEFT JOIN gcc_measures gm ON (cc.id = gm.entity_id)
LEFT JOIN civicrm_group_contact grpcont ON (cc.id = grpcont.contact_id)";
        return $from;
    }
}

?>

