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
* Package:		 ( phpBB3											)
*
*===================================================================
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
* Get user avatar
*
* @param string $avatar Users assigned avatar name
* @param int $avatar_type Type of avatar
* @param string $avatar_width Width of users avatar
* @param string $avatar_height Height of users avatar
* @param string $alt Optional language string for alt tag within image, can be a language key or text
*
* @return string Avatar image
*/
function get_my_user_avatar($avatar, $avatar_type, $avatar_width, $avatar_height, $alt = 'USER_AVATAR')
{
global $user, $config, $phpbb_root_path, $phpEx;

if (empty($avatar) || !$avatar_type)
{
return '';
}

$avatar_img = '';

switch ($avatar_type)
{
case AVATAR_UPLOAD:
$avatar_img = $phpbb_root_path . "download/file.$phpEx?avatar=";
break;

case AVATAR_GALLERY:
$avatar_img = $phpbb_root_path . $config['avatar_gallery_path'] . '/';
break;
}

$avatar_img .= $avatar;
return '<img src="' . $avatar_img . '" width="' . $avatar_width . '" height="' . $avatar_height . '" alt="' . ((!empty($user->lang[$alt])) ? $user->lang[$alt] : $alt) . '" />';
}

/** 
*
*END GET_MY_USER_AVATAR FUNCTION
*
*/


/****\
	*
	* @function		grab_recent_topics
	* @description		Grabs the most recent topics and prepares to display them.
	* @globals			$db for queries, $user for permissions checking, $config if needed, $auth for permissions check, __
	* 					$phpbb_root_path for filepath, $phpEx for PHP extension, $template for template file prep
	*
	* @param integer		$limit_amt	(Number of topics to grab)
	*
	* @usage-example	grab_recent_topics(10);
	* @usage-example	grab_recent_topics();
	*
	* @copyright 		(c) nickvergessen ( http://mods.flying-bits.org/ )
	*					( Code reused from the NV Recent Topics MOD, legal under the GNU GPL v2 )
	* @function-license 	http://opensource.org/licenses/gpl-license.php ( GNU Public License )
	*
	*
\****/
	
	function grab_recent_topics($limit_amt = 5)
	{
		global $db, $auth, $user, $template;
		global $phpbb_root_path, $phpEx, $config;
		
		$user_id = $user->data['user_id'];
		$limit = 1;
		if ($limit_amt >= $limit)
		{
			$limit = $limit_amt;
		}
		
		if ($limit_amt >= 10)
		{
			$limit = 10;
		}
		
		$template->assign_vars(array(
			'RT_DISPLAY'				=> true,
			'NEWEST_POST_IMG'			=> $user->img('icon_topic_newest', 'VIEW_NEWEST_POST'),
		));

		$rt_anti_topics = '0';
		$onlyforum = 0;
    $onlyforum = request_var('f', 0);
    $rt_anti_forums = ($onlyforum=='20') ? '0' : '0';
		$sql = 'SELECT * FROM ' . FORUMS_TABLE . "
					ORDER BY left_id";
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			if (strstr($onlyforum, $row['parent_id']))
			{
				$onlyforum .= ', ' . $row['forum_id'];
			}
		}
		$db->sql_freeresult($result);
		$forum_ary = array();
		$forum_read_ary = $auth->acl_getf('f_read');
		foreach ($forum_read_ary as $forum_id => $allowed)
		{
			if ($allowed['f_read'])
			{
				$forum_ary[] = (int) $forum_id;
			}
		}
		$forum_ary = array_unique($forum_ary);
		$forum_sql = (sizeof($forum_ary)) ? $db->sql_in_set('t.forum_id', $forum_ary, false) : $db->sql_in_set('t.forum_id', '0', false);

		$keeponrunning = $topic_id = 0;

		$sql = 'SELECT t.*, i.icons_url, i.icons_width, i.icons_height, tp.topic_posted, f.forum_name, f.forum_type, f.forum_flags
			FROM ' . TOPICS_TABLE . ' t
			LEFT JOIN ' . TOPICS_POSTED_TABLE . ' tp
				ON (t.topic_id = tp.topic_id
					AND tp.user_id = ' . $user_id . ')
			LEFT JOIN ' . FORUMS_TABLE . ' f
				ON f.forum_id = t.forum_id
			LEFT JOIN ' . ICONS_TABLE . ' i
				ON t.icon_id = i.icons_id
			WHERE
				(
					' . $forum_sql . '
          ' . (($onlyforum) ? ' AND f.forum_id IN (' . $onlyforum . ')': '') . '
          ' . (($rt_anti_forums) ? ' AND f.forum_id NOT IN (' . $rt_anti_forums . ')': '') . "
					" . (($rt_anti_topics) ? ' AND t.topic_id not IN (' . $rt_anti_topics . ')': '') . "
				)
				OR t.topic_type IN (" . POST_GLOBAL . ")
			GROUP BY t.topic_last_post_id
			ORDER BY t.topic_last_post_time DESC
			LIMIT $limit";
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$topic_id = $row['topic_id'];
			$forum_id = $row['forum_id'];
			$s_type_switch_test = ($row['topic_type'] == POST_ANNOUNCE || $row['topic_type'] == POST_GLOBAL) ? 1 : 0;
			$replies = ($auth->acl_get('m_approve', $forum_id)) ? $row['topic_replies_real'] : $row['topic_replies'];
			$topic_tracking_info = get_complete_topic_tracking($forum_id, $topic_id, $global_announce_list = false);
			$unread_topic = (isset($topic_tracking_info[$topic_id]) && $row['topic_last_post_time'] > $topic_tracking_info[$topic_id]) ? true : false;
			$folder_img = $folder_alt = $topic_type = $folder = $folder_new = '';
			switch ($row['topic_type'])
			{
				case POST_GLOBAL:
					$topic_type = $user->lang['VIEW_TOPIC_GLOBAL'];
					$folder = 'global_read';
					$folder_new = 'global_unread';
					$forum_id = request_var('sf', 2);
				break;
				case POST_ANNOUNCE:
					$topic_type = $user->lang['VIEW_TOPIC_ANNOUNCEMENT'];
					$folder = 'announce_read';
					$folder_new = 'announce_unread';
				break;
				case POST_STICKY:
					$topic_type = $user->lang['VIEW_TOPIC_STICKY'];
					$folder = 'sticky_read';
					$folder_new = 'sticky_unread';
				break;
				default:
					$topic_type = '';
					$folder = 'topic_read';
					$folder_new = 'topic_unread';
					if ($config['hot_threshold'] && $replies >= $config['hot_threshold'] && $row['topic_status'] != ITEM_LOCKED)
					{
						$folder .= '_hot';
						$folder_new .= '_hot';
					}
				break;
			}
			if ($row['topic_status'] == ITEM_LOCKED)
			{
				$topic_type = $user->lang['VIEW_TOPIC_LOCKED'];
				$folder .= '_locked';
				$folder_new .= '_locked';
			}
			if ($row['topic_posted'])
			{
				$folder .= '_mine';
				$folder_new .= '_mine';
			}
			if ($row['topic_type'] == POST_GLOBAL)
			{
				$global_announce_list[$row['topic_id']] = true;
			}
			$folder_img = ($unread_topic) ? $folder_new : $folder;
			$folder_alt = ($unread_topic) ? 'NEW_POSTS' : (($row['topic_status'] == ITEM_LOCKED) ? 'TOPIC_LOCKED' : 'NO_NEW_POSTS');
			if ($row['poll_start'] && $row['topic_status'] != ITEM_MOVED)
			{
				$topic_type = $user->lang['VIEW_TOPIC_POLL'];
			}
			$view_topic_url = append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'f=' . (($row['forum_id']) ? $row['forum_id'] : $forum_id) . '&amp;t=' . $topic_id);
			$view_forum_url = append_sid("{$phpbb_root_path}viewforum.$phpEx", 'f=' . $forum_id);
			$topic_unapproved = (!$row['topic_approved'] && $auth->acl_get('m_approve', $forum_id)) ? true : false;
			$posts_unapproved = ($row['topic_approved'] && $row['topic_replies'] < $row['topic_replies_real'] && $auth->acl_get('m_approve', $forum_id)) ? true : false;
			$u_mcp_queue = ($topic_unapproved || $posts_unapproved) ? append_sid("{$phpbb_root_path}mcp.$phpEx", 'i=queue&amp;mode=' . (($topic_unapproved) ? 'approve_details' : 'unapproved_posts') . "&amp;t=$topic_id", true, $user->session_id) : '';
			$s_type_switch = ($row['topic_type'] == POST_ANNOUNCE || $row['topic_type'] == POST_GLOBAL) ? 1 : 0;
			$template->assign_block_vars('recenttopicrow', array(
				'FORUM_ID'					=> $forum_id,
				'TOPIC_ID'					=> $topic_id,
				'TOPIC_AUTHOR_FULL'			=> get_username_string('full', $row['topic_poster'], $row['topic_first_poster_name'], $row['topic_first_poster_colour']),
				'FIRST_POST_TIME'			=> $user->format_date($row['topic_time']),
				'LAST_POST_SUBJECT'			=> censor_text($row['topic_last_post_subject']),
				'LAST_POST_TIME'			=> $user->format_date($row['topic_last_post_time']),
				'LAST_VIEW_TIME'			=> $user->format_date($row['topic_last_view_time']),
				'LAST_POST_AUTHOR'			=> get_username_string('username', $row['topic_last_poster_id'], $row['topic_last_poster_name'], $row['topic_last_poster_colour']),
				'LAST_POST_AUTHOR_COLOUR'	=> get_username_string('colour', $row['topic_last_poster_id'], $row['topic_last_poster_name'], $row['topic_last_poster_colour']),
				'LAST_POST_AUTHOR_FULL'		=> get_username_string('full', $row['topic_last_poster_id'], $row['topic_last_poster_name'], $row['topic_last_poster_colour']),
				//'PAGINATION'				=> topic_generate_pagination($replies, $view_topic_url),
				'REPLIES'					=> $replies,
				'VIEWS'						=> $row['topic_views'],
				'TOPIC_TITLE'				=> censor_text($row['topic_title']),
				'FORUM_NAME'				=> $row['forum_name'],
				'TOPIC_TYPE'				=> $topic_type,
				'TOPIC_FOLDER_IMG'			=> $user->img($folder_img, $folder_alt),
				'TOPIC_FOLDER_IMG_SRC'		=> $user->img($folder_img, $folder_alt, false, '', 'src'),
				'TOPIC_FOLDER_IMG_ALT'		=> $user->lang[$folder_alt],
				'NEWEST_POST_IMG'			=> $user->img('icon_topic_newest', 'VIEW_NEWEST_POST'),
				'TOPIC_ICON_IMG'			=> (!empty($row['icons_url'])) ? $row['icons_url'] : '',
				'TOPIC_ICON_IMG_WIDTH'		=> (!empty($row['icons_url'])) ? $row['icons_width'] : '',
				'TOPIC_ICON_IMG_HEIGHT'		=> (!empty($row['icons_url'])) ? $row['icons_height'] : '',
				'ATTACH_ICON_IMG'			=> ($auth->acl_get('u_download') && $auth->acl_get('f_download', $forum_id) && $row['topic_attachment']) ? $user->img('icon_topic_attach', $user->lang['TOTAL_ATTACHMENTS']) : '',
				'UNAPPROVED_IMG'			=> ($topic_unapproved || $posts_unapproved) ? $user->img('icon_topic_unapproved', ($topic_unapproved) ? 'TOPIC_UNAPPROVED' : 'POSTS_UNAPPROVED') : '',
				'S_TOPIC_TYPE'				=> $row['topic_type'],
				'S_USER_POSTED'				=> (isset($row['topic_posted']) && $row['topic_posted']) ? true : false,
				'S_UNREAD_TOPIC'			=> $unread_topic,
				'S_TOPIC_REPORTED'			=> (!empty($row['topic_reported']) && $auth->acl_get('m_report', $forum_id)) ? true : false,
				'S_TOPIC_UNAPPROVED'		=> $topic_unapproved,
				'S_POSTS_UNAPPROVED'		=> $posts_unapproved,
				'S_HAS_POLL'				=> ($row['poll_start']) ? true : false,
				'S_POST_ANNOUNCE'			=> ($row['topic_type'] == POST_ANNOUNCE) ? true : false,
				'S_POST_GLOBAL'				=> ($row['topic_type'] == POST_GLOBAL) ? true : false,
				'S_POST_STICKY'				=> ($row['topic_type'] == POST_STICKY) ? true : false,
				'S_TOPIC_LOCKED'			=> ($row['topic_status'] == ITEM_LOCKED) ? true : false,
				'S_TOPIC_MOVED'				=> ($row['topic_status'] == ITEM_MOVED) ? true : false,
				'U_NEWEST_POST'				=> $view_topic_url . '&amp;view=unread#unread',
				'U_LAST_POST'				=> $view_topic_url . '&amp;p=' . $row['topic_last_post_id'] . '#p' . $row['topic_last_post_id'],
				'U_LAST_POST_AUTHOR'		=> get_username_string('profile', $row['topic_last_poster_id'], $row['topic_last_poster_name'], $row['topic_last_poster_colour']),
				'U_TOPIC_AUTHOR'			=> get_username_string('profile', $row['topic_poster'], $row['topic_first_poster_name'], $row['topic_first_poster_colour']),
				'U_VIEW_TOPIC'				=> $view_topic_url,
				'U_VIEW_FORUM'				=> $view_forum_url,
				'U_MCP_REPORT'				=> append_sid("{$phpbb_root_path}mcp.$phpEx", 'i=reports&amp;mode=reports&amp;f=' . $forum_id . '&amp;t=' . $topic_id, true, $user->session_id),
				'U_MCP_QUEUE'				=> $u_mcp_queue,
				'S_TOPIC_TYPE_SWITCH'		=> ($s_type_switch == $s_type_switch_test) ? -1 : $s_type_switch_test,
			));
			$template->assign_vars(array('S_RECENT_POSTS'	=> true));
		}
		$db->sql_freeresult($result);
	}  //end recent topics

/**
* @private
*/
function setup_sidebar_mods()
{
	global $user, $config, $phpbb_root_path, $phpEx;
	global $db, $auth, $template;

	//Unapproved Notification MOD and Reported Post Notification MODs thanks to Stitch626 and CoC
	
	// Start Unapproved Notification Mod
    // Unapproved Posts
    $total_unapproved_posts = '';
        $sql = 'SELECT COUNT(post_approved) AS total_unapproved_posts
            FROM ' . POSTS_TABLE . "
            WHERE post_approved = 0";
                $result = $db->sql_query($sql);
                $total_unapproved_posts = (int) $db->sql_fetchfield('total_unapproved_posts');
                $db->sql_freeresult($result);

        if ($total_unapproved_posts >= 0)
        {
            $total_unapproved_posts = $total_unapproved_posts;
        }
    // Unapproved Posts

    // Unapproved Topics
    $total_unapproved = '';
        $sql = 'SELECT COUNT(topic_approved) AS total_unapproved
            FROM ' . TOPICS_TABLE . "
            WHERE topic_approved = 0";
                $result = $db->sql_query($sql);
                $total_unapproved = (int) $db->sql_fetchfield('total_unapproved');
                $db->sql_freeresult($result);

        if ($total_unapproved >= 0)
        {
            $total_unapproved = $total_unapproved;
        }
    // Unaproved Topics

    if ($total_unapproved)
    {
            $total_unapproved_posts = !$total_unapproved_posts;
    }
    // End Unapproved Notification Mod 
	//<--Reported Post Alert Mod------------------------------->
	$sql = 'SELECT topic_reported
		FROM ' . TOPICS_TABLE . "
		WHERE topic_reported = 1";
	$result = $db->sql_query($sql);
	$reported = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	//<--Reported Post Alert Mod-------------------------------->
	
	$template->assign_vars(array(
		'S_USERCOLOUR'					=> $user->data['user_colour'],
		'S_USERAVATAR'                  => ( $user->data['user_avatar'] ) ? get_my_user_avatar($user->data['user_avatar'], $user->data['user_avatar_type'], $user->data['user_avatar_width'], $user->data['user_avatar_height']) : '',
		'U_MCP_UNAPPROVED_TOPIC'        => append_sid("{$phpbb_root_path}mcp.$phpEx", 'i=queue&amp;mode=unapproved_topics', true, $user->session_id),
		'S_UNAPPROVED_TOPIC'        	=> (($total_unapproved) && $auth->acl_get('m_approve')) ? true : false,

		'U_MCP_UNAPPROVED_POSTS'        => append_sid("{$phpbb_root_path}mcp.$phpEx", 'i=queue&amp;mode=unapproved_posts', true, $user->session_id),
		'S_UNAPPROVED_POSTS'        	=> (($total_unapproved_posts) && $auth->acl_get('m_approve')) ? true : false, 
		'S_NEW_REPORT'      			=> (($reported['topic_reported']) && $auth->acl_get('m_report')) ? true : false,
		'U_GOTO_MCP_REPORT'         	=> append_sid("{$phpbb_root_path}mcp.$phpEx", 'i=reports&amp;mode=reports', true, $user->session_id),
	));
	
	
}

/**
* @private
*/
function setup_sidebar_ucp()
{
	global $user, $config, $phpbb_root_path, $phpEx;
	global $db, $auth, $template;
	
	$template->assign_vars(array(
		'S_SIDEBAR_SIDE'			=> $user->data['user_side'],
		'S_SHOW_SIDEBAR'			=> $user->data['user_show_side'],
	));
	
}

/*
*
*===================================================================
*
*      END Silverbar MOD Functions File.  Made by sTraTo
*===================================================================
*/
?>
