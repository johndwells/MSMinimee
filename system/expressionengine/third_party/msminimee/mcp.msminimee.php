<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license		http://expressionengine.com/user_guide/license.html
 * @link		http://expressionengine.com
 * @since		Version 2.0
 * @filesource
 */
 
// ------------------------------------------------------------------------

// our helper will require_once() everything else
require_once PATH_THIRD . 'msminimee/classes/Msminimee_helper.php';

/**
 * MSMinimee Module Control Panel File
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Module
 * @author		John D Wells
 * @link		http://johndwells.com
 */

class Msminimee_mcp {
	
	public $return_data;
	
	protected $_base_url;
	
	protected $_site_id;

	public $EE;
	
	// ------------------------------------------------------


	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();
		
		// fetch current site id
		$this->_site_id = (int) $this->EE->config->item('site_id');

		$this->_base_url = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=msminimee';

		// begin setting up our view
		$this->EE->view->cp_page_title = lang('msminimee_module_name');
		
	}
	
	// ------------------------------------------------------

	/**
	 * Index Function
	 *
	 * @return 	void
	 */
	public function index()
	{
		// Query DB for any settings
		$settings = Msminimee_helper::get_settings_by_site($this->_site_id);

		// Our flag		
		$settings_exist = ($settings);
		
		if($settings_exist)
		{
			return $this->settings();
		}
		
		else
		{
			// view vars		
			$vars = array(
				'base_url' => $this->_base_url,
				'site_id' => $this->_site_id,
				'form_open' => form_open($this->_base_url.AMP.'method=install'),
				'settings' => $settings,
				'settings_exist' => $settings_exist,
/* 				'flashdata_success' => $this->EE->session->flashdata('message_success') */
				);

			$this->EE->load->helper('form');
			return $this->EE->load->view('index', $vars, TRUE);
		}
	}
	
	public function delete()
	{
		// posting to this method confirms our desire to delete settings
		if ( ! empty($_POST))
		{
			// sanity check: input value needs to match loaded site
			if( ! isset($_POST['site_id']) || $_POST['site_id'] != $this->_site_id)
			{
				Msminimee_helper::log($this->EE->lang->line('unauthorized_access'), 1);
			}
		
			Msminimee_helper::log('Deleting settings for Site ID ' . $this->_site_id, 3);

			$this->EE->db->delete('msminimee', array('site_id' => $this->_site_id));

			$this->EE->session->set_flashdata(
				'message_success',
			 	$this->EE->lang->line('preferences_deleted')
			);
			
			$this->EE->functions->redirect(BASE.AMP.$this->_base_url);
		}
		
		// otherwise we are here for the first time, and need to confirm our selection
		else
		{

			$this->EE->cp->set_right_nav(array(
				'settings_tab'	=> BASE.AMP.$this->_base_url.AMP.'method=settings',
				'uninstall_tab'	=> BASE.AMP.$this->_base_url.AMP.'method=delete',
				// Add more right nav items here.
			));
	
			$this->EE->load->helper('form');

			$vars = array(
				'form_open' => form_open($this->_base_url.AMP.'method=delete', '', array('site_id' => $this->_site_id)),
				'minimee_url' => BASE.AMP.'C=addons_extensions'.AMP.'M=extension_settings'.AMP.'file=minimee',
				'nevermind_url' => BASE.AMP.$this->_base_url.AMP.'method=settings',
				'site_id' => $this->_site_id
			);

			return $this->EE->load->view('delete', $vars, TRUE);
		}
	}
	
	public function install()
	{
		if (empty($_POST))
		{
			Msminimee_helper::log($this->EE->lang->line('unauthorized_access'), 1);
		}
		
		else
		{
			// Query DB for any settings
			$settings = Msminimee_helper::get_settings_by_site($this->_site_id);
	
			// if settings already exist, redirect to our form
			if($settings)
			{
				$this->EE->functions->redirect(BASE.AMP.$this->_base_url.AMP.'method=settings');
			}
			
			else
			{
				Msminimee_helper::log('Installing for Site ID ' . $this->_site_id, 3);

				// this ensures we have allowable keys
				require_once PATH_THIRD . 'minimee/classes/Minimee_config.php';
				$config = new Minimee_config();
				
				$row = array(
					'site_id' => $this->_site_id,
					'enabled' => 'yes',
					'settings' => serialize($config->factory()->to_array())
				);

				$this->EE->db->insert('msminimee', $row);
				
				$this->EE->session->set_flashdata(
					'message_success',
				 	$this->EE->lang->line('preferences_installed')
				);
				
				// now redirect to settings
				$this->EE->functions->redirect(BASE.AMP.$this->_base_url.AMP.'method=settings');
			}
		}
	}
	
	public function settings()
	{
		// Query DB for any settings
		$settings = Msminimee_helper::get_settings_by_site($this->_site_id);

		$this->EE->cp->set_right_nav(array(
			'settings_tab'	=> BASE.AMP.$this->_base_url.AMP.'method=settings',
			'uninstall_tab'	=> BASE.AMP.$this->_base_url.AMP.'method=delete',
			// Add more right nav items here.
		));

		// this ensures we have allowable keys
		require_once PATH_THIRD . 'minimee/classes/Minimee_config.php';
		$config = new Minimee_config();
		$settings = array_merge($config->get_allowed(), (array) $settings);

		$this->EE->load->helper('form');
		$this->EE->load->library('table');

		// view vars		
		$vars = array(
			'base_url' => $this->_base_url,
			'site_id' => $this->_site_id,
			'form_open' => form_open($this->_base_url.AMP.'method=save_settings'),
			'settings' => $settings,
			'hide_advanced_on_startup' => false,
			'config_warning' => '',
			'flashdata_success' => $this->EE->session->flashdata('message_success')
			);

		/*
		 * SHIFT OVER TO MINIMEE
		 */
		if(version_compare(APP_VER, '2.8.0', '>='))
		{
			$this->EE->lang->loadfile('minimee');
			$this->EE->load->add_theme_cascade(PATH_THIRD . 'minimee/views/');
			$return = $this->EE->load->view('settings_form', $vars, TRUE);
		}
		else
		{
			$tmp = $this->EE->load->_ci_view_path;
			$this->EE->lang->loadfile('minimee');
			$this->EE->load->_ci_view_path = PATH_THIRD . 'minimee/views/';
			$return = $this->EE->load->view('settings_form', $vars, TRUE);
			$this->EE->load->_ci_view_path = $tmp;
		}
		
		return $return;
	}
	// ------------------------------------------------------
	
	public function save_settings()
	{
		if (empty($_POST))
		{
			Msminimee_helper::log($this->EE->lang->line('unauthorized_access'), 1);
		}
		
		else
		{
			// grab our posted form
			$settings = $_POST;
			
			// checkboxes are funny: if they don't exist in post, they must be explicitly added and set to "no"
			$checkboxes = array(
				'combine_css',
				'combine_js',
				'minify_css',
				'minify_html',
				'minify_js'
			);
			
			foreach($checkboxes as $key)
			{
				if ( ! isset($settings[$key]))
				{
					$settings[$key] = 'no';
				}
			}

			// this ensures we have allowable keys
			require_once PATH_THIRD . 'minimee/classes/Minimee_config.php';
			$config = new Minimee_config();

			// run our $settings through sanitise_settings()
			$settings = $config->sanitise_settings(array_merge($config->get_allowed(), $settings));
			
			// update db
			$this->EE->db->where('site_id', $this->_site_id)
						 ->update('msminimee', array('settings' => serialize($settings)));
			
			Msminimee_helper::log('Msminimee settings have been saved.', 3);

			// save the environment			
			unset($settings);

			// let frontend know we succeeeded
			$this->EE->session->set_flashdata(
				'message_success',
			 	$this->EE->lang->line('preferences_updated')
			);

			$this->EE->functions->redirect(BASE.AMP.$this->_base_url.AMP.'method=settings');
		}
	}
}
/* End of file mcp.msminimee.php */
/* Location: /system/expressionengine/third_party/msminimee/mcp.msminimee.php */