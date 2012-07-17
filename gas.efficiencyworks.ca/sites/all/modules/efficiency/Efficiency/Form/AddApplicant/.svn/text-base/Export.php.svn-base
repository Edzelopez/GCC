<?php

require_once 'CRM/Core/BAO/Cache.php';
require_once 'Efficiency/BAO/Export.php';
require_once 'Efficiency/Form/AddApplicant.php';
require_once 'Efficiency/BAO/File.php';
/**
 * This class gets the name of the file to upload
 */
class Efficiency_Form_AddApplicant_Export extends Efficiency_Form_AddApplicant {
    
    /**
     * the run function
     *
     * @return void
     * @access public
     */
    public function preProcess( ) 
    {
        parent::preProcess( );
        
        /*** Delete & Insert records for current user in cache table - Start ***/
        $value = 0;
        // delete current user's last form preferences from cache table
         CRM_Core_BAO_Cache::deleteGroup( "gcc_refresh_customer_{ $this->_applicantId }" );
        // Insert current user's last selected form preferences into cache table
         CRM_Core_BAO_Cache::setItem( $value, "gcc_refresh_customer_{$this->_applicantId}", 
                                         'Efficiency_Form_AddApplicant_Files', null );
         
         /*** Delete & Insert records for current user in cache table - End ***/
        
        $export =& new Efficiency_BAO_Export( $this->_applicantId );
        $output = $export->export();
      
        Efficiency_BAO_Export::doInternalExport( $this->_applicantId, $export->fileID);
        Efficiency_BAO_File::_dumpCSVHeader( $export->fileID );
         echo $output;
         exit( );
    }
    
}
?>
