<?php
 
require_once 'CRM/Core/Form.php';

/**
 * Gas LDC Final Report
 * 
 */
class Efficiency_Form_Report_Main extends CRM_Core_Form
{

    /**
     * Function to set variables up before form is built
     *
     * @return void
     * @access public
     */
    public function preProcess(){
        global $user;

        /**
         * You can define a custom title for the search form
         */
        CRM_Utils_System::setTitle( $user->name );

        parent::preProcess();
        //$accessAudit = $accessRetrofit = $accessEnbridge = $accessInventory = $accessLDCFinal = 0;
        if ( CRM_Core_Permission::check( 'access_GC-audit_report' ) ) {
            $this->assign( 'accessAudit', 1 );
        }
        if ( CRM_Core_Permission::check( 'access_GC-retrofit_report' ) ) {
            $this->assign( 'accessRetrofit', 1 );
        }
        if ( CRM_Core_Permission::check( 'access_enbridge_report' ) ) {
            $this->assign( 'accessEnbridge', 1 );
        }
        if ( CRM_Core_Permission::check( 'access_inventory_report' ) ) {
            $this->assign( 'accessInventory', 1 );
        }
        if ( CRM_Core_Permission::check( 'access_ldcfinal_report' ) ) {
            $this->assign( 'accessLDCFinal', 1 );
        }
        if ( CRM_Core_Permission::check( 'access_GC-audit_report_new' ) ) {
            $this->assign( 'accessAuditNew', 1 );
        }
        if ( CRM_Core_Permission::check( 'access_GC-retrofit_report_new' ) ) {
            $this->assign( 'accessRetrofitNew', 1 );
        }

    }
}

?>
