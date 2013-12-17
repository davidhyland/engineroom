<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


require_once PATH_THIRD.'engineroom/constants.engineroom.php';


class Engineroom_model extends CI_Model
{
	protected $EE;
	public $site_id;

	public function Engineroom_model()
	{
		if (! isset($_SESSION)) @session_start();
		parent::__construct();
		$this->EE =& get_instance();
		$this->site_id = $this->EE->config->item('site_id');
	}

	/*
	************************************************************************************
	************************************************************************************
	************************************************************************************
	*/
	

	/*
	function: add_new_entry
	purpose: creates new channel entry
	------------------------------------------------
	*/
	function add_new_entry($channel_id, $data)
	{
		//$this->EE->custom->dump_array($data);
		if($channel_id && is_array($data))
		{
			$this->EE->load->library('api');
			$this->EE->api->instantiate('channel_entries');
			$this->EE->api->instantiate('channel_fields');
			$this->EE->api_channel_fields->setup_entry_settings($channel_id, $data);
			if($this->EE->api_channel_entries->submit_new_entry($channel_id, $data) === FALSE)
			{
				return FALSE;
			}
			else
			{
				return $this->EE->api_channel_entries->entry_id;
			}
		}
	}
	
	
	

	/*
	function: _generate_random_string
	purpose: returns random string 
	------------------------------------------------
	*/
	private function _generate_random_string($length=20)
	{
		$chars = '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
			$code .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    return $code;
	}

}

/* End of file */