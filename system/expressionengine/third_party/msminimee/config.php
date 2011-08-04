<?php
if (! defined('MSMSMINIMEE_VER'))
{
	define('MSMINIMEE_NAME', 'MSMinimee');
	define('MSMINIMEE_VER',  '1.0.0');
	define('MSMINIMEE_AUTHOR',  'John D Wells');
	define('MSMINIMEE_DOCS',  'http://johndwells.com/software/msminimee');
	define('MSMINIMEE_DESC',  'A Module that makes Minimee fully MSM-compatible.');
}

$config['name'] = MSMINIMEE_NAME;
$config['version'] = MSMINIMEE_VER;
$config['nsm_addon_updater']['versions_xml'] = 'http://johndwells.com/software/versions/msminimee';