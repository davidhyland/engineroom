<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


require_once PATH_THIRD.'engineroom/constants.engineroom.php';

class Engineroom_ext
{
	var $name            = 'EngineRoom';
	var $description     = 'Custom extensions';
	var $version         = '1.0';
	var $settings_exist  = 'n';
	var $settings  			 = 'n';
	var $docs_url		     = '';
	
	var $environment;
	
  /**
   * Constructor
   */
	function __construct()
	{
		$this->EE =& get_instance();
		$this->EE->load->library('engineroom_lib');
		$this->EE->load->model('engineroom_model');
		//$this->environment = $this->EE->engineroom->get_environment($this->EE->config->item('site_url'));
	}
	
	// --------------------------------------------------------------------
	
	
	
	
	// --------------------------------------------------------------------
	
	/**
	 * Activate Extension
	 */	
	public function activate_extension()
	{
		/*
		$hooks = array(
		);

		foreach ($hooks as $hook => $method)
		{
			$data = array(
				'class' => __CLASS__,
				'method' => $method,
				'hook' => $hook,
				'settings' => serialize($this->settings),
				'priority' => 10,
				'version' => $this->version,
				'enabled' => 'y'
				);

			$this->EE->db->insert('extensions', $data);
		}
		*/
		
	}
	
	// ********************************************************************************* //

	/**
	 * Called by ExpressionEngine when the user disables the extension.
	 **/
	public function disable_extension()
	{
		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->delete('extensions');
	}

	// ********************************************************************************* //

	/**
	 * Called by ExpressionEngine updates the extension
	 **/
	public function update_extension($current=FALSE)
	{
		if($current == $this->version) return false;

		// Update the extension
		$this->EE->db
			->where('class', __CLASS__)
			->update('extensions', array('version' => $this->version));

	}


}

/* End of file ext.engineroom.php */
