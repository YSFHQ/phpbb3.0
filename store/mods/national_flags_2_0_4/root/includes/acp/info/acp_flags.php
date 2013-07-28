<?php
/**
*
* @package National Flags
* @version $Id: acp_flags.php
* @copyright (c) 2010 RMcGirr83
* @copyright (c) 2009 Anybloodyid
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* @package module_install
*/
class acp_flags_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_flags',
			'title'		=> 'ACP_FLAGS',
			'version'	=> '2.0.3',
			'modes'		=> array(
				'config'	=> array('title' => 'ACP_FLAG_SETTINGS', 'auth' => 'acl_a_board', 'cat' => array('ACP_CAT_FLAGS')),
				'flags'		=> array('title' => 'ACP_FLAGS', 'auth' => 'acl_a_board', 'cat' => array('ACP_CAT_FLAGS')),
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