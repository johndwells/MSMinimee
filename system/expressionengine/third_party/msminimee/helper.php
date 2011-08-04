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
 
// END--------

require_once PATH_THIRD . 'minimee/helper.php';

/**
 * MSMinimee Helper
 * @author John D Wells <http://johndwells.com>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD license
 * @link	http://johndwells.com/software/minimee
 */
class MSMinimee_helper extends Minimee_helper
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();
		$this->log_name = MSMINIMEE_NAME;
		$this->cache_key = MSMINIMEE_KEY;
	}
	// END
	

	/**
	 * Used by module, retrieves settings from module table
	 *
	 * @return void
	 */
	public function get_settings()
	{
        
		// if settings are already in session cache, use those
		if ( ! isset($this->EE->session->cache[$this->cache_key]['settings']))
		{
			$settings = array();
				
			$this->EE->db
				->select('settings')
				->from('msminimee')
				->where(array('enabled' => 'y', 'site_id' => (int) $this->EE->config->item('site_id')))
				->limit(1);
			$query = $this->EE->db->get();
			
			if ($query->num_rows() > 0)
			{
				$settings = unserialize($query->row()->settings);
			}
			else
			{
				$this->log('Could not find any Module settings for this site. Will now try in the normal Minimee places (config, global_vars, and then Extension).', 2);

				parent::get_settings();
			}
		
			// normalize settings before adding to session
			$this->normalize_settings($settings);
			$this->EE->session->cache[$this->cache_key] = array(
				'settings' => $settings,
				'js' => array(),
				'css' => array()
			);
	
			// free memory where possible			
			unset($settings);
		}
		
		// return settings back to module
		return $this->EE->session->cache[$this->cache_key]['settings'];
	}
	// END
}
// END CLASS

/* End of file helper.php */
/* Location: ./system/expressionengine/third_party/msminimee/helper.php */