<?php
require_once 'CRM/Core/BAO/CustomValueTable.php';
require_once 'CRM/Core/BAO/CustomGroup.php';
require_once 'CRM/Core/BAO/CustomField.php';
class Efficiency_BAO_Applicant{

     /**
     *
     * Function to change QA status to OK
     * 
     * @param int $fileID id of the file to update
     *
     * @param string $role role of contact person 
     *
     * @access public
     * 
     * @static
     *
     */

    static function toggleStatus( $action, $contactID ) {
        if ( $action == 'toggle' ) {
            $file_id = self::getFieldValue( 'gcc_applicant', 'file_identifier', $contactID );
        }

        if ( $file_id ) {
             self::setFieldValue( 'gcc_applicant', 'qa_status', $contactID, 'OK' );
             $status = 'QA Status for File-ID "<b>'.$file_id.'</b>" has been changed to OK';
             CRM_Core_Session::setStatus(ts($status));
        }

        $csid = self::getListParticipantSearchID();
        if ( $csid ) {
            CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/contact/search/custom', 
                                                             '&csid='.$csid.'&reset=1&force=1')
                                       );
        }
        // $customParams =array(
        //                      'entityID' => $contactID,
        //                      'custom_1' => 1
        //                      );
        // $identifier  = CRM_Core_BAO_CustomValueTable::getValues( $customParams );       
        // if ( $identifier['custom_1'] ) {
        //     $Params =array(
        //                    'entityID' => $contactID,
        //                    'qa_status' => 'OK'
        //                    );
        //     CRM_Core_BAO_CustomValueTable::getValues( $Params );           
        //     $status = 'QA Status for File-ID "<b>'.$identifier.'</b>" has been changed to OK';
        //     CRM_Core_Session::setStatus(ts($status));
        // } 
        
        //CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/efficiency/contact/search'));
        
    }
    
    /**
     *
     * Function to change QA status to Review
     * 
     * @param int $contactID id of the contact person 
     *
     * @access public
     * 
     * @static
     *
     */
    static function setReview( $contactID ) {
        $fieldID     = CRM_Core_BAO_CustomField::getCustomFieldID( 'File_Identifier' );
        $customParams =array(
                             'entityID'         => $contactID,
                             'custom_'.$fieldID => 1
                             );
    
        $fileID  = CRM_Core_BAO_CustomValueTable::getValues( $customParams );
             
        if ( $fileID['custom_'.$fieldID] ) {
            self::setFieldValue( 'gcc_applicant', 'qa_status', $contactID, 'Review' );           
        }
    }

    static function getCustomTree($contctType, $self,$customGroupName ) { 
        require_once 'CRM/Core/BAO/CustomGroup.php';
       
        $groupID  = $grpID = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_CustomGroup", $customGroupName, 'id', 'name' );
      
        if( $groupID ) {
            
            $groupTree =& CRM_Core_BAO_CustomGroup::getTree( $contctType,
                                                             $self,
                                                             null,
                                                             $groupID,
                                                             null, null );
                    
            $form = null;
            $groupTree = CRM_Core_BAO_CustomGroup::formatGroupTree( $groupTree, null,$self );
            $self->_groupTree = $groupTree;               
            foreach ( $groupTree as $gID => $gVal ) {
                if (  is_array( $gVal['fields'] ) ){
                    foreach ( $gVal['fields'] as $fID => $fVal ) {
                        $elementArr[$fID] = $fVal['element_name'];
                        $len      = strlen($fVal['element_name'])-2;
                        $elementName = substr($fVal['element_name'], 0 ,$len);
                        $id       = substr($fVal['column_name'], 0 );
                        $fVal['element_name'] = $elementName;
                        $ids[$fVal['element_name']] = $id;
                        
                    }
                }
            }            
        }

        return $ids;
      
        
        
    }

    /**
     * Function to obtain application status.
     *
     * @return string
     * @access public
     */
    static function getAppStatus( $appID ) {

        $status     = 'none';
        $elctric    = false;
        $status_set = false;

        if ( defined('CIVICRM_EFFICIENCY_ELECTRIC') && CIVICRM_EFFICIENCY_ELECTRIC )
            $electric = true;
        else 
            $electric = false;

        $applicant_Status_id         = CRM_Core_BAO_CustomField::getCustomFieldID( 'Status', 'GCC_Applicant' );
        $fileID                      = self::getFieldValue( 'gcc_applicant', 'File_Identifier', $appID );
        $auto_status_id              = self::getFieldValue( 'gcc_applicant', 'auto_status', $appID );

        $close_no_potential_Status   = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                    'closed_no_potential', 
                                                                    'value', 'name' );

        $participant_withdrew_Status = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                    'closed_participant_withdrew', 
                                                                    'value', 'name' );
        
        if ( $close_no_potential_Status && $auto_status_id == $close_no_potential_Status )
            $status_set = true;

        if ( $participant_withdrew_Status && $auto_status_id == $participant_withdrew_Status )
            $status_set = true;

        if ( defined('CIVICRM_EFFICIENCY_ELECTRIC') && CIVICRM_EFFICIENCY_ELECTRIC ) {
            $report_to_LDC               = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                        'report_to_LDC', 'value', 'name' );

            if ( $report_to_LDC && $auto_status_id == $report_to_LDC )
                $status_set = true;
        }
        
        if ( !$electric && ( $auto_status_id != $close_no_potential_Status || $auto_status_id != $participant_withdrew_Status ) ) {
            if( $auto_status_id == $close_no_potential_Status ) {
                $status = "Closed - No Potential";
                return $status;
            } else if ($auto_status_id == $participant_withdrew_Status ) {
                $status = "Closed - Participant withdrew";
                return $status;
            }
        }
        
        
        if ( !( $status_set ) ) {
            // if ( $applicant_Status_id ) {
            //     $applicant_Status_params = array( 
            //                                      'version'                              => 3,
            //                                      'entity_id'                            => $appID,
            //                                      "return.custom_{$applicant_Status_id}" => 1
            //                                       );
                
            //     //Retreive custom field's value in the custom table
            //     $result['applicant_Status'] = @civicrm_api( 'custom_value', 'get', $applicant_Status_params );
            //     $applicant_Status = null;
            //     if ( isset( $result['applicant_Status']['values'] ) ) {
            //         $applicant_Status       = $result['applicant_Status']['values'][$applicant_Status_id]['latest'];
            //     }

            //     if ( $applicant_Status > 0 ) {
            //         if ( $applicant_Status == 1 ) {
            //             $status = "Closed - No potential";
            //             return $status;
            //         } else if ( $applicant_Status == 2 ) {
            //             $status = "Closed - Participant Withdrew";
            //             return $status;
            //         }
            //     }
            // }
            
            if ( $status == 'none' ) {
                //Retrieve cutom field ids
                $audit_invoiced_id    = CRM_Core_BAO_CustomField::getCustomFieldID( 'audit_invoiced', 'gcc_misc' );
                $retrofit_invoiced_id = CRM_Core_BAO_CustomField::getCustomFieldID( 'retrofit_invoiced', 'gcc_misc' );

                if ( $audit_invoiced_id && $retrofit_invoiced_id ) {
                    $audit_invoiced_params = array( 
                                                   'version'                            => 3,
                                                   'entity_id'                          => $appID,
                                                   "return.custom_{$audit_invoiced_id}" => 1
                                                    );
                    //Retreive custom field's value in the custom table
                    $result['audit_invoiced'] = @civicrm_api( 'custom_value', 'get', $audit_invoiced_params );
                    $audit_invoiced = null;
                    if ( isset( $result['audit_invoiced']['values'] ) ) {
                        $audit_invoiced       = $result['audit_invoiced']['values'][$audit_invoiced_id]['latest'];
                    }
            
                    $retrofit_invoiced_params = array( 
                                                      'version'                               => 3,
                                                      'entity_id'                             => $appID,
                                                      "return.custom_{$retrofit_invoiced_id}" => 1
                                                       );

                    //Retreive custom field's value in the custom table
                    $result['retrofit_invoiced'] = @civicrm_api( 'custom_value', 'get', $retrofit_invoiced_params );
                    $retrofit_invoiced = null;
                    if ( isset( $result['retrofit_invoiced']['values'] ) ) {
                        $retrofit_invoiced       = $result['retrofit_invoiced']['values'][$retrofit_invoiced_id]['latest'];
                    }

                    /*if ( $audit_invoiced && $retrofit_invoiced ) {
                        $status = "Project Completed";
                    }*/

                    if ( $retrofit_invoiced ) {
                        $status = "Project Completed";
                    }
                    
                }
            }

            if ( $status == 'none' ) {
            
                //Retrieve cutom field ids
                $retrofit_completed_id    = CRM_Core_BAO_CustomField::getCustomFieldID( 'retrofit_completed', 'gcc_measures_other' );
                $audit_completed_id = CRM_Core_BAO_CustomField::getCustomFieldID( 'audit_completed', 'gcc_measures_other' );
                if ( $retrofit_completed_id && $audit_completed_id ) {
                    $retrofit_completed_params = array( 
                                                       'version'                            => 3,
                                                       'entity_id'                          => $appID,
                                                       "return.custom_{$retrofit_completed_id}" => 1
                                                        );

                    //Retreive custom field's value in the custom table
                    $result['retrofit_completed'] = @civicrm_api( 'custom_value', 'get', $retrofit_completed_params );
                    $retrofit_completed = null;
                    if ( isset( $result['retrofit_completed']['values'] ) ) {
                        $retrofit_completed       = $result['retrofit_completed']['values'][$retrofit_completed_id]['latest'];
                    }
            
                    $audit_completed_params = array( 
                                                    'version'                               => 3,
                                                    'entity_id'                             => $appID,
                                                    "return.custom_{$audit_completed_id}" => 1
                                                     );
                
                    //Retreive custom field's value in the custom table
                    $result['audit_completed'] = @civicrm_api( 'custom_value', 'get', $audit_completed_params );
                    $audit_completed = null;
                    if ( isset( $result['audit_completed']['values'] ) ) {
                        $audit_completed       = $result['audit_completed']['values'][$audit_completed_id]['latest'];
                    }
            
                    if ( $retrofit_completed && $audit_completed ) {
                        $status = "Retrofit Completed";
                    } else if ( $audit_completed && !( isset( $retrofit_completed ) ) ) {
                        $status = "Retrofit Pending";
                    } else if ( $electric && ( $retrofit_completed || $audit_completed ) ) {
                        $status = "Ready for QA";
                    } 
                }
            }

            if ( $status == 'none' ) {

                require_once 'CRM/Contact/DAO/Relationship.php';
                $relationship =& new CRM_Contact_DAO_Relationship( );
                $relationship->contact_id_b         = $appID;
                $relationship->relationship_type_id = 10; //auditor type rel
                $relationship->end_date             = "NULL";
            
                if ( $relationship->find( true ) ) {
                    $status = "Audit Assigned";
                }
            }

            if( empty( $fileID ) ){
                $status = ( $status == 'none' ) ? "Applicant" : $status;
            } else {
                $status = ( $status == 'none' ) ? "New Participant" : $status;
            }
        } else {
            if ( $close_no_potential_Status && ( $auto_status_id == $close_no_potential_Status ) ) {
                $status =  CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                        'closed_no_potential', 
                                                        'label', 'name' );
            } else if ( $participant_withdrew_Status && ( $auto_status_id == $participant_withdrew_Status ) ) {
                $status = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                       'closed_participant_withdrew', 
                                                       'label', 'name' );
            } else if ( $report_to_LDC && ( $auto_status_id == $report_to_LDC ) ) {
                $status = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                       'report_to_LDC', 
                                                       'label', 'name' );
            } 
            
        }
       
        return $status;
           
        // }
            
    }
    

    /**
     * Process that send e-mails
     *
     * @return void
     * @access public
     */
    static function sendMail( $contactID, $relatorID ) {
        $template =& CRM_Core_Smarty::singleton( );

        require_once 'CRM/Contact/BAO/Contact/Location.php';
        
        $contactDetails = CRM_Contact_BAO_Contact_Location::getEmailDetails( $contactID );
        
        $relatorDetails= CRM_Contact_BAO_Contact_Location::getEmailDetails( $relatorID );
        
        /*if (empty($contactDetails[1]) || empty($relatorDetails[1])) {
            return false;
        }*/

        if ( empty( $contactDetails[1] ) ) {
            CRM_Core_Session::setStatus( ts( "Loggedin user's Email-Id not found." ) );
            return false;
        } elseif( empty( $relatorDetails[1] ) ) {
            CRM_Core_Session::setStatus( ts( "Auditor's Email-Id not found." ) );
            return false;
        }

        $template->assign( 'fromName', $contactDetails[0] );
        $template->assign( 'toName', $relatorDetails[0] );
        $subject = trim( $template->fetch( 'Efficiency/Form/ReceiptSubject.tpl' ) );
        $message = $template->fetch( 'Efficiency/Form/ReceiptMessage.tpl' );
        require_once 'CRM/Core/BAO/MessageTemplates.php';
        /*if ( !defined('CIVICRM_EFFICIENCY_ELECTRIC') && CIVICRM_EFFICIENCY_ELECTRIC ){
            $templatename = 'New Enbridge Audit';
            $messageTemplateID  = CRM_Core_DAO::getFieldValue( 'CRM_Core_DAO_MessageTemplates', 'New Enbridge Audit', 'id', 'msg_title' ); 
        } else {
            $templatename = 'New HAP Audit';
            $messageTemplateID  = CRM_Core_DAO::getFieldValue( 'CRM_Core_DAO_MessageTemplates', 'New HAP Audit', 'id', 'msg_title' );

        }*/

        if ( defined('CIVICRM_EFFICIENCY_ELECTRIC') && CIVICRM_EFFICIENCY_ELECTRIC ){
            $templatename = 'New HAP Audit';
            $messageTemplateID  = CRM_Core_DAO::getFieldValue( 'CRM_Core_DAO_MessageTemplates', 'New HAP Audit', 'id', 'msg_title' );             
        } else {
            $templatename = 'New Enbridge Audit';
            $messageTemplateID  = CRM_Core_DAO::getFieldValue( 'CRM_Core_DAO_MessageTemplates', 'New Enbridge Audit', 'id', 'msg_title' );
        }

        $params['from']              ='"' .  $contactDetails[0] . '" <' . $contactDetails[1] . '>';
        $params['toName']            = " $relatorDetails[0]";
        $params['toEmail']           = $relatorDetails[1] ;
        $params['messageTemplateID'] = $messageTemplateID;
        //return CRM_Core_BAO_MessageTemplates::sendTemplate($params);
        
        if( $messageTemplateID ) {
            $params['messageTemplateID'] = $messageTemplateID;
            return CRM_Core_BAO_MessageTemplates::sendTemplate($params);
        } else {
            CRM_Core_Session::setStatus( ts( "'".$templatename."' message template not found." ) );
            return false;
        }
     
    }


    /**
     * Process that send e-mails
     *
     * @return void
     * @access public
     */
    static function sendNotificationMail( $contactID, $relatorID ) {
        $template =& CRM_Core_Smarty::singleton( );
        $session =& CRM_Core_Session::singleton( );
        $loggedInUserID = $session->get('userID');
        require_once 'CRM/Contact/BAO/Contact/Location.php';
        
        $contactDetails = CRM_Contact_BAO_Contact_Location::getEmailDetails( $loggedInUserID );
        $relatorDetails= CRM_Contact_BAO_Contact_Location::getEmailDetails( $relatorID );
                
        if (empty($contactDetails[1]) || empty($relatorDetails[1])) {
            return false;
        }
        
        $template->assign( 'fromName', $contactDetails[0] );
        $template->assign( 'toName', $relatorDetails[0] );
        require_once 'CRM/Core/BAO/MessageTemplates.php';
        if ( !defined('CIVICRM_EFFICIENCY_ELECTRIC') && CIVICRM_EFFICIENCY_ELECTRIC ) {
            $messageTemplateID  = CRM_Core_DAO::getFieldValue( 'CRM_Core_DAO_MessageTemplates', 'New Enbridge Audit Notification', 'id', 'msg_title' );
        } else {
            $messageTemplateID  = CRM_Core_DAO::getFieldValue( 'CRM_Core_DAO_MessageTemplates', 'Refresh FAST from efficiencyworks.ca', 'id', 'msg_title' );
        }
        $params['from']              ='"' .  $contactDetails[0] . '" <' . $contactDetails[1] . '>';
        $params['toName']            = " $relatorDetails[0]";
        $params['toEmail']           = $relatorDetails[1] ;
        $params['messageTemplateID'] = $messageTemplateID;
          
        require_once 'Efficiency/BAO/Export.php';
        $export =& new Efficiency_BAO_Export( $contactID );
        $output = $export->export();
        $filename =  $export->fileID;
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

        // CSV should not accompany the email while sending Email notification on Summary, Household, Landlord, Note Updates
        /* $params['attachments'][]       = array(
                                             'fullPath'  => $writeTo,
                                             'mime_type' => 'text/x-csv',
                                             'cleanName' => $filename
                                             );
        */
        return CRM_Core_BAO_MessageTemplates::sendTemplate($params);
        
    }


    
    static function getFieldValue( $tableName, $column, $contactID ) {
        $sql    = "SELECT {$column} FROM {$tableName} WHERE entity_id={$contactID}";
        $params = array( );
        require_once 'CRM/Core/DAO.php';
        $result = CRM_Core_DAO::singleValueQuery( $sql, $params );
        return $result;
    }

    /**
     * Set Value to column in Table
     * @param tableName
     * @param column to set value to
     * @param contactID or entity id
     * @param value to be set to the column
     **/
    static function setFieldValue( $tableName, $column, $contactID, $value ) {
        $sql    = "UPDATE {$tableName} SET {$column} = '{$value}' WHERE entity_id={$contactID}";
        $params = array( );
       
        require_once 'CRM/Core/DAO.php';
        $result = CRM_Core_DAO::executeQuery( $sql );
        return $result;
    }
    
    /**
     * Function to set auto_status in gcc_applicant
     *
     * @param entity_id
     * @return Status Label
     * @access public
     **/
    static function setStatus( $entityID , $overrideStatus = false ) {
        $status       = 'none';
        $status_value = null;
        $elctric      = false;
        $status_set   = false;

        if ( defined('CIVICRM_EFFICIENCY_ELECTRIC') && CIVICRM_EFFICIENCY_ELECTRIC )
            $electric = true;
        else 
            $electric = false;

        $auto_status_id              = self::getFieldValue( 'gcc_applicant', 'auto_status', $entityID );
        $close_no_potential_Status   = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                    'closed_no_potential', 
                                                                    'value', 'name' );

        $participant_withdrew_Status = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue",
                                                                    'closed_participant_withdrew', 
                                                                    'value', 'name' );

        if ( $close_no_potential_Status && $auto_status_id == $close_no_potential_Status )
            $status_set = true;

        if ( $participant_withdrew_Status && $auto_status_id == $participant_withdrew_Status )
            $status_set = true;

        if ( defined('CIVICRM_EFFICIENCY_ELECTRIC') && CIVICRM_EFFICIENCY_ELECTRIC ) {
            $report_to_LDC               = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                                                                        'report_to_LDC', 'value', 'name' );

            if ( $report_to_LDC && $auto_status_id == $report_to_LDC)
                $status_set = true;
        }
        
                if ( !$electric && ( $auto_status_id != $close_no_potential_Status || $auto_status_id != $participant_withdrew_Status ) ) {
             // return $status;
              if( $auto_status_id == $close_no_potential_Status ) {
                 //  $close_no_potential_label   = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                 // 'closed_no_potential', 
                 //   'label', 'name' );
                 $status = "Closed - No Potential";
                 return $status;
             } else if ($auto_status_id == $participant_withdrew_Status ) {
                 
             //$participant_withdrew_label   = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
                 //  'closed_participant_withdrew', 
                 //  'label', 'name' );
                 $status = "Closed - Participant withdrew";
                 return $status;
                 }
                 }
             //  }
         
        // if ( !( $status_set ) ) {
            if ( $status == 'none' ) {
                //Retrieve cutom field ids
                
                $audit_invoiced    = self::getFieldValue( 'gcc_misc', 'audit_invoiced', $entityID );
                $retrofit_invoiced = self::getFieldValue( 'gcc_misc', 'retrofit_invoiced', $entityID );
                $File_ID = self::getFieldValue( 'gcc_applicant', 'File_Identifier', $entityID );
                               
                /*if ( $audit_invoiced && $retrofit_invoiced ) {
                    $status = "Project Completed";
                }*/
                if ( $retrofit_invoiced ) {
                    $status = "Project Completed";
                }
            }
            
            if ( $status == 'none' ) {
                $retrofit_completed = self::getFieldValue( 'gcc_measures_other', 'retrofit_completed', $entityID );
                $audit_completed    = self::getFieldValue( 'gcc_measures_other', 'audit_completed', $entityID );

                if ( $retrofit_completed && $audit_completed ) {
                    $status = "Retrofit Completed";
                } else if ( $audit_completed && !( isset( $retrofit_completed ) ) ) {
                    $status = "Retrofit Pending";
                }//  else if ( $electric && ( $retrofit_completed || $audit_completed ) ) {
//                     $status = "Ready for QA";
//                 } 
               
            }

            if ( $status == 'none' ) {

                require_once 'CRM/Contact/DAO/Relationship.php';
                $relationship =& new CRM_Contact_DAO_Relationship( );
                $relationship->contact_id_b         = $entityID;
                $relationship->relationship_type_id = 10; //auditor type rel
                $relationship->end_date             = "NULL";
            
                if ( $relationship->find( true ) ) {
                    $status = "Audit Assigned";
                }
               
            }

            if ( empty( $File_ID ) ) {
                $status = ( $status == 'none' ) ? "Applicant" : $status;
            } else {
                $status = ( $status == 'none' ) ? "New Participant" : $status;
            }

            //find current status's option value 
            $auto_status       = self::getFieldValue( 'gcc_applicant', 'auto_status', $entityID );
            $auto_status_label = null;
            $op_grp = array( 
                            'name' => 'project_details_status',
                            'version' => 3,
                             );
            
            require_once 'api/api.php';
            $op_grp_id = civicrm_api( 'option_group', 'get', $op_grp ) ;
        
            if ( isset( $op_grp_id['id'] ) ) {
                $op_grp_val = array(
                                    'option_group_id' => $op_grp_id['id'],
                                    'version' => 3,
                                    );
                $optionValues = civicrm_api( 'option_value', 'get', $op_grp_val );
                if ( isset( $optionValues['values'] ) ) {
                    foreach ( $optionValues['values'] as $opKey => $opVal ) {
                        $options[$opVal['label']] = $opVal['value'];
                        if ( $opVal['label'] == $status ) {
                            $status_value = $opVal['value'];
                        }
                        if ( $opVal['value'] == $auto_status ) {
                            $auto_status_label = $opVal['label'];
                        }
                    }
                }
            }
            if ( isset( $status_value ) ) {
                if ( $auto_status && !$overrideStatus ) {
                    // if calculated status is of greater priority then update auto_status in gcc_applicant
                    if ( $status_value > $auto_status ) {
                        $update_autoStatus = self::setFieldValue( 'gcc_applicant', 'auto_status', $entityID, $status_value );
                        return $status;
                    } else {
                        return $auto_status_label;
                    }
                } else {  
                    // update gcc_applicant if auto_status with calculated status if auto_status is not set
                    $update_autoStatus = self::setFieldValue( 'gcc_applicant', 'auto_status', $entityID, $status_value );
                    return $status;
                }
            }
        // } else {
//             if ( $close_no_potential_Status && ( $auto_status_id == $close_no_potential_Status ) ) {
//                 $status =  CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
//                                                         'closed_no_potential', 
//                                                         'label', 'name' );
//             } else if ( $participant_withdrew_Status && ( $auto_status_id == $participant_withdrew_Status ) ) {
//                 $status = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
//                                                        'closed_participant_withdrew', 
//                                                        'label', 'name' );
//             } else if ( $report_to_LDC && ( $auto_status_id == $report_to_LDC ) ) {
//                 $status = CRM_Core_DAO::getFieldValue( "CRM_Core_DAO_OptionValue", 
//                                                        'report_to_LDC', 
//                                                        'label', 'name' );
//             }          
//         }
        return $status;
        //}
    }

    /**
     * Set Staus Label 
     * @param OptionValue value of the option
     * @return Status label
     **/
    static function setStatusLabel( $optionValue ) {
        $statusLabel = '';
        $op_grp = array( 
                        'name' => 'project_details_status',
                        'version' => 3,
                         );
            
        require_once 'api/api.php';
        $op_grp_id = civicrm_api( 'option_group', 'get', $op_grp );
        
        if ( isset( $op_grp_id['id'] ) ) {
            $op_grp_val = array(
                                'option_group_id' => $op_grp_id['id'],
                                'version' => 3,
                                );
            $optionValues = civicrm_api( 'option_value', 'get', $op_grp_val );
            
            if ( isset( $optionValues['values'] ) && $optionValue ) {
                foreach ( $optionValues['values'] as $opKey => $opVal ) {

                    //Check for matching option value
                    if ( $opVal['value'] == $optionValue ) {
                        $statusLabel = $opVal['label']; // Set option Label
                        break;
                    }
                }
            }
        }
         return $statusLabel;
    }
    
    /**
     * Function to find the ListParticipants custom search ID
     * @return custom search ID of ListParticipants
     **/
    static function getListParticipantSearchID( ) {    
        $op_grp = array( 
                        'name' => 'custom_search',
                        'version' => 3,
                         );
            
        require_once 'api/api.php';
        $op_grp_id = civicrm_api( 'option_group', 'get', $op_grp );
        if ( isset( $op_grp_id['id'] ) ) {
            $op_grp_val = array(
                                'option_group_id' => $op_grp_id['id'],
                                'version' => 3,
                                );
            $optionValues = civicrm_api( 'option_value', 'get', $op_grp_val );
            if ( isset( $optionValues['values'] ) ) {
                foreach ( $optionValues['values'] as $opKey => $opVal ) {

                    //Check for matching option value
                    if ( $opVal['label'] == 'Efficiency_Form_Search_Custom_ListParticipant' ) {
                        $custom_search_ID = $opVal['value']; // Set option Label
                        return $custom_search_ID;
                    }
                }
            }
        }
    }
    
    /**
     * Function to return status 
     * @param entityID or applicantID 
     * @return status Label
     **/
    function getStatusLabel( $entityId ) {
        
        $status_id   = self::getFieldValue( 'gcc_applicant', 'auto_status', $entityId );
        if ( $status_id ) {
            $statusLabel = self::setStatusLabel( $status_id );
        } else {
            $statusLabel = self::setStatus( $entityId );
        }
        return $statusLabel;
    }
}


?>
