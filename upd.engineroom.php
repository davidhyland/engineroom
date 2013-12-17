<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once PATH_THIRD.'engineroom/constants.engineroom.php';

class Engineroom_upd {

    public $version = '1.0';
    public $module_name = ER_MODULENAME;

    private $EE;

    /**
     * Class Constructor
     */
    public function __construct()
    {
        $this->EE =& get_instance();
    }

    // ----------------------------------------------------------------

    /**
     * Installation Method
     *
     * Required by ExpressionEngine
     *
     * @return boolean  TRUE
     */
    public function install()
    {       
         // Drop info on the module into the modules table
        $mod_data = array(
            'module_name'           => 'Engineroom',
            'module_version'        => $this->version,
            'has_cp_backend'        => 'n',
            'has_publish_fields'        => 'n'
        );
        $this->EE->db->insert('modules', $mod_data);


				// set an action id
				$data = array(
					'class'		=> 'Engineroom',
					'method'	=> ''
				);
				//$this->EE->db->insert('actions', $data);

        return TRUE;
    }

    // ----------------------------------------------------------------

    /**
     * Module Uninstaller
     *
     * Required by ExpressionEngine
     *
     * @return  boolean     TRUE
     */
    public function uninstall()
    {
        return TRUE;
    }

    // ----------------------------------------------------------------

    /**
     * Module Updater
     *
     * Required by ExpressionEngine
     *
     * @return  boolean     TRUE
     */ 
    public function update($current = '')
    {

			if ($current == $this->version)
			{
				return FALSE;
			}
			
			/*
			if ($current < 2.0) 
			{
				// Do your update code here
			} 
			*/
			
			return TRUE; 
    }
		
		
}

