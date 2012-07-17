<?php
require_once 'Efficiency/Form/AddApplicant.php';
require_once 'CRM/Custom/Form/CustomData.php';
require_once 'CRM/Core/BAO/CustomGroup.php';
require_once "api/v3/CustomValue.php";

class Efficiency_Form_AddApplicant_Application extends Efficiency_Form_AddApplicant
{
    public function preProcess( ) 
    {
        $this->_groupID     = 1;
        
        if ( isset( $_GET['cid'] ) ) {
            $this->_contactID = $_GET['cid'];
        }
        
        if( $this->_groupID ) {
            
            $groupTree =& CRM_Core_BAO_CustomGroup::getTree( 'Individual',
                                                             $this,
                                                             null,
                                                             $this->_groupID,
                                                             null, null );
            
            $form = null;
            $groupTree = CRM_Core_BAO_CustomGroup::formatGroupTree( $groupTree, null, $this);
            $this->_groupTree = $groupTree;
            
            foreach ( $this->_groupTree as $gID => $gVal ) {
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
             
            

            $this->_names = $ids;
            $this->_elementsarr = $elementArr;


        }

        if( isset( $_GET['context'] ) ) {
            $session =& CRM_Core_Session::singleton( );
            $this->_context = $_GET['context'];
            $session->set('context', $_GET['context'] );
        }
        

        parent::preProcess( );
    }
    

  /**
     * This function sets the default values for the form. Relationship that in edit/view action
     * the default values are retrieved from the database
     * 
     * @access public
     * @return void
     */
    function setDefaultValues( ) 
    {
        $defaults = array( );
        if ( isset( $this->_contactID ) ) {
            foreach( $this->_names as $key => $value ) {
                $customKeyparams[$key] = 1;
            }
            $customParams =array(
                                 'entityID' => $this->_contactID
                                 );
            $customParams = array_merge($customParams,$customKeyparams);
            
            require_once 'CRM/Core/BAO/CustomValueTable.php';
            $customValue = CRM_Core_BAO_CustomValueTable::getValues( $customParams );
            foreach($customValue as $key =>$value) {
                if( $key == 'custom_2' ){
                    if ( !empty ( $value ) ){
                    $date = CRM_Utils_Date::setDateDefaults( date( 'Y-m-d', strtotime ("$value") ) );
                    $defaults[$key.'_-'] = $date[0];
                    }
                }else{
                    $defaults[$key.'_-'] = $value;  
                }
                
            }
            
        }
        
        return $defaults;
    }
    

    /**
     * Function to actually build the form
     *
     * @return void
     * @access public
     */
   
    public function buildQuickForm( ) 
    {        
        $this->_groupTree[$this->_groupID]['fields'][1]['readonly']='readonly';
        CRM_Core_BAO_CustomGroup::buildQuickForm( $this, $this->_groupTree, false, null );
        foreach ( $this->_elements as $key => $value ) {
            if( $value->_attributes['name']=='custom_1_-') {
                $value->_attributes['readonly']='readonly';
                break;
            }
        }

        parent::buildQuickForm( ); 


    }



   /**
     * global validation rules for the form
     *
     * @param array $fields posted values of the form
     *
     * @return array list of errors to be posted back to the form
     * @static
     * @access public
     */
    static function formRule( $values ) 
    {
        $errors = array( );
        
        if (!$values['is_template']) {
            if ( CRM_Utils_System::isNull( $values['date_entered'] ) ) {
                $errors['date_entered'] = ts( 'Start Date and Time are required fields' );
            } else {
                $start = CRM_Utils_Date::processDate( $values['date_entered'] );
                
                if ( ($end < $start)) {
                    $errors['date_entered'] = ts( 'End date should be after Start date' );
                }
            }
        }
        
        
        
        return $errors;
    }

    /**
     * process the form after the input has been submitted and validated
     *
     * @access public
     * @return void
     */
    public function postProcess() 
    {
        $session = CRM_Core_Session::singleton();
        $cid     = $session->get( 'currCID');
        $params  = $this->controller->exportValues( $this->_name );
        $params['custom_2_-'] = CRM_Utils_Date::processDate( $params['custom_2_-'] );
        
        //Context passed through the url
        $this->_context = $session->get( 'context' );
        
        foreach( $params as $key => $value )  {
            if(strstr($key,'custom')) {
                $length             = strlen($key)-2;
                $newKey             = substr($key, 0 ,$length);
                $newParams[$newKey] = $value;
            }
        }
        
        $newParams['version']   = 3;
        if ( isset( $cid ) ) {
            $newParams['entity_id'] = $cid;
        }
        
        if ( $newParams ) {
            $customPost = @civicrm_api( 'custom_value','create', $newParams );
            
        }
        
        if ( isset( $this->_context ) && $cid ) {
            
            //URL for Application after saved
            $url = "civicrm/efficiency/application/view";
            $urlParams = "reset=1&cid={$cid}&selectedChild=application";
            
            CRM_Utils_System::redirect( CRM_Utils_System::url( $url, $urlParams ) );
            
        } else if ( $this->_action & CRM_Core_Action::ADD ) {
            $url = 'civicrm/efficiency/note';
            $urlParams = "action=add&reset=1&cid={$cid}";
            
            CRM_Utils_System::redirect( CRM_Utils_System::url( $url, $urlParams ) );
        }
        
    }
    
    
    
    /**
     * Return a descriptive name for the page, used in wizard header
     *
     * @return string
     * @access public
     */
    public function getTitle()
    {
        return ts('Application');
    }
    
    
    
}