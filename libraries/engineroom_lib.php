<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


require_once PATH_THIRD.'engineroom/constants.engineroom.php';

class Engineroom_lib
{
	protected $EE;
	protected $output = "";
	protected $site_id;

	public function Engineroom_lib()
	{
		if (! isset($_SESSION)) @session_start();
		
		$this->EE =& get_instance();
		//$this->EE->engineroom =& $this;
		
		$this->EE->load->helper('text'); 
		$this->EE->load->library('email');
		//$this->EE->load->model('engineroom_model');
		
		$this->site_id = $this->EE->config->item('site_id');
	}
	

	/*
	=============================================================================================================================================	
	=============================================================================================================================================	
	MODULE METHODS	
	=============================================================================================================================================	
	=============================================================================================================================================	
	*/



	
	
	
	/*
	send_email
	------------------------------------------------
	*/
	public function send_email($code, $data)
	{
		//$this->EE->custom->dump_array($data);
		if($code != '' && count($data) > 0 && $data['to'] != '')
		{
			$result = $this->EE->custom->db_query_row("SELECT * FROM exp_tag_emails WHERE code = ".$this->EE->db->escape($code));
			if($result)
			{
				//$this->EE->custom->dump_array($result);
				// from email and name
				if(isset($result['fromemail']))
					$fromemail = $result['fromemail'];
				else
					//$fromname = UAM_EMAIL_SUPPORT;
					
				if(isset($result['fromname']))
					$fromname = $result['fromname'];
				else
					$fromname = "Use A Member";
					
				if(isset($data['subject']))
					$subject = $data['subject'];
				else
					$subject = $result['subject'];
					
				$this->EE->email->initialize();
				$this->EE->email->wordwrap = TRUE;
				$this->EE->email->from($fromemail, $fromname);
				$this->EE->email->to($data['to']); 
				if(isset($data['cc'])) $this->EE->email->cc($data['cc']); 
				if(isset($data['bcc'])) $this->EE->email->bcc($data['bcc']); 
				$this->EE->email->subject($subject);
				$body_text = $result['body_text'];
				$body_html = ($result['body_html'] != '') ? $result['body_html'] : FALSE;
				$this->EE->email->mailtype = ($body_html) ? 'html' : 'text';
				
				foreach($data as $key => $value)
				{
					$body_text = str_replace("{".$key."}", $value, $body_text);
					if($body_html)
					{
						$body_html = str_replace("{".$key."}", $value, $body_html);
					}
				}
				
				if($body_html)
				{
					$this->EE->email->message(entities_to_ascii($body_html));
					$this->EE->email->set_alt_message(entities_to_ascii($body_text));
				}
				else
				{
					//if(isset($data['footer'])) $body_text .= $data['footer'];
					//if($result['footer'] == '1') $body_text .= UAM_EMAIL_FOOTER;
					$this->EE->email->message(entities_to_ascii($body_text));
				}
				
				if($this->EE->email->send() !== FALSE)
				{
					// update count
					$sql = $this->EE->db->update_string('exp_tag_emails', array('count' => ($result['count'] + 1)), "email_id = ".$this->EE->db->escape($result['email_id']));
					$this->EE->db->query($sql);
					return TRUE;
				}
				else
				{
					return FALSE;
				}
			}
		}
	}
	


	// -----------------------------------
	// -----------------------------------
	// -----------------------------------
	// PRIVATE FUNCTIONS
	// -----------------------------------
	// -----------------------------------
	// -----------------------------------
	
	
	private function _log_this($type='', $log, $value)
	{
		$data['type'] = $type;
		$data['log'] = $log;
		$data['value'] = (is_array($value)) ? serialize($value): $value;
		$sql = $this->EE->db->insert_string('exp_tag_logs', $data);
		$this->EE->db->query($sql);
	}


}

/* End of file */