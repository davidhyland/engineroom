<?php if (!defined('BASEPATH')) die('No direct script access allowed');


include_once(PATH_THIRD.'engineroom/constants.engineroom.php');

class Engineroom {

	protected $output = "";
	protected $site_id;
	protected $environment;
	var $return_data    = '';
	
	// ********************************************************************************* //

  function __construct()
  {
		if (! isset($_SESSION)) @session_start();
		$this->EE =& get_instance(); 
		$this->EE->load->library('engineroom_lib');
		$this->EE->load->model('engineroom_model');
		$this->site_id = $this->EE->config->item('site_id');
		//$this->environment = $this->EE->engineroom->get_environment($this->EE->config->item('site_url'));
  }
	
	// ********************************************************************************* //



	public function load_ads()
	{
		
	}


	/*
	get_states
	-----------------------------------------
	*/
	public function get_states()
	{
		$type = $this->EE->TMPL->fetch_param('type', 'menu_list');
		$fld = "d.field_id_23"; // ad_state
		$where = "$fld != ''";
			
		$sql = "SELECT DISTINCT($fld) AS value FROM exp_channel_data AS d
					LEFT JOIN exp_channel_titles AS t ON t.entry_id = d.entry_id
					WHERE #SQL_WHERE# AND t.status = 'open' AND t.channel_id = 2 
					AND FROM_UNIXTIME(t.entry_date) < NOW() AND (FROM_UNIXTIME(t.expiration_date) > NOW() OR t.expiration_date = '0')
					ORDER BY value ASC";
							
			if($type)
			{
				$result = $this->EE->custom->db_query_result(str_replace("#SQL_WHERE#", $where, $sql));
				if ($result)
				{
					$tmp = array();
					$tmp2 = array();
					$options = array();
					
					// menu list
					if($type == "menu_list")
					{
						$this->output .= '<li class="selected"><p><a title="View ads in all States" href="#" data-all="true" data-filter="*">All States</a></p></li>'.NL;
						foreach($result as $row)
						{
							$this->output .= '<li><p><a title="View all ads in '.$row['value'].'" href="#" data-state="true" data-filter="'.$this->lookup('state_alpha2', $row['value']).'">'.$row['value'].'</a></p></li>'.NL;
						}
					}
					
					// menu list
					if($type == "hidden_list")
					{
						foreach($result as $row)
						{
							$this->output .= '<li data-state="'.$row['value'].'">'.$this->lookup('state_alpha2', $row['value']).'</li>'.NL;
						}
					}
					
					// map list
					if($type == "map_list")
					{
						$this->output .= "<ul>";
						$this->output .= '<li><a href="#" title="View ads in all States" data-all="true" data-filter="*">All States</a></li>';
						$i = 0;
						$total = count($result);
						$half = ceil($total / 2) - 1; // -1 because we've moved All States to the top of the list
						foreach($result as $row)
						{
							$this->output .= '<li><a href="#" title="View ads in '.$row['value'].'" data-state="true" data-filter="'.$this->lookup('state_alpha2', $row['value']).'">'.$row['value'].'</a></li>';
							$i++;
							if($total > 10 && $i == $half)
							{
								$this->output .= "</ul><ul>";
							}
						}
						$this->output .= "</ul>";
					}
					
				}
				
			}
						
			return $this->output;
			
	}
	

	/*
	get_pois
	-----------------------------------------
	*/
	public function get_pois()
	{
		$type = $this->EE->TMPL->fetch_param('type', 'menu_list');
		
		$options = array(
			'family' => 'Family Fun',
			'city' => 'City Fun',
			'adventure' => 'Adventure',
			'kid' => 'Kid Fun',
			'water' => 'Water Fun',
			'relax' => 'Relaxing Fun'
		);
			
		// menu list
		{
			//$this->output .= '<li class="selected"><p><a title="View ads in all States" href="#" data-all="true" data-filter="*">All States</a></p></li>'.NL;
			foreach($options as $key => $value)
			{
				if($type == "menu_list")
				{
					$this->output .= '<li><p><a title="View all '.$value.' ads" href="#" data-poi="'.$key.'" class="poi">'.$value.'</a></p></li>'.NL;
				}
				elseif($type == "hidden_list")
				{
					$this->output .= '<li>'.$key.'</li>'.NL;
				}
			}
		}
						
		return $this->output;
			
	}
	

	/*
	get_post
	-----------------------------------------
	*/
	public function get_post()
	{
		//$name = $this->EE->TMPL->fetch_param('name', FALSE);
		//$wrap_pre = $this->EE->TMPL->fetch_param('wrap_pre', '');
		//$wrap_post = $this->EE->TMPL->fetch_param('wrap_post', '');
		return 'city';
		
		/*if($name && $this->EE->input->gets_post($name))
		{
			return $wrap_pre.$this->EE->input->get_post($name).$wrap_post;
		}
		else
		{
			$this->EE->functions->redirect('/#/state/all');
		}*/
	}

	/*
	lookup
	-----------------------------------------
	*/
	public function lookup($what='', $value='')
	{
		include PATH_THIRD.'reegion_select/libraries/regions.php';
		if($what == 'region')
		{
			return $tfun_regions[$value];
		}
		if($what == 'state')
		{
			return $states[$value];
		}
		if($what == 'state_alpha2')
		{
			$flipped = array_flip($states);
			if(isset($flipped[$value])) return $flipped[$value];
		}
		if($what == 'season')
		{
			switch($value)
			{
				case '1': $output = "Spring"; break;
				case '2': $output = "Summer"; break;
				case '3': $output = "Fall"; break;
				case '4': $output = "Winter"; break;
			}
			return $output;
		}
	}
	

}


