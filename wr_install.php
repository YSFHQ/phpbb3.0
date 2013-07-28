<?php

/**
*
* @author RMcGirr83 (Rich McGirr) rmcgirr83@gmail.com 
* @package warning_reasons
* @version $Id wr_install.php
* @copyright (c) 2011 RMcGirr83 ( http://www.rmcgirr83.org/ )
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
define('UMIL_AUTO', true);
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
$user->session_begin();
$auth->acl($user->data);
$user->setup();


if (!file_exists($phpbb_root_path . 'umil/umil_auto.' . $phpEx))
{
	trigger_error('Please download the latest UMIL (Unified MOD Install Library) from: <a href="http://www.phpbb.com/mods/umil/">phpBB.com/mods/umil</a>', E_USER_ERROR);
}

/*
* The language file which will be included when installing
* Language entries that should exist in the language file for UMIL (replace $mod_name with the mod's name you set to $mod_name above)
* $mod_name
* 'INSTALL_' . $mod_name
* 'INSTALL_' . $mod_name . '_CONFIRM'
* 'UPDATE_' . $mod_name
* 'UPDATE_' . $mod_name . '_CONFIRM'
* 'UNINSTALL_' . $mod_name
* 'UNINSTALL_' . $mod_name . '_CONFIRM'
*/
$language_file = 'mods/warning_reasons';

// The name of the mod to be displayed during installation.
$mod_name = 'WR_TITLE';

/*
* The name of the config variable which will hold the currently installed version
* You do not need to set this yourself, UMIL will handle setting and updating the version itself.
*/
$version_config_name = 'wr_version';

/*
* The array of versions and actions within each.
* You do not need to order it a specific way (it will be sorted automatically), however, you must enter every version, even if no actions are done for it.
*
* You must use correct version numbering.  Unless you know exactly what you can use, only use X.X.X (replacing X with an integer).
* The version numbering must otherwise be compatible with the version_compare function - http://php.net/manual/en/function.version-compare.php
*/
$versions = array(
	// Version 1.0.0
	'1.0.0'	=> array(
		// nothing to do
	),
	// Version 1.0.1
	'1.0.1'	=> array(
		// nothing to do
	),
	// Version 1.0.2
	'1.0.2' => array(
		// nothing to do
	),
	// Version 1.0.2a
	'1.0.2a' => array(
		// nothing to do
	),
	// Version 1.0.3
	'1.0.3' => array(
		// Nothing changed in this version.
	),
	// Version 1.0.4
	'1.0.4' => array(
		'custom' => 'wr_module',
	),
);

// Include the UMIF Auto file and everything else will be handled automatically.
include($phpbb_root_path . 'umil/umil_auto.' . $phpEx);

/*
* Here is our custom function that will be called
*
* @param string $action The action (install|update|uninstall) will be sent through this.
* @param string $version The version this is being run for will be sent through this.
*/
function wr_module($action, $version)
{
	global $db, $umil;

	if ($action == 'install')
	{
		// Run this when installing
		if (!$umil->table_exists(WARNING_REASONS_TABLE))
		{
			// Now to add a table (this uses the layout from develop/create_schema_files.php and from phpbb_db_tools)
			$umil->table_add(WARNING_REASONS_TABLE, array(
				'COLUMNS'		=> array(
					'reason_id'				=> array('USINT', NULL, 'auto_increment'),
					'reason_title'			=> array('VCHAR', ''),
					'reason_description'	=> array('MTEXT_UNI', ''),
					'reason_order'			=> array('USINT', 0),
				),
				'PRIMARY_KEY' => 'reason_id',
			));
			// Now to add some stuffs to the table
			$sql_ary = array(
				'reason_title'			=> 'Other',
				'reason_description'	=> 'The warning does not fit into any other category, please use the further information field.',
				'reason_order'			=> 1,
			);
			$sql = 'INSERT INTO ' . WARNING_REASONS_TABLE . $db->sql_build_array('INSERT', $sql_ary);
			$db->sql_query($sql);			
		}
		
		if(!$umil->module_exists('acp', 'ACP_GENERAL_TASKS', 'ACP_MANAGE_WARNING_REASONS'))
		{	
			// Alright, now lets add some modules to the ACP
			$umil->module_add('acp', 'ACP_GENERAL_TASKS', array(
					'module_basename'	=> 'warning_reasons',
					'modes'	=> array('main'),
			));
		}
		return 'WR_INSTALL_DONE';
	}
	if($action == 'uninstall')
	{
		$umil->table_remove('phpbb_warning_reasons');
		$umil->module_remove('acp', 'ACP_GENERAL_TASKS', 'ACP_MANAGE_WARNING_REASONS');
	}
}

?>