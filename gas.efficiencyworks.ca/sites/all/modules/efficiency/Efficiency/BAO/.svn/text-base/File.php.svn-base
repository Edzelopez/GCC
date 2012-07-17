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

require_once 'CRM/Core/DAO/File.php';

/**
 * BAO object for crm_log table
 */

class Efficiency_BAO_File extends CRM_Core_DAO_File {

    public function delete($fileID , $entityID) {
          
        require_once "CRM/Core/DAO/EntityFile.php";
        $entityFileDAO = new CRM_Core_DAO_EntityFile();
        $entityFileDAO->file_id      = $fileID;
        $entityFileDAO->entity_id    = $entityID;
        
        if ( $entityFileDAO->find(true) ) {
            $result =   $entityFileDAO->delete();
        } else {
            CRM_Core_Error::fatal( );
        }
        
        require_once "CRM/Core/DAO/File.php";
        $fileDAO = new CRM_Core_DAO_File();
        $fileDAO->id = $fileID;
        if ( $fileDAO->find(true) ) {
            $fileDAO->delete();
        } else {
            CRM_Core_Error::fatal( );
        }
        
    }
   

public function _dumpCSVHeader( $fileName ) {
        $now       = gmdate('D, d M Y H:i:s') . ' GMT';
        $mime_type = 'text/x-csv';
        $ext       = 'csv';

        $fileName = CRM_Utils_String::munge( $fileName );
        $fileName = str_replace('_', '-', $fileName); // for Gcc

        $config =& CRM_Core_Config::singleton( );       
        header('Content-Type: ' . $mime_type); 
        header('Expires: ' . $now);
        
        // lem9 & loic1: IE need specific headers
        $isIE = strstr( $_SERVER['HTTP_USER_AGENT'], 'MSIE' );
        if ( $isIE ) {
            header('Content-Disposition: inline; filename="' . $fileName . '.' . $ext . '"');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
        } else {
            header('Content-Disposition: attachment; filename="' . $fileName . '.' . $ext . '"');
            header('Pragma: no-cache');
        }

    }


 
    
}

