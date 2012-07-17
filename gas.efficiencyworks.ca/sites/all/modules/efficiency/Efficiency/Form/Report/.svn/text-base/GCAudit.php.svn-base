<?php

/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.1                                                |
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

require_once 'CRM/Report/Form.php';

class Efficiency_Form_Report_GCAudit extends CRM_Report_Form {
    
    protected $_customGroupExtends = array( 'Individual' );

    protected $_exposeContactID    = false;
    protected $_add2groupSupported = false;
    
    function __construct( ) {

        $this->_columns = array( 
                                'civicrm_contact' => 
                                array( 'dao'  => 'CRM_Contact_DAO_Contact',
                                       'fields'    =>
                                       array( 'sort_name'      => 
                                              array( 'title'      => ts( 'Display Name' ),
                                                     'default'      => true,
                                                     ),
                                              ),
                                       'grouping'  => 'contact-fields',
                                       ),
                                'civicrm_address' =>
                                array( 'dao'      => 'CRM_Core_DAO_Address',
                                       'fields'   =>
                                       array( 'street_address'    => 
                                              array( 'title' => ts( 'Street Address' ),
                                                     'default'      => true,
                                                     ),
                                              'city'              => 
                                              array( 'title' => ts( 'City' ),
                                                     'default'      => true,
                                                     ),
                                              ),
                                       'grouping'  => 'contact-fields',
                                       ),
                                'civicrm_phone'   =>
                                array( 'dao'       => 'CRM_Core_DAO_Phone',
                                       ),
                                'civicrm_email'   =>
                                array( 'dao'       => 'CRM_Core_DAO_Email',
                                       ),
                                'civicrm_group' => 
                                array( 'dao'    => 'CRM_Contact_DAO_Group',
                                       ),
                                'civicrm_group_contact' => 
                                array( 'dao'    => 'CRM_Contact_DAO_GroupContact',
                                       ),
                                'civicrm_relationship' => 
                                array( 'dao'    => 'CRM_Contact_DAO_Relationship',
                                       ),
                                 );
        parent::__construct( );
        
        foreach ( array('gcc_applicant', 'gcc_measures_other', 
                        'gcc_retrofit', 'gcc_misc', 'gcc_measures') as $table ) {
            foreach ( $this->_columns[$table]['fields'] as $cfId => $cfVal ) {
                if ( !in_array($cfVal['name'], 
                               array('file_identifier', 
                                     'audit_completed', 
                                     'retrofit_completed',
                                     'audit_type_id',
                                     'audit_invoiced',
                                     )) ) {
                    $this->_columns[$table]['grouping'] = 'contact-fields';
                    unset($this->_columns[$table]['fields'][$cfId]);
                } else {
                    $this->_columns[$table]['fields'][$cfId]['default'] = true;
                }
            }

            //unset($this->_columns[$table]['fields']);
            $this->_columns[$table]['alias'] = substr( $table, 4 );
            if ( !array_key_exists('dao', $this->_columns[$table]) ) {
                $this->_columns[$table]['dao'] = 'CRM_Contact_DAO_Contact';
            }
            foreach ( $this->_columns[$table]['filters'] as $cfId => $cfVal ) {
                if ( !in_array($cfVal['name'], array('file_identifier', 'audit_completed', 'audit_invoiced' )) ) {
                    unset($this->_columns[$table]['filters'][$cfId]);
                }
            }
        }
        
        // pane title
        $this->_columns['gcc_measures_other']['group_title'] = ts('Select Columns');
    }

    function from( ) {
        // fixme: relationship_type_id = 8 should be generalised

        $this->_from = "FROM gcc_applicant {$this->_aliases['gcc_applicant']}
INNER JOIN civicrm_contact {$this->_aliases['civicrm_contact']} 
           ON ({$this->_aliases['gcc_applicant']}.entity_id = {$this->_aliases['civicrm_contact']}.id)
LEFT  JOIN gcc_measures {$this->_aliases['gcc_measures']} 
           ON ({$this->_aliases['civicrm_contact']}.id = {$this->_aliases['gcc_measures']}.entity_id)
LEFT  JOIN gcc_measures_other {$this->_aliases['gcc_measures_other']} 
           ON ({$this->_aliases['civicrm_contact']}.id = {$this->_aliases['gcc_measures_other']}.entity_id)
LEFT  JOIN gcc_misc {$this->_aliases['gcc_misc']} 
           ON ({$this->_aliases['civicrm_contact']}.id = {$this->_aliases['gcc_misc']}.entity_id)
LEFT  JOIN civicrm_group_contact {$this->_aliases['civicrm_group_contact']} 
           ON ({$this->_aliases['civicrm_contact']}.id = {$this->_aliases['civicrm_group_contact']}.contact_id)
LEFT  JOIN civicrm_group {$this->_aliases['civicrm_group']} 
           ON ({$this->_aliases['civicrm_group_contact']}.group_id = {$this->_aliases['civicrm_group']}.id)
LEFT  JOIN civicrm_address {$this->_aliases['civicrm_address']} 
           ON ({$this->_aliases['civicrm_contact']}.id = {$this->_aliases['civicrm_address']}.contact_id AND 
               {$this->_aliases['civicrm_address']}.is_primary = 1)
LEFT  JOIN civicrm_phone   {$this->_aliases['civicrm_phone']} 
           ON ({$this->_aliases['civicrm_contact']}.id = {$this->_aliases['civicrm_phone']}.contact_id AND 
               {$this->_aliases['civicrm_phone']}.is_primary = 1)
LEFT  JOIN civicrm_relationship {$this->_aliases['civicrm_relationship']} 
           ON ({$this->_aliases['civicrm_contact']}.id = {$this->_aliases['civicrm_relationship']}.contact_id_b AND 
               {$this->_aliases['civicrm_relationship']}.relationship_type_id = 8 AND 
               {$this->_aliases['civicrm_relationship']}.end_date IS NULL)
LEFT  JOIN civicrm_contact contact_auditor 
           ON ({$this->_aliases['civicrm_relationship']}.contact_id_a = contact_auditor.id)"; 
    }

    function alterDisplay( &$rows ) {
        // fix for ids like audit_type_id
    }
}
