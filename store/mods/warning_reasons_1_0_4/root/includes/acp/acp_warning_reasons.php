<?php
/**
*
* @package warning_reasons
* @version $Id: acp_warning_reasons.php
* @copyright (c) 2011 RMcGirr83
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* most of the code derived from acp_reasons.php...thanks naderman
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* @package acp
*/
class acp_warning_reasons
{
	var $u_action;

	function main($id, $mode)
	{
		global $db, $user, $auth, $template, $cache;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		$user->add_lang(array('acp/posting', 'mods/warning_reasons'));

		// Set up general vars
		$action = request_var('action', '');
		$submit = (isset($_POST['submit'])) ? true : false;
		$reason_id = request_var('id', 0);

		$this->tpl_name = 'acp_warning_reasons';
		$this->page_title = 'ACP_WARNING_REASONS';

		add_form_key('acp_warning_reasons');

		$error = array();

		switch ($action)
		{
			case 'add':
			case 'edit':

				$reason_row = array(
					'reason_title'			=> utf8_normalize_nfc(request_var('reason_title', '', true)),
					'reason_description'	=> utf8_normalize_nfc(request_var('reason_description', '', true)),
				);

				if ($submit)
				{
					if (!check_form_key('acp_warning_reasons'))
					{
						$error[] = $user->lang['FORM_INVALID'];
					}
					// Reason specified?
					if (!$reason_row['reason_title'] || !$reason_row['reason_description'])
					{
						$error[] = $user->lang['NO_REASON_INFO'];
					}

					$check_double = ($action == 'add') ? true : false;

					if ($action == 'edit')
					{
						$result = $db->sql_query('SELECT reason_title FROM ' . WARNING_REASONS_TABLE . " WHERE reason_id = $reason_id");
						$row = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);

						if (strtolower($row['reason_title']) == 'other' || strtolower($reason_row['reason_title']) == 'other')
						{
							$reason_row['reason_title'] = 'Other';
						}

						if ($row['reason_title'] != $reason_row['reason_title'])
						{
							$check_double = true;
						}
					}

					// Check for same reason if adding it...
					if ($check_double)
					{
						$result = $db->sql_query('SELECT reason_id FROM ' . WARNING_REASONS_TABLE . " WHERE reason_title = '" . $db->sql_escape($reason_row['reason_title']) . "'");
						$row = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);

						if ($row || ($action == 'add' && strtolower($reason_row['reason_title']) == 'other'))
						{
							$error[] = $user->lang['REASON_ALREADY_EXIST'];
						}
					}

					if (!sizeof($error))
					{
						// New reason?
						if ($action == 'add')
						{
							// Get new order...
							$result = $db->sql_query('SELECT MAX(reason_order) as max_reason_order FROM ' . WARNING_REASONS_TABLE);
							$max_order = (int) $db->sql_fetchfield('max_reason_order');
							$db->sql_freeresult($result);
							
							$sql_ary = array(
								'reason_title'			=> (string) $reason_row['reason_title'],
								'reason_description'	=> (string) $reason_row['reason_description'],
								'reason_order'			=> $max_order + 1
							);

							$db->sql_query('INSERT INTO ' . WARNING_REASONS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary));

							$log = 'ADDED';
						}
						else if ($reason_id)
						{
							$sql_ary = array(
								'reason_title'			=> (string) $reason_row['reason_title'],
								'reason_description'	=> (string) $reason_row['reason_description'],
							);

							$db->sql_query('UPDATE ' . WARNING_REASONS_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $sql_ary) . ' WHERE reason_id = ' . $reason_id);

							$log = 'UPDATED';
						}

						add_log('admin', 'LOG_WARNING_REASON_' . $log, $reason_row['reason_title']);
						trigger_error($user->lang['WARNING_REASON_' . $log] . adm_back_link($this->u_action));
					}
				}
				else if ($reason_id)
				{
					$result = $db->sql_query('SELECT * FROM ' . WARNING_REASONS_TABLE . ' WHERE reason_id = ' . $reason_id);
					$reason_row = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);

					if (!$reason_row)
					{
						trigger_error($user->lang['NO_REASON'] . adm_back_link($this->u_action), E_USER_WARNING);
					}
				}

				$l_title = ($action == 'edit') ? 'EDIT' : 'ADD';
				
				$translated = false;
				
				// If the reason is defined within the language file, we will use the localized version, else just use the database entry...
				if (isset($user->lang['warning_reasons']['TITLE'][strtoupper(str_replace(' ','_',$reason_row['reason_title']))]) && isset($user->lang['warning_reasons']['DESCRIPTION'][strtoupper(str_replace(' ','_',$reason_row['reason_title']))]))
				{
					$translated = true;
				}
				
				$template->assign_vars(array(
					'L_TITLE'		=> $user->lang['WARNING_REASON_' . $l_title],
					'U_ACTION'		=> $this->u_action . "&amp;id=$reason_id&amp;action=$action",
					'U_BACK'		=> $this->u_action,
					'ERROR_MSG'		=> (sizeof($error)) ? implode('<br />', $error) : '',
					
					'REASON_TITLE'			=> $reason_row['reason_title'],
					'REASON_DESCRIPTION'	=> $reason_row['reason_description'],
					'TRANSLATED_TITLE'		=> ($translated) ? $user->lang['warning_reasons']['TITLE'][strtoupper(str_replace(' ','_',$reason_row['reason_title']))] : '',
					'TRANSLATED_DESCRIPTION'=> ($translated) ? $user->lang['warning_reasons']['DESCRIPTION'][strtoupper(str_replace(' ','_',$reason_row['reason_title']))] : '',
					
					'S_TRANSLATED'			=> $translated,
					'S_EDIT_REASON'			=> true,
					'S_ERROR'				=> (sizeof($error)) ? true : false,
					)
				);

				return;
			break;

			case 'delete':

				$result = $db->sql_query('SELECT * FROM ' . WARNING_REASONS_TABLE . ' WHERE reason_id = ' . $reason_id);
				$reason_row = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);

				if (!$reason_row)
				{
					trigger_error($user->lang['NO_REASON'] . adm_back_link($this->u_action), E_USER_WARNING);
				}

				if (strtolower($reason_row['reason_title']) == 'other')
				{
					trigger_error($user->lang['NO_REMOVE_DEFAULT_REASON'] . adm_back_link($this->u_action), E_USER_WARNING);
				}

				// Let the deletion be confirmed...
				if (confirm_box(true))
				{
					$db->sql_query('DELETE FROM ' . WARNING_REASONS_TABLE . ' WHERE reason_id = ' . $reason_id);

					add_log('admin', 'LOG_WARNING_REASON_REMOVED', $reason_row['reason_title']);
					trigger_error($user->lang['REASON_REMOVED'] . adm_back_link($this->u_action));
				}
				else
				{
					confirm_box(false, $user->lang['CONFIRM_OPERATION'], build_hidden_fields(array(
						'i'			=> $id,
						'mode'		=> $mode,
						'action'	=> $action,
						'id'		=> $reason_id))
					);
				}

			break;

			case 'move_up':
			case 'move_down':

				$order = request_var('order', 0);
				$order_total = $order * 2 + (($action == 'move_up') ? -1 : 1);

				$db->sql_query('UPDATE ' . WARNING_REASONS_TABLE . ' SET reason_order = ' . $order_total . ' - reason_order	WHERE reason_order IN (' . $order . ', ' . (($action == 'move_up') ? $order - 1 : $order + 1) . ')');

			break;
		}

		// By default, check that order is valid and fix it if necessary
		$result = $db->sql_query('SELECT reason_id, reason_order FROM ' . WARNING_REASONS_TABLE . ' ORDER BY reason_order');

		if ($row = $db->sql_fetchrow($result))
		{
			$order = 0;
			do
			{
				++$order;
				
				if ($row['reason_order'] != $order)
				{
					$db->sql_query('UPDATE ' . WARNING_REASONS_TABLE . " SET reason_order = $order WHERE reason_id = {$row['reason_id']}");
				}
			}
			while ($row = $db->sql_fetchrow($result));
		}
		$db->sql_freeresult($result);
		$wr_version = isset($config['wr_version']) ? $config['wr_version'] : '';
		$template->assign_vars(array(
			'U_ACTION'			=> $this->u_action,
			'WR_VERSION'		=> sprintf($user->lang['ACP_WR_VERSION'], $wr_version),
		));
		
		$result = $db->sql_query('SELECT * FROM ' . WARNING_REASONS_TABLE . ' ORDER BY reason_order ASC');

		while ($row = $db->sql_fetchrow($result))
		{
			$other_reason = ($row['reason_title'] == 'Other') ? true : false;
			$translated = false;
			// If the reason is defined within the language file, we will use the localized version, else just use the database entry...
			if (isset($user->lang['warning_reasons']['TITLE'][strtoupper(str_replace(' ','_',$row['reason_title']))]) && isset($user->lang['warning_reasons']['DESCRIPTION'][strtoupper(str_replace(' ','_',$row['reason_title']))]))
			{

				$translated = true;
			}			
			$template->assign_block_vars('reasons', array(
				'REASON_TITLE'			=> $row['reason_title'],
				'REASON_DESCRIPTION'	=> $row['reason_description'],

				'S_OTHER_REASON'	=> $other_reason,
				'S_TRANSLATED'		=> $translated,
				
				'U_EDIT'		=> $this->u_action . '&amp;action=edit&amp;id=' . $row['reason_id'],
				'U_DELETE'		=> (!$other_reason) ? $this->u_action . '&amp;action=delete&amp;id=' . $row['reason_id'] : '',
				'U_MOVE_UP'		=> $this->u_action . '&amp;action=move_up&amp;order=' . $row['reason_order'],
				'U_MOVE_DOWN'	=> $this->u_action . '&amp;action=move_down&amp;order=' . $row['reason_order'])
			);
		}
		$db->sql_freeresult($result);
	}
}

?>