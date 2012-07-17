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

 * LDC Final Report

 * 

 */

class Efficiency_Form_Report_LDCFinal extends Efficiency_Form_Report
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



        $this->_formFields = array(

                                   /*'ldc_id'            => array(

                                                                'title'      => 'LDC',

                                                                'tableAlias' => 'ga'

                                                                ),
                                   'funder'            => array(

                                                                'title'      => 'Funder',

                                                                'tableAlias' => 'gm',

                                                                'hidden'     => 'Bill LDC'

                                                                ),*/

                                   'retrofit_invoiced' => array(

                                                                'title'      => 'Retrofit Invoiced',

                                                                'tableAlias' => 'gmi'

                                                                ),

                                   ); 

        $this->assign('formFields', $this->_formFields);



        $selectFields      = array(

                                   'file_identifier'    => array(

                                                                 'title'      => 'FileID',

                                                                 'tableAlias' => 'ga'

                                                                 ),

                                   'ldc_acct'           => array(

                                                                 'title'      => 'Account',

                                                                 'tableAlias' => 'ga'

                                                                 ),

                                   'audit_completed'    => array(

                                                                 'title' => 'Audit Completed',

                                                                 'tableAlias' => 'gmo'

                                                                 ),

                                   'retrofit_completed' => array(

                                                                 'title' => 'Retrofit completed',

                                                                 'tableAlias' => 'gmo'

                                                                 ),

                                   'xm_costs'           => array(

                                                                 'title' => 'Extended Costs',

                                                                 'tableAlias' => 'gmo'

                                                                 ),

                                   'xm_kwh'             => array(

                                                                 'title' => 'Extended Savings kWh/y',

                                                                 'tableAlias' => 'gmo'

                                                                 ),

                                   'xm_kw_s'            => array(

                                                                 'title' => 'Extended Savings KW-S',

                                                                 'tableAlias' => 'gmo'

                                                                 ),

                                   'xm_kw_w'            => array(

                                                                 'title' => 'Extended Savings KW-W',

                                                                 'tableAlias' => 'gmo'

                                                                 ),

                                   'xm_trc'             => array(

                                                                 'title' => 'Extended TRC NPV',

                                                                 'tableAlias' => 'gmo'

                                                                 ),

                                   'retrofit_invoiced'  => array(

                                                                 'title'      => 'Retrofit Invoiced',

                                                                 'tableAlias' => 'gmi'

                                                                 ),

                                   );

        

        $this->_selectFields = array_merge($this->_selectFields, $selectFields);

        

        // for values of a table to be included as columns

        $this->_measuresValueColumns = array(

                                             'name' => array ('values' =>

                                                              array('%Attic insulation%'   => 'Attic INS',

                                                                    '%Wall insulation%'    => 'Wall INS',

                                                                    '%Basement insulation%'=> 'Bsmt INS',

                                                                    '%Floor insulation%'   => 'Flr INS',

                                                                    '%Draft proofing%'     => 'Draft proof',

                                                                    '%Refrigerator replacement-15 cu ft%' => 'Fridge 15',

                                                                    '%Refrigerator replacement-18 cu ft%' => 'Fridge 18',

                                                                    '%Refrigerator replacement-21 cu ft%' => 'Fridge 21',

                                                                    '%Refrigerator removal only%' => 'Fridge Rmv',

                                                                    '%DHW fuel switch%' => 'Fuel switch DHW',

                                                                    '%DW Heat recovery%'=> 'DW Heat recovery',//guess work

                                                                    ),

                                                              'fields' =>

                                                              array('costs' => 'Costs',

                                                                    'kwh'   => 'Savings kwh',

                                                                    'kw_s'  => 'KW-S',

                                                                    'kw_w'  => 'KW-W',

                                                                    )

                                                              )

                                             );



    }



    /**

     * name of export file.

     *

     * @param string $output type of output

     * @return string name of the file

     */

    public function getExportFileName( $output = 'csv') {

        return ts('LDC Final Report');

    }



    /**

     * returns the header sequence to be used in report

     *

     * @return Array

     */

    public function getHeaderSequence() {

        return array(

                     $this->_selectFields['file_identifier']['title'], 

                     $this->_selectFields['display_name']['title'], 

                     $this->_selectFields['street_address']['title'],

                     $this->_selectFields['city']['title'], 

                     $this->_selectFields['ldc_acct']['title'],

                     $this->_selectFields['audit_completed']['title'],

                     $this->_selectFields['retrofit_completed']['title'],

                     $this->_selectFields['xm_costs']['title'], 

                     $this->_selectFields['xm_kwh']['title'], 

                     $this->_selectFields['xm_kw_s']['title'], 

                     $this->_selectFields['xm_kw_w']['title'], 

                     $this->_selectFields['xm_trc']['title'], 



                     $this->_measuresValueColumns['name']['values']['%Attic insulation%'],

                     $this->_measuresValueColumns['name']['fields']['costs'],

                     $this->_measuresValueColumns['name']['fields']['kwh'],

                     $this->_measuresValueColumns['name']['fields']['kw_s'],

                     $this->_measuresValueColumns['name']['fields']['kw_w'],



                     $this->_measuresValueColumns['name']['values']['%Wall insulation%'],

                     $this->_measuresValueColumns['name']['fields']['costs'],

                     $this->_measuresValueColumns['name']['fields']['kwh'],

                     $this->_measuresValueColumns['name']['fields']['kw_s'],

                     $this->_measuresValueColumns['name']['fields']['kw_w'],



                     $this->_measuresValueColumns['name']['values']['%Basement insulation%'],

                     $this->_measuresValueColumns['name']['fields']['costs'],

                     $this->_measuresValueColumns['name']['fields']['kwh'],

                     $this->_measuresValueColumns['name']['fields']['kw_s'],

                     $this->_measuresValueColumns['name']['fields']['kw_w'],



                     $this->_measuresValueColumns['name']['values']['%Floor insulation%'],

                     $this->_measuresValueColumns['name']['fields']['costs'],

                     $this->_measuresValueColumns['name']['fields']['kwh'],

                     $this->_measuresValueColumns['name']['fields']['kw_s'],

                     $this->_measuresValueColumns['name']['fields']['kw_w'],



                     $this->_measuresValueColumns['name']['values']['%Draft proofing%'],

                     $this->_measuresValueColumns['name']['fields']['costs'],

                     $this->_measuresValueColumns['name']['fields']['kwh'],

                     $this->_measuresValueColumns['name']['fields']['kw_s'],

                     $this->_measuresValueColumns['name']['fields']['kw_w'],



                     $this->_measuresValueColumns['name']['values']['%Refrigerator replacement-15 cu ft%'],

                     $this->_measuresValueColumns['name']['fields']['costs'],

                     $this->_measuresValueColumns['name']['fields']['kwh'],

                     $this->_measuresValueColumns['name']['fields']['kw_s'],

                     $this->_measuresValueColumns['name']['fields']['kw_w'],



                     $this->_measuresValueColumns['name']['values']['%Refrigerator replacement-18 cu ft%'],

                     $this->_measuresValueColumns['name']['fields']['costs'],

                     $this->_measuresValueColumns['name']['fields']['kwh'],

                     $this->_measuresValueColumns['name']['fields']['kw_s'],

                     $this->_measuresValueColumns['name']['fields']['kw_w'],



                     $this->_measuresValueColumns['name']['values']['%Refrigerator replacement-21 cu ft%'],

                     $this->_measuresValueColumns['name']['fields']['costs'],

                     $this->_measuresValueColumns['name']['fields']['kwh'],

                     $this->_measuresValueColumns['name']['fields']['kw_s'],

                     $this->_measuresValueColumns['name']['fields']['kw_w'],



                     $this->_measuresValueColumns['name']['values']['%Refrigerator removal only%'],

                     $this->_measuresValueColumns['name']['fields']['costs'],

                     $this->_measuresValueColumns['name']['fields']['kwh'],

                     $this->_measuresValueColumns['name']['fields']['kw_s'],

                     $this->_measuresValueColumns['name']['fields']['kw_w'],



                     $this->_measuresValueColumns['name']['values']['%DHW fuel switch%'],

                     $this->_measuresValueColumns['name']['fields']['costs'],

                     $this->_measuresValueColumns['name']['fields']['kwh'],

                     $this->_measuresValueColumns['name']['fields']['kw_s'],

                     $this->_measuresValueColumns['name']['fields']['kw_w'],



                     $this->_measuresValueColumns['name']['values']['%DW Heat recovery%'],

                     $this->_measuresValueColumns['name']['fields']['costs'],

                     $this->_measuresValueColumns['name']['fields']['kwh'],

                     $this->_measuresValueColumns['name']['fields']['kw_s'],

                     $this->_measuresValueColumns['name']['fields']['kw_w'],



                     $this->_selectFields['retrofit_invoiced']['title'],

                     );

    }



    /**

     * returns the result sequence to be used in report

     *

     * @return Array

     */

    public function getResultSequence() {

        return array(

                     $this->_selectFields['file_identifier']['title'], 

                     $this->_selectFields['display_name']['title'], 

                     $this->_selectFields['street_address']['title'],

                     $this->_selectFields['city']['title'], 

                     $this->_selectFields['ldc_acct']['title'],

                     $this->_selectFields['audit_completed']['title'],

                     $this->_selectFields['retrofit_completed']['title'],

                     $this->_selectFields['xm_costs']['title'], 

                     $this->_selectFields['xm_kwh']['title'], 

                     $this->_selectFields['xm_kw_s']['title'], 

                     $this->_selectFields['xm_kw_w']['title'], 

                     $this->_selectFields['xm_trc']['title'], 

                     

                     $this->_measuresValueColumns['name']['values']['%Attic insulation%'],

                     $this->_measuresValueColumns['name']['fields']['costs'] . '-1',

                     $this->_measuresValueColumns['name']['fields']['kwh'] . '-1',

                     $this->_measuresValueColumns['name']['fields']['kw_s'] . '-1',

                     $this->_measuresValueColumns['name']['fields']['kw_w'] . '-1',

                     

                     $this->_measuresValueColumns['name']['values']['%Wall insulation%'],

                     $this->_measuresValueColumns['name']['fields']['costs'] . '-2',

                     $this->_measuresValueColumns['name']['fields']['kwh'] . '-2',

                     $this->_measuresValueColumns['name']['fields']['kw_s'] . '-2',

                     $this->_measuresValueColumns['name']['fields']['kw_w'] . '-2',

                     

                     $this->_measuresValueColumns['name']['values']['%Basement insulation%'],

                     $this->_measuresValueColumns['name']['fields']['costs'] . '-3',

                     $this->_measuresValueColumns['name']['fields']['kwh'] . '-3',

                     $this->_measuresValueColumns['name']['fields']['kw_s'] . '-3',

                     $this->_measuresValueColumns['name']['fields']['kw_w'] . '-3',

                     

                     $this->_measuresValueColumns['name']['values']['%Floor insulation%'],

                     $this->_measuresValueColumns['name']['fields']['costs'] . '-4',

                     $this->_measuresValueColumns['name']['fields']['kwh'] . '-4',

                     $this->_measuresValueColumns['name']['fields']['kw_s'] . '-4',

                     $this->_measuresValueColumns['name']['fields']['kw_w'] . '-4',

                     

                     $this->_measuresValueColumns['name']['values']['%Draft proofing%'],

                     $this->_measuresValueColumns['name']['fields']['costs'] . '-5',

                     $this->_measuresValueColumns['name']['fields']['kwh'] . '-5',

                     $this->_measuresValueColumns['name']['fields']['kw_s'] . '-5',

                     $this->_measuresValueColumns['name']['fields']['kw_w'] . '-5',

                     

                     $this->_measuresValueColumns['name']['values']['%Refrigerator replacement-15 cu ft%'],

                     $this->_measuresValueColumns['name']['fields']['costs'] . '-6',

                     $this->_measuresValueColumns['name']['fields']['kwh'] . '-6',

                     $this->_measuresValueColumns['name']['fields']['kw_s'] . '-6',

                     $this->_measuresValueColumns['name']['fields']['kw_w'] . '-6',

                     

                     $this->_measuresValueColumns['name']['values']['%Refrigerator replacement-18 cu ft%'],

                     $this->_measuresValueColumns['name']['fields']['costs'] . '-7',

                     $this->_measuresValueColumns['name']['fields']['kwh'] . '-7',

                     $this->_measuresValueColumns['name']['fields']['kw_s'] . '-7',

                     $this->_measuresValueColumns['name']['fields']['kw_w'] . '-7',

                     

                     $this->_measuresValueColumns['name']['values']['%Refrigerator replacement-21 cu ft%'],

                     $this->_measuresValueColumns['name']['fields']['costs'] . '-8',

                     $this->_measuresValueColumns['name']['fields']['kwh'] . '-8',

                     $this->_measuresValueColumns['name']['fields']['kw_s'] . '-8',

                     $this->_measuresValueColumns['name']['fields']['kw_w'] . '-8',

                     

                     $this->_measuresValueColumns['name']['values']['%Refrigerator removal only%'],

                     $this->_measuresValueColumns['name']['fields']['costs'] . '-9',

                     $this->_measuresValueColumns['name']['fields']['kwh'] . '-9',

                     $this->_measuresValueColumns['name']['fields']['kw_s'] . '-9',

                     $this->_measuresValueColumns['name']['fields']['kw_w'] . '-9',

                     

                     $this->_measuresValueColumns['name']['values']['%DHW fuel switch%'],

                     $this->_measuresValueColumns['name']['fields']['costs'] . '-10',

                     $this->_measuresValueColumns['name']['fields']['kwh'] . '-10',

                     $this->_measuresValueColumns['name']['fields']['kw_s'] . '-10',

                     $this->_measuresValueColumns['name']['fields']['kw_w'] . '-10',



                     $this->_measuresValueColumns['name']['values']['%DW Heat recovery%'],

                     $this->_measuresValueColumns['name']['fields']['costs'] . '-11',

                     $this->_measuresValueColumns['name']['fields']['kwh'] . '-11',

                     $this->_measuresValueColumns['name']['fields']['kw_s'] . '-11',

                     $this->_measuresValueColumns['name']['fields']['kw_w'] . '-11',

                     

                     $this->_selectFields['retrofit_invoiced']['title'],

                     );

    }



    /**

     * returns the title header to be used in report

     *

     * @return Array

     */

    public function getTitleHeader( $params ) {

        require_once 'CRM/Core/OptionGroup.php';



        if ($params['ldc_id']) {

            $options = CRM_Core_OptionGroup::values('ldc');

            $ldc = $options[$params['ldc_id']];

        }

        

        $titleHeader = " LDC Final Report: $ldc \n";

        return $titleHeader;

    }

    

}



?>

