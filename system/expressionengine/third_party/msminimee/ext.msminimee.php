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
 * MSMinimee Extension
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Extension
 * @author		John D Wells
 * @link		http://johndwells.com
 */

class Msminimee_ext {
	
	/**
	 * EE, obviously
	 */
	private $EE;


	/**
	 * Standard Extension stuff
	 */
	public $name			= MSMINIMEE_NAME;
	public $version			= MSMINIMEE_VER;
	public $description		= MSMINIMEE_DESC;
	public $docs_url		= MSMINIMEE_DOCS;
	public $required_by		= array('module');
	public $settings 		= array();
	public $settings_exist	= 'n';
	
	
	protected $_site_id		= 1;				// default
	
	/**
	 * Reference to our cache
	 */
	public $cache;


	// ------------------------------------------------------


	/**
	 * Constructor
	 *
	 * @param 	mixed	Settings array - only passed when activating a hook
	 * @return void
	 */
	public function __construct($settings = array())
	{
		// got EE?
		$this->EE =& get_instance();

		// fetch current site id
		$this->_site_id = (int) $this->EE->config->item('site_id');

		// grab reference to our cache
		$this->cache =& Msminimee_helper::cache();
	}
	// ------------------------------------------------------
	
	
	/**
	 * Activate Extension
	 *
	 * All of this is maintained by upd.msminimee.php
	 *
	 * @return bool TRUE
	 */
	public function activate_extension()
	{
		return TRUE;
	}	
	// ------------------------------------------------------


	/**
	 * on_minimee_get_current_settings
	 *
	 * @return mixed	Array of settings or FALSE
	 */
	public function minimee_get_settings($M)
	{

		// As a precaution, do nothing if not on the front-end
		if (REQ != 'PAGE')
		{
			return FALSE;
		}

		$settings = Msminimee_helper::get_settings_by_site($this->_site_id);
		
		// if we have some settings, return them
		if (is_array($settings) && count($settings) > 0)
		{
			Msminimee_helper::log('Returning settings for site ID #' . $this->_site_id, 3);

			$M->location = 'msminimee';
			return $settings;
		}
		
		else
		{
			Msminimee_helper::log('No settings found for site ID #' . $this->_site_id, 2);
			return FALSE;
		}
	}
	// ------------------------------------------------------

	
	/**
	 * Disable Extension
	 *
	 * All of this is maintained by upd.msminimee.php
	 *
	 * @return bool TRUE
	 */
	function disable_extension()
	{
		return TRUE;
	}
	// ------------------------------------------------------


	/**
	 * Update Extension
	 *
	 * All of this is maintained by upd.msminimee.php
	 *
	 * @return bool TRUE
	 */
	function update_extension($current = '')
	{
		return TRUE;
	}	
	// ------------------------------------------------------
}

/* End of file ext.msminimee.php */
/* Location: /system/expressionengine/third_party/msminimee/ext.msminimee.php */