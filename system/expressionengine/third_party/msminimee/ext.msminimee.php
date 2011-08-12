<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

require_once PATH_THIRD . 'msminimee/config.php';

/**
 * MSMinimee Extension
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Extension
 * @author		John D Wells
 * @link		http://johndwells.com
 */

class Msminimee_ext {
	
	public $settings 		= array();
	public $description		= MSMINIMEE_DESC;
	public $docs_url		= MSMINIMEE_DOCS;
	public $name			= MSMINIMEE_NAME;
	public $version			= MSMINIMEE_VER;
	public $required_by = array('module');
	public $settings_exist	= 'n';
	
	protected $EE;
	
	/**
	 * Constructor
	 *
	 * @param 	mixed	Settings array or empty string if none exist.
	 */
	public function __construct($settings = array())
	{
		$this->EE =& get_instance();

		$this->settings = $settings;

		// fetch current site id
		$this->site_id = (int) $this->EE->config->item('site_id');
	}
	// END
	
	
	/**
	 * Activate Extension
	 *
	 * This function enters the extension into the exp_extensions table
	 *
	 * @see http://codeigniter.com/user_guide/database/index.html for
	 * more information on the db class.
	 *
	 * @return void
	 */
	public function activate_extension()
	{
		return TRUE;
	}	
	// END


	/**
	 * on_minimee_get_current_settings
	 *
	 * @return array
	 */
	public function on_minimee_get_current_settings(&$minimee, $current)
	{
		// return current settings straight away if running default site
		if ($this->site_id === 1) return $current;
	
		// make our MSMinimee query
		$query = $this->EE->db
							->select('settings')
							->from('msminimee')
							->where(array('enabled' => 'y', 'site_id' => $this->site_id))
							->limit(1)
							->get();

		// return settings if found, or an array of defaults
		if ($query->num_rows() > 0)
		{
			// we need this to be 'db' so that the form shows
			$minimee->config_loc = 'db';
			
			// log it
			MSMinimee_helper::log('Configuration settings have been found for site ID #' . $this->EE->config->item('site_id') . '');

			// return settings
			return unserialize($query->row()->settings);
		}
		else
		{
			return $minimee->_default_settings();
		}
	}
	// END

	
	/**
	 * on_minimee_get_settings
	 *
	 * @return array
	 */
	public function on_minimee_get_settings(&$minimee)
	{
		// abort straight away if running default site
		if ($this->site_id === 1) return array();
	
		// make our MSMinimee query
		$query = $this->EE->db
							->select('settings')
							->from('msminimee')
							->where(array('enabled' => 'y', 'site_id' => $this->site_id))
							->limit(1)
							->get();

		// fetch settings or empty array
		$settings = ($query->num_rows() > 0) ? unserialize($query->row()->settings) : array();
		
		// we need to make sure we are not missing any defaults
		if($settings)
		{
			foreach($minimee->_default_settings() as $key => $val)
			{
				if( ! array_key_exists($key, $settings))
				{
					$settings[$key] = $val;
				}
			}
		}
		
		// free up memory
		$query->free_result();

		// set this so minimee knows where settings came from
		$minimee->config_loc = 'hook';
		
		return $settings;
	}
	// END

	
	/**
	 * on_minimee_save_settings
	 *
	 * @param array
	 * @return void
	 */
	public function on_minimee_save_settings(&$minimee, $settings)
	{
		// if we are changing default site, then return the $settings array back untouched
		if ($this->site_id === 1) return $settings;
		
		// EDGE CASE: If a user visits the Minimee extensions page,
		// but then in another tab switches sites, then goes back to save settings,
		// it runs the risk of overwriting the wrong site.
		if($this->site_id !== (int) $this->EE->input->post('site_id'))
		{
			// it's a bit grubby but it's good enough for now
			show_error($this->EE->lang->line('msminimee_unauthorized_access'));
		}

		// let's see if we already have settings saved for this site
		$query = $this->EE->db
							->select('settings')
							->from('msminimee')
							->where(array('enabled' => 'y', 'site_id' => $this->site_id))
							->limit(1)
							->get();

		if($query->num_rows() > 0)
		{
			// update
			$this->EE->db->where('site_id' , $this->site_id);
			$this->EE->db->update('msminimee', array('settings' => serialize($settings)));
		}
		else
		{
			// insert
			$this->EE->db->insert('msminimee', array(
				'site_id' => $this->site_id,
				'enabled' => 'y',
				'settings' => serialize($settings)	
			));
		}
		
		// free memory
		$query->free_result();
		
		// tell minimee that we have successfully saved
		return TRUE;
	}
	// END


	/**
	 * Disable Extension
	 *
	 * This method removes information from the exp_extensions table
	 *
	 * @return void
	 */
	function disable_extension()
	{
		return TRUE;
	}
	// END


	/**
	 * Update Extension
	 *
	 * This function performs any necessary db updates when the extension
	 * page is visited
	 *
	 * @return 	mixed	void on update / false if none
	 */
	function update_extension($current = '')
	{
		return TRUE;
	}	
	// END
}

/* End of file ext.msminimee.php */
/* Location: /system/expressionengine/third_party/msminimee/ext.msminimee.php */