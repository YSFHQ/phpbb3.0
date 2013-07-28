<?php
/**
*
* @package warning_reasons
* @version $Id: functions_warning reasons.php
* @copyright (c) Rich McGirr RMcGirr83
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

// drop down box
function display_warning_reasons($reason_id = 0)
{
	global $db, $template;

	$result = $db->sql_query('SELECT * FROM ' . WARNING_REASONS_TABLE . ' ORDER BY reason_order ASC');

	while ($row = $db->sql_fetchrow($result))
	{
		$reason_description = (string) $row['reason_description'];
		// If the reason is defined within the language file, we will use the localized version, else just use the database entry...
		if (isset($user->lang['warning_reasons']['TITLE'][str_replace(' ','_',strtoupper($row['reason_title']))]) && isset($user->lang['warning_reasons']['DESCRIPTION'][str_replace(' ','_',strtoupper($row['reason_title']))]))
		{
			$reason_description = $user->lang['warning_reasons']['DESCRIPTION'][str_replace(' ','_',strtoupper($row['reason_title']))];
		}
		$template->assign_block_vars('reason', array(
			'ID'			=> $row['reason_id'],
			'TITLE'			=> $row['reason_title'],
			'DESCRIPTION'	=> $reason_description,
			'S_SELECTED'	=> ($row['reason_id'] == $reason_id) ? true : false)
		);
	}
	$db->sql_freeresult($result);
}

// gets added into the note of the user
function warning_reasons($warning_reason_id, $user_id)
{
	global $db, $phpbb_root_path, $phpEx, $user;
	
	// Get the user being warned language var
	$result = $db->sql_query('SELECT user_lang FROM ' . USERS_TABLE . ' WHERE user_id =' . (int) $user_id);
	$user_lang = $db->sql_fetchfield('user_lang');
	$db->sql_freeresult($result);
	
	$result = $db->sql_query('SELECT * FROM ' . WARNING_REASONS_TABLE . ' WHERE reason_id =' . (int) $warning_reason_id);
	$reason = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	// Only load up the language pack if the language is different to the current one
	if ($user_lang != $user->lang_name && file_exists($phpbb_root_path . '/language/' . $user_lang . '/mods/warning_reasons.' . $phpEx))
	{
		// Load up the language pack
		$lang = array();
		@include($phpbb_root_path . '/language/' . basename($user_lang) . '/mods/warning_reasons.' . $phpEx);

		// If we find the reason in this language pack use it
		if (isset($lang['warning_reasons']['TITLE'][str_replace(' ','_',strtoupper($reason['reason_title']))]) && isset($lang['warning_reasons']['DESCRIPTION'][str_replace(' ','_',strtoupper($reason['reason_title']))]))
		{		
			$reason['reason_description'] = $lang['warning_reasons']['DESCRIPTION'][str_replace(' ','_',strtoupper($reason['reason_title']))];
		}

		unset($lang); // Free memory
	}

	return $reason;
}
?>