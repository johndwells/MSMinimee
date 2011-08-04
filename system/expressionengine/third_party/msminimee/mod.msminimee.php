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
require_once PATH_THIRD . 'minimee/pi.minimee.php';

/**
 * MSMinimee Module Front End File
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Module
 * @author		John D Wells
 * @link		http://johndwells.com
 */

class Msminimee extends Minimee {
	
	
	public function __construct()
	{
		MSMinimee_helper::init();
		
		parent::__construct();
	}
	
	/**
	 * Override _init so that we can first see if we have settings for site
	 */
	protected function _init()
	{
		// Our helper function will put our settings into session
		MSMinimee_helper::get_settings();
	
		// chaining !
		return parent::_init();
	}
	
}
/* End of file mod.msminimee.php */
/* Location: /system/expressionengine/third_party/msminimee/mod.msminimee.php */