<?php
/**
*
*===================================================================
*
*  BEGIN Silverbar MOD Functions File
*-------------------------------------------------------------------
*	Script info:
* Version:		 ( 0.6.0 - Beta										)
* Last release:	 ( 6/12/2008  |||  5:46 PM [ GMT - 5 ] 						)
* Copyright: 	 ( (c) 2008 - sTraTo 									)
* License: 		 ( http://opensource.org/licenses/gpl-license.php  |||  GNU Public License 	)
* Package:		 ( Language											)
* Language:		 ( English											)
* Translated by: 	 ( sTraTo											)
*
*===================================================================
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
    exit;
}

if (empty($lang) || !is_array($lang))
{
    $lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
	'SIDEBAR_INFO'				=> 'Silverbar MOD by <a href="http://startrekguide.com/community/memberlist.php?mode=viewprofile%26u=8985" style="color: #AA0000; text-weight: bold;">sTraTo</a><br />',
	'SIDEBAR_WELC'				=> 'Welcome, ',
	'SIDEBAR_VER'				=> '0.6.0 <strong>Beta</strong>',
	'SIDEBAR_BOT_INFO'			=> 'This board uses the Silverbar MOD by <a href="http://startrekguide.com/community/memberlist.php?mode=viewprofile%26u=8985" style="color: #AA0000; text-weight: bold;">sTraTo</a>',
	'UNAPPROVED_TOPIC_ATT'		=> 'One or more topics are awaiting approval!',
	'UNAPPROVED_POST_ATT'		=> 'One or more posts are awaiting approval!', 
	'REPORTED_POST_ATT'			=> 'One or more posts have been reported!',
	'NO_APPROVED_TOPIC_ATT'		=> 'No topics awaiting approval',
	'NO_APPROVED_POST_ATT'		=> 'No posts awaiting approval', 
	'NO_REPORTED_POST_ATT'		=> 'No posts reported',
	'SIDEBAR_REM_ME'			=> 'Remember me',
	'SHOW_HIDE'					=> 'Show/Hide Sidebar',
	
//UCP Extension language entries
	'WHICH_SIDE'				=> 'Side to display the Sidebar on, if enabled',
	'USE_SIDE'					=> 'Enable the sidebar',
	'SIDE_LEFT'					=> 'Left',
	'SIDE_RIGHT'				=> 'Right',
//END UCP Extension language entries

	//Recent topics
	'RECENT_TOPICS'				=> 'Recent Topics',
	));

?>