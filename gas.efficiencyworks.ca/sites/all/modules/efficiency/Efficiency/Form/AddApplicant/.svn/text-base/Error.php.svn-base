<?php
  /**
   *This is Error Page for UnAthorized Users 
   *
   *
   **/

require_once 'Efficiency/Form/AddApplicant.php';
class Efficiency_Form_AddApplicant_Error extends Efficiency_Form_AddApplicant
{
    /**
     * Function to set variables up before form is built
     *
     * @return void
     * @access public
     */
    public function preProcess()
    {
        parent::preProcess( ); 
        $action = $this->getVar( '_action' );
       
        if ( $action == 4 ){
            $String = 'view';
        }else{
            $String = 'add/update';
        }
        $String =  'You are not Aauthorize to '. $String .' this Tab';
         $this->assign( 'errMsg', $String);
       
    }
}