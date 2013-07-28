<?php
/** 
*
* warning_reasons [English]
*
* @package	language
* @version	Id$
* @copyright(c) 2011 RMcGirr83
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
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
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(

// ACP Entries
	'ACP_WR_VERSION'					=> 'Version %s',
	'ACP_WARNING_REASONS_EXPLAIN'		=> 'Here you can manage the warning reasons used when issuing a warning. There is one default reason (marked with a *) you are not able to remove, this reason is normally used for custom messages if no reason fits.',
	'SELECT_REASON'						=> 'Please select a reason',
	'WARNING_REASON_ADD'				=> 'Add warning reason',
	'WARNING_REASON_ADDED'				=> 'Warning reason successfully added.',
	'WARNING_REASON_ALREADY_EXIST'		=> 'A reason with this title already exists, please enter another title for this reason.',
	'WARNING_REASON_DESCRIPTION'		=> '<strong><em>Reason description:</em></strong>',
	'WARNING_REASON_DESC_TRANSLATED'	=> 'Displayed reason description',
	'WARNING_REASON_EDIT'				=> 'Edit warning reason',
	'WARNING_REASON_EDIT_EXPLAIN'		=> 'Here you are able to add or edit a reason. If the reason is translated the localised version is used instead of the description entered here.',
	'WARNING_REASON_REMOVED'			=> 'Warning reason successfully removed.',
	'WARNING_REASON_TITLE'				=> 'Reason title',
	'WARNING_REASON_TITLE_TRANSLATED'	=> 'Displayed reason title',
	'WARNING_REASON_UPDATED'			=> 'Warning reason successfully updated.',
	'WARNING_IS_TRANSLATED_EXPLAIN'		=> 'Reason has been localised. If the title you enter here is specified within the warning reasons language files (warning reasons translated section), the localised form of the title and description will be used.',
// db_update language
	'WR_TITLE'	=> 'Warning Reasons',
	'WARNING_TABLE_CREATED'	=> 'Warning reasons table was made',
	'WR_MODULE_ADDED'	=> 'Warning reasons module was added',
	'WR_INSTALL_DONE'	=> 'Database changes are done',
	
// warning stuff
	'WARNING_TEMPLATE'	=> '%1$s ' . "\n" . ' %2$s',

// warning reasons translated
	'warning_reasons'		=> array(
		'TITLE'	=> array(
			'OTHER'		=> 'Other',
		),
		'DESCRIPTION' => array(
			'OTHER'		=> 'The warning does not fit into any other category, please use the further information field.',
		)
	),	

));

?>