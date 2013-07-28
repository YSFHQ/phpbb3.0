<?php
/**
*
* @package warning_reasons
* @version $Id: acp_warning_reasons.php
* @copyright (c) 2011 Rich McGirr http://rmcgirr83.org
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}
/**
* @package module_install
*/
class acp_warning_reasons_info
{
	function module()
	{
		return array(
			'filename'	=> 'warning_reasons',
			'title'		=> 'ACP_WARNING_REASONS',
			'version'	=> '1.0.4',
			'modes'		=> array(
				'main'		=> array('title' => 'ACP_MANAGE_WARNING_REASONS', 'auth' => 'acl_a_reasons', 'cat' => array('ACP_GENERAL_TASKS')),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}

?>