<?php

require_once PATH_THIRD . 'msminimee/config.php';

/**
 * MSMinimee Helper
 * @author John D Wells <http://johndwells.com>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD license
 * @link	http://johndwells.com/software/msminimeee
 */
class Msminimee_helper {

	/**
	 * Logging levels
	 */
	private static $_levels = array(
		1 => 'ERROR',
		2 => 'DEBUG',
		3 => 'INFO'
	);
	// ----------------------------------------------
	
	
	/**
	 * Fetch our settings by ID
	 *
	 * @return 	Mixed	Array of settings - empty if no settings saved yet
	 */
	public static function get_settings_by_site($site_id)
	{
		$cache =& self::cache();

		// see if we have already looked up the site settings in our cache
		if ( ! array_key_exists($site_id, $cache))
		{
		
		
			// make our MSMinimee query
			$query = get_instance()->db
								->select('settings')
								->from('msminimee')
								->where(array('enabled' => 'y', 'site_id' => $site_id))
								->limit(1)
								->get();
	
			if ($query->num_rows() > 0)
			{
				// log it
				self::log('Configuration settings have been found for site ID #' . $site_id, 3);
	
				// return settings
				$cache[$site_id] = unserialize($query->row()->settings);
			}
			
			else
			{
				$cache[$site_id] = array();
			}
			
			$query->free_result();
		}
		
		return $cache[$site_id];
	}
	// ------------------------------------------------------


	/**
	 * Create an alias to our cache
	 *
	 * @return 	Array	Our cache in EE->session->cache
	 */
	public static function &cache()
	{
		$ee =& get_instance();

		// be sure we have a cache set up
		if ( ! isset($ee->session->cache['msminimee']))
		{
			$ee->session->cache['msminimee'] = array();

			self::log('Session cache has been created.', 3);
		}
		
		return $ee->session->cache['msminimee'];
	}
	// ------------------------------------------------------


	/**
	 * Log method
	 *
	 * By default will pass message to log_message();
	 * Also will log to template if rendering a PAGE.
	 *
	 * @access  public
	 * @param   string      $message        The log entry message.
	 * @param   int         $severity       The log entry 'level'.
	 * @return  void
	 */
	public static function log($message, $severity = 1)
	{
		// translate our severity number into text
		$severity = (array_key_exists($severity, self::$_levels)) ? self::$_levels[$severity] : self::$_levels[1];

		// basic EE logging
		log_message($severity, MSMINIMEE_NAME . ": {$message}");

		// If not in CP, let's also log to template
		if (REQ == 'PAGE')
		{
			get_instance()->TMPL->log_item(MSMINIMEE_NAME . " [{$severity}]: {$message}");
		}

		// If we are in CP and encounter an error, throw a nasty show_message()
		if (REQ == 'CP' && $severity == self::$_levels[1])
		{
			show_error(MSMINIMEE_NAME . " [{$severity}]: {$message}");
		}
	}
	// ------------------------------------------------------
}
// END CLASS

/* End of file Msminimee_helper.php */
/* Location: ./system/expressionengine/third_party/msminimee/Msminimee_helper.php */