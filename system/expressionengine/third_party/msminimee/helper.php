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

/**
 * MSMinimee Helper
 * @author John D Wells <http://johndwells.com>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD license
 * @link	http://johndwells.com/software/minimee
 */
class MSMinimee_helper
{
	public $omnilog		= NULL;

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
	 * Logs a message to template - if not in CP
	 *
	 * @access  public
	 * @param   string      $message        The log entry message.
	 * @param   int         $severity       The log entry 'level'.
	 * @return  void
	 */
	public function log($message, $severity = 1)
	{
		// no template logging when in CP
		if (REQ != 'CP')
		{
	   		$type = ($severity == 3) ? 'ERROR' : (($severity == 2) ? 'WARNING' : 'NOTICE');
			$this->EE->TMPL->log_item(MSMINIMEE_NAME . " [{$type}]: {$message}");
			unset($type);
		}
		
		// uncomment if you have Omnilog installed and need to do debug
		// $this->omnilog($message, $severity);
	}
	// END


	/**
	 * Logs a message to Omnilog
	 *
	 * @access  public
	 * @param   string      $message        The log entry message.
	 * @param   int         $severity       The log entry 'level'.
	 * @return  void
	 */
	public function omnilog($message, $severity = 1)
	{
        $this->EE =& get_instance();

		if ($this->omnilog === NULL)
		{
			if(REQ != 'CP' && array_key_exists('Omnilog', $this->EE->TMPL->module_data))
			{
				$this->omnilog = TRUE;
			}
			else
			{
				$omnilog = $this->EE->db->query("SELECT * FROM exp_modules WHERE module_name = 'Omnilog'");
				if ($omnilog->num_rows > 0)
				{
					$this->omnilog = TRUE;
				}
				else
				{
					$this->omnilog = FALSE;
				}
			}
		}
		
		// i can has omnilog?
		if($this->omnilog)
		{
			// Load the OmniLogger class.
			if (is_file(PATH_THIRD .'omnilog/classes/omnilogger' .EXT))
			{
				include_once PATH_THIRD .'omnilog/classes/omnilogger' .EXT;
			}
	
			if (class_exists('Omnilog_entry') && class_exists('Omnilogger'))
			{
				switch ($severity)
				{
					case 3:
						$notify = TRUE;
						$type   = Omnilog_entry::ERROR;
					break;
		
					case 2:
						$notify = FALSE;
						$type   = Omnilog_entry::WARNING;
					break;
		
					case 1:
					default:
						$notify = FALSE;
						$type   = Omnilog_entry::NOTICE;
					break;
				}
		
				$omnilog_entry = new Omnilog_entry(array(
					'addon_name'    => MSMINIMEE_NAME,
					'date'          => time(),
					'message'       => $message,
					'notify_admin'  => $notify,
					'type'          => $type
				));
		
				Omnilogger::log($omnilog_entry);
				
				// free memory where possible
				unset($notify, $omnilog_entry, $type);
			}
		}
	}
	// END

}
// END CLASS

/* End of file helper.php */
/* Location: ./system/expressionengine/third_party/msminimee/helper.php */