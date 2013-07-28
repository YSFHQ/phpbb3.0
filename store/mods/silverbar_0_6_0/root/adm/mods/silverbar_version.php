<?php
/**
*
* @package acp
* @version $Id: mod_version_check_version.php 48 2007-09-23 20:23:14Z Handyman $
* @copyright (c) 2005 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @package silverbar
*/
class silverbar_version
{
	function version()
	{
		return array(
			'author'	=> 'Desdenova',
			'title'		=> 'SilverBar MOD',
			'tag'		=> 'silverbar',
			'version'	=> '0.6.0',
			'file'		=> array('phpbbcircus.net', 'silverbar/versions', 'silverbar.xml'),
		);
	}
}

?>