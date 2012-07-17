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
require_once 'CRM/Core/Permission.php';
/**
 * Helper class to build navigation links
 */
class Efficiency_Form_AddApplicant_TabHeader {
    
    static function build( &$form ) {
        $tabs = $form->get( 'tabHeader' );
        if ( !$tabs || !CRM_Utils_Array::value('reset', $_GET) ) {
            $tabs =& self::process( $form );
            $form->set( 'tabHeader', $tabs );
        }
        $form->assign_by_ref( 'tabHeader', $tabs );
        $form->assign_by_ref( 'selectedTab', self::getCurrentTab($tabs) );
        return $tabs;
    }
   
    static function process( &$form ) {
        $action = $form->getVar( '_action' );
        $actionString = $form->getVar( '_actionString' );
       
        if ( $actionString == 'add' ) {
            $tabs = array(
                          'Summary'      => array( 'title'   => ts( 'CONTACT INFO' ),
                                                   'link'    => null,
                                                   'valid'   => false,
                                                   'active'  => false,
                                                   'current' => false,
                                                   ),
                          'Details'      => array( 'title'   => ts( 'HOUSEHOLD INFO' ),
                                                   'link'    => null,
                                                   'valid'   => false,
                                                   'active'  => false,
                                                   'current' => false,
                                                   ),
                          'Landlord'     => array( 'title'   => ts( 'LANDLORD INFO' ),
                                                   'link'    => null,
                                                   'valid'   => false,
                                                   'active'  => false,
                                                   'current' => false,
                                                   ),
                          'Note'         => array( 'title'   => ts( 'NOTES' ),
                                                   'link'    => null,
                                                   'valid'   => false,
                                                   'active'  => false,
                                                   'current' => false,
                                                   ),
                          
                          );
        } else if ( $actionString == 'update' || $actionString == 'view' ) {
            $tabs = array(
                               'Summary'        => array( 'title'   => ts( 'Contact Info' ),
                                                          'link'    => null,
                                                          'valid'   => true,
                                                          'active'  => true,
                                                          'current' => false,
                                                          ),
                               'Details'        => array( 'title'   => ts( 'Household Info' ),
                                                          'link'    => null,
                                                          'valid'   => true,
                                                          'active'  => true,
                                                          'current' => false,
                                                          ),
                               'Landlord'       => array( 'title'   => ts( 'Landlord Info' ),
                                                          'link'    => null,
                                                          'valid'   => true,
                                                          'active'  => true,
                                                          'current' => false,
                                                          ),
                               'Note'           => array( 'title'   => ts( 'Notes' ),
                                                          'link'    => null,
                                                          'valid'   => true,
                                                          'active'  => true,
                                                          'current' => false,
                                                          ),
                               'Assignaudit'    => array( 'title'   => ts( 'Assign Audit' ),
                                                          'link'    => null,
                                                          'valid'   => true,
                                                          'active'  => true,
                                                          'current' => false,
                                                          ),
                               'Files'          => array( 'title'   => ts( 'Files' ),
                                                          'link'    => null,
                                                          'valid'   => true,
                                                          'active'  => true,
                                                          'current' => false,
                                                          ),                               
                               'Projectdetails' => array( 'title'   => ts( 'Project Details' ),
                                                          'link'    => null,
                                                          'valid'   => true,
                                                          'active'  => true,
                                                          'current' => false,
                                                          ),
                          
                               );
           
        }
        $applicantId = $form->getVar( '_applicantId' );
        $context     = $form->getVar( '_contextView' );
        $measeures   = $form->getVar( '_measuresID' );
        $className   = CRM_Utils_String::getClassName( $form->getVar( '_name' ) );
      
        if ( array_key_exists( $className, $tabs ) ) {
           
            $tabs[$className]['current'] = true;
           
        }
        
        $reset = CRM_Utils_Array::value('reset', $_GET) ? 'reset=1&' : "";
        $qfKey = $form->get( 'qfKey' );
        
        
        $action = $actionString == 'view' ? 'view' : 'update';
       
        foreach ( $tabs as $key => $value ) {
            if($context){
                if($measeures){
                    $mid ='mid='.$measeures . '&'; 
                }else{
                    $mid =''; 
                }

            $tabs[$key]['link'] = 
                CRM_Utils_System::url( "civicrm/efficiency/applicant/" . strtolower($key) . "/" . $actionString,
                                       "{$reset}action={$action}&context={$context}&{$mid}cid={$applicantId}&snippet=4&qfKey={$qfKey}" );
            
            }else{
                $tabs[$key]['link'] = 
                CRM_Utils_System::url( "civicrm/efficiency/applicant/" . strtolower($key) . "/" . $actionString,
                                       "{$reset}action={$action}&cid={$applicantId}&snippet=4&qfKey={$qfKey}" ); 
            }
            if ( $action == 'view' ){
                if (! self :: checkViewPermission($key) ){
                    unset($tabs[$key]);
                }
               
            } else{
                
                if (! self :: checkUpdatePermission($key) ){
                    
                    if ( self :: checkViewPermission($key) ){
                       $tabs[$key]['link'] =  CRM_Utils_System::url( "civicrm/efficiency/applicant/" . strtolower($key) . "/" . $actionString,"{$reset}action=view&cid={$applicantId}&snippet=4&qfKey={$qfKey}");
                       $tabs[$key]['active'] = true;
                    }else{
                        //  $tabs[$key]['link'] =  CRM_Utils_System::url( "civicrm/efficiency/applicant/error","{$reset}action={$action}&cid={$applicantId}&snippet=4&qfKey={$qfKey}");
                        unset($tabs[$key]);
                    }
                } else {
                    $tabs[$key]['active'] = true;
                }
            }           
            
           
            $tabs[$key]['valid']  = true;
        }
       
        return $tabs;
       
    }
    
    static function reset( &$form ) {
        $tabs =& self::process( $form );
        $form->set( 'tabHeader', $tabs );
    }
    
    static function getCurrentTab( $tabs ) {
        static $current = false;
        
        if ( $current ) {
            return $current;
        }
        
        if ( is_array($tabs) ) {
            foreach ( $tabs as $subPage => $pageVal ) {
                if ( $pageVal['current'] === true ) {
                    $current = $subPage;
                    break;
                }
            }
        }
        
        $current = $current ? $current : 'Summary';
        return $current;
    }
    
    static function getNextSubPage( $form, $currentSubPage = 'Summary' ) {
        $tabs = self::build( $form );
        $flag = false;
        
        if ( is_array($tabs) ) {
            foreach ( $tabs as $subPage => $pageVal ) {
                if ( $flag && $pageVal['valid'] ) {
                    return $subPage;
                }
                if ( $subPage == $currentSubPage ) {
                    $flag = true;
                }
            }
        }
        return 'Summary';
    }

    static function checkViewPermission($tab){
        $checkKey = '';
        switch( $tab ) {
        case 'Summary':
            $checkKey = 'view_app_summary';   
            break;
        case 'Details':
            $checkKey = 'view_app_details';    
            break;
        case 'Landlord':
            $checkKey = 'view_app_landlord' ;   
            break;
        case 'Note':
            $checkKey = 'view_app_note'   ;   
            break;
        case 'Assignaudit':
            $checkKey =  'view_app_audit'   ;   
            break;
        case 'Files':
            $checkKey = 'view_app_files' ; 
            break;
        case 'Projectdetails':
            $checkKey = 'view_app_project' ;  
            break;
        default:
            break;
        }
        
        if ( CRM_Core_Permission::check( $checkKey ) ){
            
            return true;
        }else{
            return false;
                }
        
    }
    static function checkUpdatePermission($tab){
        
        $checkKey = '';
        switch( $tab ) {
        case 'Summary':
            $checkKey = 'edit_app_summary';
            break;
        case 'Details':
            $checkKey =  'edit_app_details';
            break;
        case 'Landlord':
            $checkKey = 'edit_app_landlord';
            break;
        case 'Note':
            $checkKey = 'edit_app_note';
            break;
        case 'Assignaudit':
            $checkKey = 'assign_app_audit';
            break;
        case 'Files':
            $checkKey = 'upload_app_files';
            break;
        case 'Projectdetails':
            $checkKey = 'edit_app_project';
            break;
        default:
            break;
        }
        
        if ( CRM_Core_Permission::check( $checkKey ) ){
            
            return true;
        }else{
            return false;
                }
        
        
    }



    
  }
