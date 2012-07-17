<?php
require_once 'Efficiency/Form/AddApplicant.php';
require_once 'api/api.php';
require_once 'api/v3/Relationship.php';
require_once 'api/v3/Phone.php';
require_once 'api/v3/Contact.php';
require_once 'api/v3/Email.php';
require_once 'api/v3/Address.php';

class Efficiency_Form_AddApplicant_Note extends Efficiency_Form_AddApplicant
{
    
    public function preProcess( ) {
        
        parent::preProcess( );
        
        if ( $this->_action & CRM_Core_Action::VIEW ) {
            $session = CRM_Core_Session::singleton();
            
            //default tab selected on cancel button
            $url = CRM_Utils_System::url('civicrm/efficiency/applicant/note/view', 'reset=1&action=view&cid=' . $this->_applicantId . '&selectedChild=note' );
            
            $session->pushUserContext( $url );
            
            $params       = array( );
            $defaults     = array( );
            $contactinfo  = array( );
            $params['id'] = $params['contact_id'] = $this->_applicantId;
            
            $noteParams = array(
                                'note'           => array( 
                                                          'entity_table' => 'gcc_applicant',
                                                          'entity_id'    => $params['id'],
                                                          'subject'      => 'note',
                                                          'version'      => 3
                                                           ),
                                'auditornotes'   => array('entity_table' => 'gcc_applicant',
                                                          'entity_id'    => $params['id'],
                                                          'subject'      => 'auditornotes',
                                                          'version'      => 3 
                                                          ),
                                'vvnotes'       => array( 
                                                          'entity_table' => 'gcc_applicant',
                                                          'entity_id'    => $params['id'],
                                                          'subject'      => 'vvnotes',
                                                          'version'      => 3
                                                           ),                                
                                'hsnotes'        => array('entity_table' => 'gcc_applicant',
                                                          'entity_id'    => $params['id'],
                                                          'subject'      => 'hsnotes',
                                                          'version'      => 3 
                                                          )
                                );
            

            // $noteParams = array( 
            //                     'entity_table' => 'gcc_applicant',
            //                     'entity_id'    => $params['id'] ,
            //                     'contact_id'   => $params['id'],
            //                     'version'      => 3,
            //                      );
            
            $result =array( );
            $contactinfo =array();
            foreach ( $noteParams as $key => $value ) {
                $result[$key] = civicrm_api( 'note','get',$value );
                
                if ( $key == 'note' ) {
                    if ( count( $result[$key]['values'] ) > 1 ) {
                        foreach ( $result[$key]['values'] as $akey => $aval ) {
                            $id = $akey;
                            $session->set( 'noteNid', $id );
                            $contactinfo['note'] = $result[$key]['values'][$id]['note'];
                        }
                    } else {
                        $id = $result[$key]['id'];
                        $session->set( 'noteNid', $id );
                        $contactinfo['note'] = $result[$key]['values'][$id]['note'];
                    }
                }else if ( $key == 'hsnotes' && CRM_Core_Permission::check( 'view_H_S_Note' )) {
                    if ( count( $result[$key]['values'] ) > 1 ) {
                        foreach ( $result[$key]['values'] as $akey => $aval ) {
                            $id = $akey;
                            $session->set( 'hsnotesNid', $id );
                            $contactinfo['hsnotes'] = $result[$key]['values'][$id]['note'];
                        }
                    } else {
                        $id = $result[$key]['id'];
                        $session->set( 'hsnotesNid', $id );
                        $contactinfo['hsnotes'] = $result[$key]['values'][$id]['note'];
                    }
                }else if ( $key == 'vvnotes') {
                    if ( count( $result[$key]['values'] ) > 1 ) {
                        foreach ( $result[$key]['values'] as $akey => $aval ) {
                            $id = $akey;
                            $session->set( 'vvnotesNid', $id );
                            $contactinfo['vvnotes'] = $result[$key]['values'][$id]['note'];
                        }
                    } else {
                        $id = $result[$key]['id'];
                        $session->set( 'vvnotesNid', $id );
                        $contactinfo['vvnotes'] = $result[$key]['values'][$id]['note'];
                    }
                }else if ( $key == 'auditornotes' ) {
                    if ( count( $result[$key]['values'] ) > 1 ) {
                        foreach ( $result[$key]['values'] as $akey => $aval ) {
                            $id = $akey;
                            $session->set( 'auditornotesNid', $id );
                            $contactinfo['auditornotes'] = $result[$key]['values'][$id]['note'];
                        }
                    } else {
                        $id = $result[$key]['id'];
                        $session->set( 'auditornotesNid', $id );
                        $contactinfo['auditornotes'] = $result[$key]['values'][$id]['note'];
                    }
                }
            }

                // $result = civicrm_api( 'note','get',$noteParams );
                
                // if(array_key_exists('id',$result )){
                
            //     $session->set( 'nid', $result['id'] );
            //     $contactinfo['note'] = $result['values'][$result['id']]['note'];
            // }
            // $customParams =array(
//                                  'entityID'  => $this->_applicantId,
//                                  'custom_20' => 1
//                                  );
//             require_once 'CRM/Core/BAO/CustomValueTable.php';
//             $customValue = CRM_Core_BAO_CustomValueTable::getValues( $customParams );
            
            //$contactinfo['corrections'] = $customValue['custom_20'];
            
            if ( $contactinfo ) {
                
                $this->assign( 'contactinfo', $contactinfo );
                
            }
            
            if ( $this->_applicantId && CRM_Core_Permission::check( 'edit_app_note' ) ) {
                $editurl = CRM_Utils_System::url('civicrm/efficiency/applicant/note/update', 'reset=1&action=update&cid=' . $this->_applicantId );
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
    function setDefaultValues( ) {

        $defaults = array( );
        $session = CRM_Core_Session::singleton(); 

        $params = array(
                        'note'           => array( 
                                                  'entity_table' => 'gcc_applicant',
                                                  'entity_id'    => $this->_applicantId,
                                                  'subject'      => 'note',
                                                  'version'      => 3
                                                   ),
                        'auditornotes'   => array('entity_table' => 'gcc_applicant',
                                                  'entity_id'    => $this->_applicantId,
                                                  'subject'      => 'auditornotes',
                                                  'version'      => 3 
                                                  ),
                        'vvnotes'        => array( 
                                                  'entity_table' => 'gcc_applicant',
                                                  'entity_id'    => $this->_applicantId,
                                                  'subject'      => 'vvnotes',
                                                  'version'      => 3
                                                   ),                        
                        'hsnotes'        => array('entity_table' => 'gcc_applicant',
                                                  'entity_id'    => $this->_applicantId,
                                                  'subject'      => 'hsnotes',
                                                  'version'      => 3 
                                                  )
                        
                        );
        // $params = array( 
        //                 'entity_table' => 'gcc_applicant',
        //                 'entity_id'    => $this->_applicantId,
        //                 'contact_id'   => $this->_applicantId,
        //                 'version'      => 3,
        //                  );
        $result =array( );
        foreach ( $params as $key => $value ) {
            $result[$key] = civicrm_api( 'note','get',$value );
            
            if ( array_key_exists('id',$result[$key] )){
                if ( $key == 'note' ) {
                    $id = $result[$key]['id'];
                    $session->set( 'noteNid', $result[$key]['id'] );
                    $defaults['note'] = $result[$key]['values'][$id]['note'];
                } else if ( $key == 'hsnotes') {
                    $id = $result[$key]['id'];
                    $session->set( 'hsnotesNid', $result[$key]['id'] );
                    $defaults['hsnotes'] = $result[$key]['values'][$id]['note'];
                }else if ( $key == 'vvnotes') {
                    $id = $result[$key]['id'];
                    $session->set( 'vvnotesNid', $result[$key]['id'] );
                    $defaults['vvnotes'] = $result[$key]['values'][$id]['note'];
                }else if ( $key == 'auditornotes') {
                    $id = $result[$key]['id'];
                    $session->set( 'auditornotesNid', $result[$key]['id'] );
                    $defaults['auditornotes'] = $result[$key]['values'][$id]['note'];
                }
            } else {
                if ( $key == 'note' ) {
                    foreach ( $result[$key]['values'] as $akey => $aval ) {
                        $id = $akey;
                        $session->set( 'noteNid', $id );
                        $defaults['note'] = $result[$key]['values'][$id]['note'];
                    }
                    //$session->set( 'noteNid','' );
                } else if ( $key == 'hsnotes') {
                    foreach ( $result[$key]['values'] as $akey => $aval ) {
                        $id = $akey;
                        $session->set( 'hsnotesNid', $id );
                        $defaults['hsnotes'] = $result[$key]['values'][$id]['note'];
                    }
                    //$session->set( 'hsnotesNid','' ); 
                }else if ( $key == 'vvnotes') {
                    foreach ( $result[$key]['values'] as $akey => $aval ) {
                        $id = $akey;
                        $session->set( 'vvnotesNid', $id );
                        $defaults['vvnotes'] = $result[$key]['values'][$id]['note'];
                    }
                    //$session->set( 'vvnotesNid','' );
                }else if ( $key == 'auditornotes') {
                    foreach ( $result[$key]['values'] as $akey => $aval ) {
                        $id = $akey;
                        $session->set( 'auditornotesNid', $id );
                        $defaults['auditornotes'] = $result[$key]['values'][$id]['note'];
                    }
                    //$session->set( 'auditornotesNid','' );
                }
            }  
        }     
        // $customParams =array(
//                              'entityID'  => $this->_applicantId,
//                              'custom_20' => 1
//                              );
        
//         require_once 'CRM/Core/BAO/CustomValueTable.php';
//         $customValue = CRM_Core_BAO_CustomValueTable::getValues( $customParams );
        
        //$defaults['corrections'] = $customValue['custom_20'];

        return $defaults;

    }
    
    
    /**
     * Function to actually build the form
     *
     * @return void
     * @access public
     */
    
    public function buildQuickForm() {
        parent::buildQuickForm( ); 
        // This is Basic note for all version
        $this->add('textarea', 'note', ts("Note") , array('cols' => '60', 'rows' => '3'));  
        $corr = $this->add('textarea', 'auditornotes', ts("Auditor's Notes") , array('cols' => '60', 'rows' => '3', 'readonly' => TRUE ));
        //$corr->freeze( );
        if( $this->_isElectric == 1 ) {
            
            if (CRM_Core_Permission::check( 'edit_Note' )){
                $this->add('textarea', 'vvnotes', ts("Verification Visit Notes") , array('cols' => '60', 'rows' => '3'));
                
            }else{
                
                $this->add('textarea', 'vvnotes', ts("Verification Visit Notes") , array('cols' => '60', 'rows' => '3', 'readonly' => TRUE ));
                
            }
            
        } // else {
//             $corr = $this->add('textarea', 'corrections', ts("Auditor's Notes") , array('cols' => '60', 'rows' => '3'));
//             $corr->freeze( );
//         }
      
        if( $this->_isElectric == 1 ) {
            
            if ( CRM_Core_Permission::check( 'view_H_S_Note' ) && !CRM_Core_Permission::check( 'edit_H_S_Note' )){
                
                $this->add('textarea', 'hsnotes', ts("Health & Safety Notes") , array('cols' => '60', 'rows' => '3', 'readonly' => TRUE ));
            }else if (CRM_Core_Permission::check( 'edit_H_S_Note' )){
                
                $this->add('textarea', 'hsnotes', ts("Health & Safety Notes") , array('cols' => '60', 'rows' => '3'));
            }else{
                
                $this->add('hidden', 'hsnotes', '',ts("Health & Safety Notes") );
            }
        }
        if ( $this->_actionString == "add" ) {
            $buttons   = array( );
            $buttons[] = array ( 'type'      => 'submit',
                                 'name'      => ts('Done'),
                                 'spacing'   => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                                 'isDefault' => true   );
            
            $this->addButtons( $buttons );
        }
        
    }
    
    
    public function postProcess() {
        
        // check if dedupe button, if so return.
        $buttonName     = $this->controller->getButtonName( );
        $session        = & CRM_Core_Session::singleton( );
        $note_update    = false;

        if ( $buttonName == '_qf_Note_submit' || $buttonName == '_qf_Note_upload' ) {
            //get the submitted values in an array
            $params = $this->controller->exportValues( $this->_name );

            $noteParams = array(
                                'note'           => array( 
                                                          'entity_table' => 'gcc_applicant',
                                                          'entity_id'    => $params['cid'],
                                                          'subject'      => 'note',
                                                          'version'      => 3
                                                          ),
                                'auditornotes'   => array('entity_table' => 'gcc_applicant',
                                                          'entity_id'    => $params['cid'],
                                                          'subject'      => 'auditornotes',
                                                          'version'      => 3 
                                                          ),
                                
                                'vvnotes'        => array( 
                                                          'entity_table' => 'gcc_applicant',
                                                          'entity_id'    => $params['cid'],
                                                          'subject'      => 'vvnotes',
                                                          'version'      => 3
                                                          ),
                                
                                'hsnotes'        => array('entity_table' => 'gcc_applicant',
                                                          'entity_id'    => $params['cid'],
                                                          'subject'      => 'hsnotes',
                                                          'version'      => 3 
                                                          ),
                                );

            foreach ( $noteParams as $key => $value ) {
                $getresult[$key] = civicrm_api( 'note','get',$value);

                $keycount = count( $getresult[$key]['values'] );
                $id = null;
                if ( array_key_exists( 'id', $getresult[$key] ) ) {
                    $value['id'] = $getresult[$key]['id'];
                } else if ( $keycount > 1 ) {
                    
                    foreach ( $getresult[$key]['values'] as $akey => $aval ) {
                        $aval['version'] = 3;
                        $delnotes = civicrm_api( 'note','delete',$aval);
                    }
                }
                
                //$noteNid         = $session->get( 'noteNid' );
                //$hsnotesNid      = $session->get( 'hsnotesNid' );
                //$vvnotesNid      = $session->get( 'vvnotesNid' );
                //$auditornotesNid = $session->get( 'auditornotesNid' ); 

                if ( /*!empty( $noteNid ) &&*/ $key == 'note' ){
                    //$value['id']         = $noteNid;
                    $value['contact_id'] = $params['cid'];
                    $value['note']       = ( $params['note'] ) ? $params['note'] : 'null';
                } else if ( /*!empty( $hsnotesNid ) &&*/ $key == 'hsnotes' ){
                    //$value['id'] = $hsnotesNid;
                    $value['contact_id'] = $params['cid'];
                    $value['note']       = ( $params['hsnotes'] ) ? $params['hsnotes'] : 'null';
                } else if ( /*!empty( $vvnotesNid ) &&*/ $key == 'vvnotes' ){
                    //$value['id'] = $vvnotesNid;
                    $value['contact_id'] = $params['cid'];
                    $value['note']       = ( $params['vvnotes'] ) ? $params['vvnotes'] : 'null';
                } else if ( /*!empty( $auditornotesNid ) &&*/ $key == 'auditornotes' ){
                    //$value['id'] = $auditornotesNid;
                    $value['contact_id'] = $params['cid'];
                    $value['note']       = ( $params['auditornotes'] ) ? $params['auditornotes'] : 'null';
                }
                
                //if ( $key != 'vvnotes' && $key != 'auditornotes' ) {
                    $result = civicrm_api( 'note','create',$value);
                //}
                $note_update = true;
            }

            $formvalues = 1;  
            // delete current user's last form preferences from cache table
            CRM_Core_BAO_Cache::deleteGroup( "gcc_refresh_customer_{$params['cid']}" );
            
            if($this->_actionString == "update") {
            // Insert current user's last selected form preferences into cache table
            CRM_Core_BAO_Cache::setItem( $formvalues, "gcc_refresh_customer_{$params['cid']}", 
                                         'Efficiency_Form_AddApplicant_Files', null );
            }
            
            /*** Delete & Insert records for current user in cache table - End ***/
            if ( $note_update ) {
                CRM_Core_Session::setStatus( ts(" Note Saved Successfully. "), false );
            }
            
            // Email Notification on Note edit
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
                //  Temporary Disabled Email notifications on Note update
                //  Enabled Email notifications on Note update

                require_once "Efficiency/BAO/Applicant.php";
                /* if ( !empty( $this->_auditor ) ){
                    // send mail to new auditor
                    $auditStatus = Efficiency_BAO_Applicant::sendNotificationMail( $this->_applicantId, $this->_auditor );
                    
                }*/
            }
            
        }
        parent::endPostProcess( );
        
    }
    
    
    /**
     * Return a descriptive name for the page, used in wizard header
     *
     * @return string
     * @access public
     */
    public function getTitle() {

        return ts('Note');
    }
    
}