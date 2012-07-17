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

require_once 'Efficiency/Form/Report.php';

/**

 * Inventory Report

 * 

 */

class Efficiency_Form_Report_Inventory extends Efficiency_Form_Report
{
  
    /**

     * Function to set variables up before form is built
     
     *
     
     * @return void
     
     * @access public
     
    */
    
    public function preProcess()
        
    {

        parent::preProcess();


        if ( ! ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) ) {
            $this->_formFields = array(
                                       
                                       'file_identifier'    => array(
                                                                     
                                                                 'title'      => 'FileID',
                                                                 
                                                                 'tableAlias' => 'ga'
                                                                 
                                                                 ),
                                       
                                       'audit_completed'    => array(
                                                                     
                                                                     'title'      => 'Audit Completed',
                                                                     
                                                                     'tableAlias' => 'gmo'

                                                                     ),
                                       
                                       'retrofit_completed'    => array(

                                                                        'title'      => 'Retrofit Completed',
                                                                        
                                                                        'tableAlias' => 'gmo'

                                                                        ),
                                       
                                       );
        } else {

            $this->_formFields = array(
                                       
                                       'file_identifier'    => array(
                                                                     
                                                                     'title'      => 'OPA FileID',

                                                                     'tableAlias' => 'ga'
                                                                     
                                                                 ),
                                       
                                       'audit_completed'    => array(
                                                                     
                                                                     'title'      => 'Audit Completed',
                                                                     
                                                                     'tableAlias' => 'gmo'
                                                                     
                                                                     ),
                                       
                                       'retrofit_completed'    => array(
                                                                        
                                                                        'title'      => 'Retrofit Completed',
                                                                        
                                                                        'tableAlias' => 'gmo'
                                                                        
                                                                        ),

                                       );
            
        }
        
        $this->assign('formFields', $this->_formFields);

        //$display_name = "CONCAT( `first_name`, ' ', `last_name` )";
        $display_name = 'display_name';
        $selectFields      = array(

                                   'postal_code'        => array(

                                                                 'title'      => 'Postal Code',

                                                                 'tableAlias' => 'ca'

                                                                 ),

                                   'phone'              => array(

                                                                 'title'      => 'Telephone',

                                                                 'tableAlias' => 'cp'

                                                                 ),

                                   'ldc_acct'           => array(

                                                                 'title'      => 'LDC Acct',

                                                                 'tableAlias' => 'ga'

                                                                 ),

                                   'full_name'       => array(

                                                                 'title'      => 'Auditor',

                                                                 'tableAlias' => 'contact_auditor'

                                                                 ),

                                   'software'           => array(

                                                                 'title'      => 'Software',

                                                                 'tableAlias' => 'gmo'

                                                                 ),

                                   'tenure'             => array(

                                                                 'title'      => 'Tenure',

                                                                 'tableAlias' => 'ga'

                                                                 ),

                                   'occupants'          => array(

                                                                 'title'      => 'Occupants',

                                                                 'tableAlias' => 'ga'

                                                                 ),

                                   'wac'                => array(

                                                                 'title'      => 'WAC',

                                                                 'tableAlias' => 'gmo'

                                                                 ),

                                   'computers'          => array(

                                                                 'title'      => 'Comp',

                                                                 'tableAlias' => 'gmo'

                                                                 ),

                                   'fridges'            => array(

                                                                 'title'      => 'Fridges',

                                                                 'tableAlias' => 'gmo'

                                                                 ),

                                   'frzrs'              => array(

                                                                 'title'      => 'Freeze',

                                                                 'tableAlias' => 'gmo'

                                                                 ),

                                   'shhd_flow'          => array(

                                                                 'title'      => 'SHH Before',

                                                                 'tableAlias' => 'gmo'

                                                                 ),

                                   'shhd_flow_after'    => array(

                                                                 'title'      => 'SHH After',

                                                                 'tableAlias' => 'gmo'

                                                                 ),

                                   'potential_costs'    => array(

                                                                 'title' => 'Potential Costs',

                                                                 'tableAlias' => 'gmo'

                                                                 ),

                                   'potential_kwh'      => array(

                                                                 'title' => 'Potential kWh',

                                                                 'tableAlias' => 'gmo'

                                                                 ),

                                   'potential_kw_s'     => array(

                                                                 'title' => 'Potential KW-S',

                                                                 'tableAlias' => 'gmo'

                                                                 ),

                                   'potential_kw_w'     => array(

                                                                 'title' => 'Potential KW-W',

                                                                 'tableAlias' => 'gmo'

                                                                 ),

                                   'potential_npv'      => array(

                                                                 'title' => 'Potential NPV',

                                                                 'tableAlias' => 'gmo'

                                                                 ),

                                   'retrofit_completed' => array(

                                                                 'title' => 'Retrofit Completed',

                                                                 'tableAlias' => 'gmo'

                                                                 ),

                                   'job_costs'          => array(

                                                                 'title' => 'Job Costs',

                                                                 'tableAlias' => 'gmo'

                                                                 ),

                                   'job_kwh'            => array(

                                                                 'title' => 'Job kWh',

                                                                 'tableAlias' => 'gmo'

                                                                 ),

                                   'job_kw_s'           => array(

                                                                 'title' => 'Job KW-S',

                                                                 'tableAlias' => 'gmo'

                                                                 ),

                                   'job_kw_w'           => array(

                                                                 'title' => 'Job KW-W',

                                                                 'tableAlias' => 'gmo'

                                                                 ),

                                   'job_npv'            => array(

                                                                 'title' => 'Job NPV',

                                                                 'tableAlias' => 'gmo'

                                                                 ),

                                   'audit_type_id'      => array(
                                                                 
                                                                 'title' => 'Audit TIME',
                                                                 
                                                                 'tableAlias' => 'gmo'
                                                                 
                                                                 ),
                                   
                                   'audit_type_text'    => array(
                                                                 
                                                                 'title' => 'Audit TYPE',
                                                                 
                                                                 'tableAlias' => 'gmo'
                                                                 
                                                                 ),
                                   
                                   'gas_acct'           => array(
                                                                 
                                                                 'title' => 'Gas Acct',
                                                                 
                                                                 'tableAlias' => 'ga'
                                                                 
                                                                 ),
                                   
                                   'job_m3saved'        => array(
                                                                 
                                                                 'title' => 'Job m3',
                                                                 
                                                                 'tableAlias' => 'gmo'
                                                                 
                                                                 ) 
                                   
                                   );
        


        if ( ! ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) ) {

            $selectGasFields = array( 
                                     'base_gas_m3'      => array(
                                                                 
                                                                 'title'      => 'Base gas m3',
                                                                 
                                                                 'tableAlias' => 'gmo'
                                                                 
                                                                 ),
                                     'corrections'      => array(
                                                                 
                                                                 'title' => "Auditor's Notes",
                                                                 
                                                                 'tableAlias' => 'ga'
                                                                 
                                                                 )
                                      );

            $selectFields  = array_merge( $selectFields, $selectGasFields );
        }
        $this->_selectFields = array_merge($this->_selectFields, $selectFields, $this->_formFields);
        
        
        
        require_once 'CRM/Core/OptionGroup.php';
        
        $this->_optionGroups = array(
                                     
                                     'gas_util_id'    => array(
                                                               
                                                               'title'     => 'Gas LDC',
                                                               
                                                               'tableAlias'=> 'ga',
                                                               
                                                               'options'   => CRM_Core_OptionGroup::values('gas_util'),
                                                               
                                                               ), 
                                     
                                     'ldc_id'         => array(
                                                               
                                                               'title'     => 'LDC',
                                                               
                                                               'tableAlias'=> 'ga',
                                                               
                                                               'options'   => CRM_Core_OptionGroup::values('ldc'),

                                                               ),

                                     'income_basis_id'=> array(

                                                               'title'     => 'Income Basis',

                                                               'tableAlias'=> 'ga',

                                                               'options'=> CRM_Core_OptionGroup::values('income_basis'),

                                                               ),

                                     'referral_id'    => array(

                                                               'title'     => 'Referral',

                                                               'tableAlias'=> 'ga',

                                                               'options'   => CRM_Core_OptionGroup::values('referral'),

                                                               ),

                                     
                                     'pheat_fuel_id'  => array(

                                                               'title'     => 'SpHtFuel',

                                                               'tableAlias'=> 'ga',

                                                               'options'   => CRM_Core_OptionGroup::values('pheat_fuel'),

                                                               ),

                                     'dhw_fuel_id'    => array(

                                                               'title'     => 'DHW Fuel',

                                                               'tableAlias'=> 'ga',

                                                               'options'   => CRM_Core_OptionGroup::values('dhw_fuel'),

                                                               ),

                                     );

        if ( ! ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) ) {
            $this->_optionGroups['central_air_id'] = array(

                                                               'title'     => 'Central Air',

                                                               'tableAlias'=> 'ga',

                                                               'options' => CRM_Core_OptionGroup::values('central_air'),

                                                           );
            }


    }



    /**

     * name of export file.

     *

     * @param string $output type of output

     * @return string name of the file

     */

    public function getExportFileName( $output = 'csv') {

        return ts('Inventory Report');

    }



    /**

     * returns the header sequence to be used in report

     *

     * @return Array

     */

    public function getHeaderSequence() {

        if ( ! ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) ) {

            return array(
                         
                         $this->_selectFields['file_identifier']['title'], 
                         
                         $this->_selectFields['display_name']['title'], 
                         
                         $this->_selectFields['street_address']['title'],
                         
                         $this->_selectFields['city']['title'], 
                         
                         
                     
                         $this->_selectFields['postal_code']['title'], 
                         
                         $this->_selectFields['phone']['title'], 
                         
                         $this->_selectFields['audit_completed']['title'],
                         
                         
                         
                         $this->_selectFields['audit_type_text']['title'],
                         
                         $this->_selectFields['audit_type_id']['title'],
                         
                         $this->_optionGroups['ldc_id']['title'],
                         
                         
                         
                         $this->_selectFields['ldc_acct']['title'],

                         $this->_selectFields['base_gas_m3']['title'],
                         
                         $this->_optionGroups['gas_util_id']['title'],
                         
                         $this->_selectFields['gas_acct']['title'],
                         
                         $this->_selectFields['full_name']['title'],
                         
                         $this->_selectFields['software']['title'],
                         
                         $this->_selectFields['tenure']['title'],
                         

                         
                         $this->_optionGroups['income_basis_id']['title'],
                         
                         $this->_optionGroups['referral_id']['title'],
                         
                         

                         $this->_selectFields['occupants']['title'],
                         
                         
                         
                         $this->_optionGroups['central_air_id']['title'],
                         

                         
                         $this->_selectFields['wac']['title'],
                         
                         
                         
                         $this->_optionGroups['pheat_fuel_id']['title'],
                         
                         $this->_optionGroups['dhw_fuel_id']['title'],
                         
                         

                         $this->_selectFields['computers']['title'],
                         
                         $this->_selectFields['fridges']['title'],
                         
                         $this->_selectFields['frzrs']['title'],
                         
                         $this->_selectFields['shhd_flow']['title'],
                         
                         $this->_selectFields['shhd_flow_after']['title'],

                         
                         
                         $this->_selectFields['potential_costs']['title'], 
                         
                         $this->_selectFields['potential_kwh']['title'], 
                         
                         $this->_selectFields['potential_kw_s']['title'], 

                         $this->_selectFields['potential_kw_w']['title'], 
                         
                         $this->_selectFields['potential_npv']['title'], 
                         

                         
                         $this->_selectFields['retrofit_completed']['title'],
                         
                         
                         
                         $this->_selectFields['job_costs']['title'], 
                         
                         $this->_selectFields['job_kwh']['title'], 

                         $this->_selectFields['job_kw_s']['title'], 
                         
                         $this->_selectFields['job_kw_w']['title'], 
                         
                         $this->_selectFields['job_m3saved']['title'],
                         
                         $this->_selectFields['job_npv']['title'], 
                         
                         
                         
                         $this->_selectFields['corrections']['title'],
                     
                     );
        } else {

              return array(
                         
                         $this->_selectFields['file_identifier']['title'], 
                         
                         $this->_selectFields['display_name']['title'], 
                         
                         $this->_selectFields['street_address']['title'],
                         
                         $this->_selectFields['city']['title'], 
                         
                         
                     
                         $this->_selectFields['postal_code']['title'], 
                         
                         $this->_selectFields['phone']['title'], 
                         
                         $this->_selectFields['audit_completed']['title'],
                         
                         
                         
                         $this->_selectFields['audit_type_text']['title'],
                         
                         $this->_selectFields['audit_type_id']['title'],
                         
                         $this->_optionGroups['ldc_id']['title'],
                         
                         
                         
                         $this->_selectFields['ldc_acct']['title'],
                         
                         $this->_optionGroups['gas_util_id']['title'],
                         
                         $this->_selectFields['gas_acct']['title'],
                         
                         $this->_selectFields['full_name']['title'],
                         
                         $this->_selectFields['software']['title'],
                         
                         $this->_selectFields['tenure']['title'],
                         

                         
                         $this->_optionGroups['income_basis_id']['title'],
                         
                         $this->_optionGroups['referral_id']['title'],
                         
                         

                         $this->_selectFields['occupants']['title'],
                         
                         
                         
                         $this->_optionGroups['central_air_id']['title'],
                         

                         
                         $this->_selectFields['wac']['title'],
                         
                         
                         
                         $this->_optionGroups['pheat_fuel_id']['title'],
                         
                         $this->_optionGroups['dhw_fuel_id']['title'],
                         
                         

                         $this->_selectFields['computers']['title'],
                         
                         $this->_selectFields['fridges']['title'],
                         
                         $this->_selectFields['frzrs']['title'],
                         
                         $this->_selectFields['shhd_flow']['title'],
                         
                         $this->_selectFields['shhd_flow_after']['title'],

                         
                         
                         $this->_selectFields['potential_costs']['title'], 
                         
                         $this->_selectFields['potential_kwh']['title'], 
                         
                         $this->_selectFields['potential_kw_s']['title'], 

                         $this->_selectFields['potential_kw_w']['title'], 
                         
                         $this->_selectFields['potential_npv']['title'], 
                         

                         
                         $this->_selectFields['retrofit_completed']['title'],
                         
                         
                         
                         $this->_selectFields['job_costs']['title'], 
                         
                         $this->_selectFields['job_kwh']['title'], 

                         $this->_selectFields['job_kw_s']['title'], 
                         
                         $this->_selectFields['job_kw_w']['title'], 
                         
                         $this->_selectFields['job_m3saved']['title'],
                         
                         $this->_selectFields['job_npv']['title'], 
                         
                         
                         
                         $this->_selectFields['corrections']['title'],
                     
                     );

        }
        
    }
    
    

    /**

     * returns the result sequence to be used in report

     *

     * @return Array

     */

    public function getResultSequence() {

        return $this->getHeaderSequence();

    }

    

    /**

     * returns the title header to be used in report

     *

     * @return Array

     */

    public function getTitleHeader( $params ) {
        if ( ! ( defined( 'CIVICRM_EFFICIENCY_ELECTRIC' ) && CIVICRM_EFFICIENCY_ELECTRIC ) ) {
            $titleHeader = " Inventory Report:\n, ";
        } else {
            
            $titleHeader = ", Home Assistance Program \n,Inventory Report:\n, ";
        }
        
        $dateFrom = CRM_Utils_Date::format( array_reverse( $params['audit_completed_low'] ),'-' );

        if ($dateFrom) {

            $dateFrom = CRM_Utils_Date::customFormat($dateFrom, '%d/%b/%Y');

            $titleHeader .= "Audit Comleted From";

            $titleHeader .= " $dateFrom ";

        }



        $dateTo = CRM_Utils_Date::format( array_reverse( $params['audit_completed_high'] ),'-' );

        if ($dateTo) {

            $dateTo = CRM_Utils_Date::customFormat($dateTo, '%d/%b/%Y');

            $titleHeader .= "- $dateTo ";

        }

        

        $dateFromRetrofit = CRM_Utils_Date::format( array_reverse( $params['retrofit_completed_low'] ),'-' );

        if ($dateFromRetrofit) {

            $dateFromRetrofit = CRM_Utils_Date::customFormat($dateFromRetrofit, '%d/%b/%Y');

            if ( $dateFrom ) {

                $titleHeader .= "And ";

            }

            $titleHeader .= "Retrofit Completed From";

            $titleHeader .= " $dateFromRetrofit ";

        }

        $dateToRetrofit = CRM_Utils_Date::format( array_reverse( $params['retrofit_completed_high'] ),'-' );

        if ($dateToRetrofit) {

            $dateToRetrofit = CRM_Utils_Date::customFormat($dateToRetrofit, '%d/%b/%Y');

            $titleHeader .= "- $dateToRetrofit ";

        }

        $titleHeader .= "\n";

        return $titleHeader;

    }



    public function buildWhereClause( $params ) {

        foreach($params as $key => $val ) {

            if ($key != 'qfKey') {

                if( $key == 'retrofit_completed_low' ) {

                    $retrofit_completed[$key] = $val;

                    unset($params[$key]);

                } elseif ($key == 'retrofit_completed_high' ) {

                    $retrofit_completed[$key] = $val;

                    unset($params[$key]);

                } elseif ($key == 'audit_completed_low') {

                    $audit_completed[$key] = $val;

                    unset($params[$key]);

                } elseif ($key == 'audit_completed_high') {

                    $audit_completed[$key] = $val;

                    unset($params[$key]);

                } 

            }

        }

        $where    = parent::buildWhereClause( $params );

        $retrofit = parent::buildWhereClause( $retrofit_completed );

        $audit    = parent::buildWhereClause( $audit_completed );

        

        $clause = false;

        if ( $audit &&  $audit!= 1 ) {

            $clause = "({$audit})";

        }

        if ( $retrofit && $retrofit != 1 ) {

            $clause = $clause ? "({$clause} OR ({$retrofit}))" : "({$retrofit})";

        }

        if ( $clause ) {

            $where = "$where AND {$clause}";

        }

        return $where ? $where : true;

    }

}



?>

