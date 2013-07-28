<?php
/**
*
* @package warning_reasons
* @version $Id: ajax_warning_reasons.php
* @copyright (c) 2011 RMcGirr83 http://rmcgirr83.org
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$user->session_begin(false);
$auth->acl($user->data);
$user->setup('mods/warning_reasons');

if(!$auth->acl_gets('m_warn'))
{
	trigger_error($user->lang['NO_AUTH_OPERATION']);
}

$ajax_warning_id =  request_var('warning_id', 0);

$result = $db->sql_query('SELECT * FROM ' . WARNING_REASONS_TABLE . ' WHERE reason_id =' . (int) $ajax_warning_id);

$row = $db->sql_fetchrow($result);
$db->sql_freeresult($result);

$reason_description = (string) $row['reason_description'];
// If the reason is defined within the language file, we will use the localized version, else just use the database entry...
if (isset($user->lang['warning_reasons']['TITLE'][str_replace(' ','_',strtoupper($row['reason_title']))]) && isset($user->lang['warning_reasons']['DESCRIPTION'][str_replace(' ','_',strtoupper($row['reason_title']))]))
{
	$reason_description = $user->lang['warning_reasons']['DESCRIPTION'][str_replace(' ','_',strtoupper($row['reason_title']))];
}

if (!empty($reason_description))
{
	$return = $user->lang['WARNING_REASON_DESCRIPTION'] . ' ' . $reason_description;
}
else
{
	$return = '';
}

exit($return);
?>
