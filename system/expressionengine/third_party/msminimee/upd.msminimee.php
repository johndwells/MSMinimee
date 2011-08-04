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

require_once PATH_THIRD . 'msminimee/config.php';
require_once PATH_THIRD . 'msminimee/helper.php';

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
	
	private $EE;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();

		MSMinimee_helper::init();
	}
	
	// ----------------------------------------------------------------
	
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
		
		// free up memory
		unset($mod_data, $fields);
		
		// should be bring over settings from Minimee Extensions?
		$this->EE->db
					->select('settings')
					->from('extensions')
					->where(array('enabled' => 'y', 'class' => 'Minimee_ext' ))
					->limit(1);
		$query = $this->EE->db->get();
		
		if ($query->num_rows() > 0)
		{
			$settings = unserialize($query->row()->settings);
			MSMinimee_helper::normalize_settings($settings);
			
			$default_data = array(
				'site_id' => 1,
				'enabled' => 'y',
				'settings' => serialize($settings)	
			);
			$this->EE->db->insert('msminimee', $default_data);
			
			unset($default_data);
		}
		unset($query);
		
		return TRUE;
	}

	// ----------------------------------------------------------------
	
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
		
		return TRUE;
	}
	
	// ----------------------------------------------------------------
	
	/**
	 * Module Updater
	 *
	 * @return 	boolean 	TRUE
	 */	
	public function update($current = '')
	{
		// If you have updates, drop 'em in here.
		return TRUE;
	}
	
}
/* End of file upd.msminimee.php */
/* Location: /system/expressionengine/third_party/msminimee/upd.msminimee.php */