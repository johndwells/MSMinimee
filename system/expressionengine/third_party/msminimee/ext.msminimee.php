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
		$rows = array(
			array(
				'class'		=> __CLASS__,
				'method'	=> 'on_minimee_get_settings',
				'hook'		=> 'minimee_get_settings',
				'settings'	=> '',
				'version'	=> $this->version,
				'enabled'	=> 'y'
			),
			array(
				'class'		=> __CLASS__,
				'method'	=> 'on_minimee_save_settings',
				'hook'		=> 'minimee_save_settings',
				'settings'	=> '',
				'version'	=> $this->version,
				'enabled'	=> 'y'
			)
		);

		foreach($rows as $row)
		{
			$this->EE->db->insert('extensions', $row);
		}
		
		unset($rows);
		
	}	
	// END


	/**
	 * on_minimee_get_settings
	 *
	 * @return array
	 */
	public function on_minimee_get_settings()
	{
		// make our MSMinimee query
		$query = $this->EE->db
							->select('settings')
							->from('msminimee')
							->where(array('enabled' => 'y', 'site_id' => (int) $this->EE->config->item('site_id')))
							->limit(1)
							->get();

		// return settings or empty array
		return ($query->num_rows() > 0) ? unserialize($query->row()->settings) : array();
	}
	// END

	
	/**
	 * on_minimee_save_settings
	 *
	 * @param array
	 * @return void
	 */
	public function on_minimee_save_settings($settings)
	{
		if($this->on_minimee_get_settings())
		{
			// update
			$this->EE->db->where('site_id' , (int) $this->EE->config->item('site_id'));
			$this->EE->db->update('msminimee', array('settings' => serialize($settings)));
		} else {
			// insert
			$row = array(
				'site_id' => (int) $this->EE->config->item('site_id'),
				'enabled' => 'y',
				'settings' => serialize($settings)	
			);
			$this->EE->db->insert('msminimee', $row);
		}
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
		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->delete('extensions');

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
		if ($current == '' OR $current == $this->version)
		{
			return FALSE;
		}
	}	
	// END
}

/* End of file ext.msminimee.php */
/* Location: /system/expressionengine/third_party/msminimee/ext.msminimee.php */