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
	 * init: define our logging class name
	 * @access  public
	 * @return  void
	 */
	public static function init()
	{
		if (self::$log_name === NULL)
		{
			self::$log_name = MSMINIMEE_NAME;
		}
	}

	/**
	 * Used by module, retrieves settings from module table
	 *
	 * @return void
	 */
	public static function get_settings()
	{
        $ee =& get_instance();
        
		// if settings are already in session cache, use those
		if ( ! isset($ee->session->cache['minimee']['settings']))
		{
			$settings = array();
				
			$ee->db
				->select('settings')
				->from('msminimee')
				->where(array('enabled' => 'y', 'site_id' => (int) $ee->config->item('site_id')))
				->limit(1);
			$query = $ee->db->get();
			
			if ($query->num_rows() > 0)
			{
				$settings = unserialize($query->row()->settings);
			}
			else
			{
				self::log('Could not find any settings to use. Will try default Minimee now.', 2);

				parent::get_settings();
			}
		
			// normalize settings before adding to session
			self::normalize_settings($settings);
			$ee->session->cache['minimee'] = array(
				'settings' => $settings,
				'js' => array(),
				'css' => array()
			);
	
			// free memory where possible			
			unset($settings);
		}
		
		// return settings back to plugin
		return $ee->session->cache['minimee']['settings'];
	}
	// END
}
// END CLASS

/* End of file helper.php */
/* Location: ./system/expressionengine/third_party/msminimee/helper.php */