<?php
/**
*
* @package AJAX User Flag
* @version $Id: ajax_user_flag.php, V1.0.0 2010-09-12 rmcgirr83 $
* @copyright (c) 2010 RMcGirr83 http://rmcgirr83.org
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
$user->setup();

$ajax_flag_id =  request_var('flag_id', 0);

if (empty($ajax_flag_id))
{
	return;
}


if (!empty($ajax_flag_id))
{
	$flag = $cache->get('_user_flags');
	$flag_img = $flag[$ajax_flag_id]['flag_image'];
	$flag_img = "{$phpbb_root_path}images/flags/$flag_img";
	$flag_name = $flag[$ajax_flag_id]['flag_name'];
	
	$return = '<img src="' . $flag_img . '" alt="' . $flag_name .'" title="' . $flag_name .'" style="vertical-align:middle;" />';
}
else
{
	$return = '';
}
    echo $return;
    exit_handler();
?>