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
 * MSMinimee Module Install/Update File
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Module
 * @author		John D Wells
 * @link		http://johndwells.com
 */
class Msminimee_upd {

	public $version = MSMINIMEE_VER;
	
	protected $EE;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();
	}
	
	// END
	
	/**
	 * Installation Method
	 *
	 * @return 	boolean 	TRUE
	 */
	public function install()
	{
		$mod_data = array(
			'module_name'			=> 'Msminimee',
			'module_version'		=> $this->version,
			'has_cp_backend'		=> "y",
			'has_publish_fields'	=> 'n'
		);
		
		$this->EE->db->insert('modules', $mod_data);

		// Create custom table
		$this->EE->load->dbforge();
		$fields = array(
			'site_id'   => array('type' => 'int', 'constraint' => 5, 'unsigned' => TRUE),
			'enabled'  => array('type' => 'char', 'constraint' => 1),
			'settings'      => array('type' => 'text')
		);

		$this->EE->dbforge->add_field($fields);
		$this->EE->dbforge->add_key('site_id', TRUE);
		$this->EE->dbforge->create_table('msminimee');

		// install extension
		$rows = array(
			array(
				'class'		=> 'Msminimee_ext',
				'method'	=> 'minimee_get_settings',
				'hook'		=> 'minimee_get_settings',
				'settings'	=> '',
				'version'	=> $this->version,
				'enabled'	=> 'y'
			)
		);

		foreach($rows as $row)
		{
			$this->EE->db->insert('extensions', $row);
		}
		
		// free up memory
		unset($rows, $mod_data, $fields);
		
		return TRUE;
	}
	// END

	
	/**
	 * Uninstall
	 *
	 * @return 	boolean 	TRUE
	 */	
	public function uninstall()
	{
		$mod_id = $this->EE->db->select('module_id')
								->get_where('modules', array(
									'module_name'	=> 'Msminimee'
								))->row('module_id');
		
		$this->EE->db->where('module_id', $mod_id)
					 ->delete('module_member_groups');
		
		$this->EE->db->where('module_name', 'Msminimee')
					 ->delete('modules');

		// drop table		
		$this->EE->load->dbforge();
		$this->EE->dbforge->drop_table('msminimee');

		// remove extension settings
		$this->EE->db->where('class', 'Msminimee_ext');
		$this->EE->db->delete('extensions');
		
		return TRUE;
	}
	// END

	
	/**
	 * Module Updater
	 *
	 * @return 	boolean 	TRUE
	 */	
	public function update($current = '')
	{
		return TRUE;
	}
	// END
	
}
/* End of file upd.msminimee.php */
/* Location: /system/expressionengine/third_party/msminimee/upd.msminimee.php */