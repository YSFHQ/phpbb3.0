<?php
/**
*
* @author Anybloodyid 
* @package umil
* @version $Id: user_flags_install.php
* @copyright (c) 2011 RMcGirr83
* @copyright (c) 2009 Anybloodyid
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
$language_file = 'mods/info_acp_flags';
	
// The name of the mod to be displayed during installation.
$mod_name = 'INSTALL_FLAG';
	
/*
* The name of the config variable which will hold the currently installed version
* You do not need to set this yourself, UMIL will handle setting and updating the version itself.
*/
$version_config_name = 'flag_version';

/*
* The array of versions and actions within each.
* You do not need to order it a specific way (it will be sorted automatically), however, you must enter every version, even if no actions are done for it.
*
* You must use correct version numbering.  Unless you know exactly what you can use, only use X.X.X (replacing X with an integer).
* The version numbering must otherwise be compatible with the version_compare function - http://php.net/manual/en/function.version-compare.php
*/
$versions = array(
	// Version 2.0.1
	'2.0.1'	=> array(
	// Add a table
		'table_add' => array(
			array('phpbb_flags', array(
				'COLUMNS'		=> array(
				'flag_id'		=> array('UINT', NULL, 'auto_increment'),
				'flag_name'		=> array('VCHAR', ''),
				'flag_image'	=> array('VCHAR', ''),
				),
				'PRIMARY_KEY'	=> 'flag_id',
				),
			),
		),
		
		'table_column_add' => array(
			array('phpbb_users', 'user_flag', array('UINT', 0)),
		),
	// lets add a config setting and set it to show flag images
		'config_add' => array(
			array('allow_flags', true),
			array('flag_type', 2),
		),
		
	// Now to add some permission settings
		'permission_add' => array(
			array('a_flags'),
		),
		
	// How about we give some default permissions then as well?
	// Admins can do anything with the flags mod
		'permission_set' => array(
			// Global Group permissions
			array('ADMINISTRATORS', 'a_flags', 'group'),
			// Global Role permissions for admins
			array('ROLE_ADMIN_FULL', 'a_flags'),
		),
			
	// and last but not least...a module		
		'module_add'	=> array(
		// First, lets add a new category named ACP_CAT_FLAGS into the ACP_CAT_MODS
			array('acp', 'ACP_CAT_DOT_MODS', 'ACP_CAT_FLAGS'),
		// next let's add our modules
			array('acp', 'ACP_CAT_FLAGS', array(
					'module_basename'	=> 'flags',
					'modes'				=> array('config', 'flags'),
					'module_auth'		=> 'a_flags',
				),
			),
		),
		'custom'	=> 'flag_install_images',		
	),
	'2.0.2'	=> array(
	//Nothing to do in this version
	),
	'2.0.3'	=> array(
	//Nothing to do in this version	
	),
	'2.0.4'	=> array(
		'permission_unset' => array(
			// Global Moderators were getting the a_flags set which then couldn't be 
			// unchecked so we remove the permission previously set
			array('ROLE_ADMIN_FULL', 'a_flags'),
		),
		'cache_purge' => array(''),		
	),	
);
//end versions
		
// Include the UMIF Auto file and everything else will be handled automatically.
include($phpbb_root_path . 'umil/umil_auto.' . $phpEx);
	
/*
* @param string $action The action (install|update|uninstall) will be sent through this.
* @param string $version The version this is being run for will be sent through this.
*/
function flag_install_images($action, $version)
{
	global $db, $table_prefix, $umil;

	switch ($action)
	{
		case 'install' :
			if ($umil->table_exists('phpbb_flags'))
			{
				$sql_ary = array(
					array(		
						'flag_name'		=> 'Afghanistan',
						'flag_image'	=> 'AF.gif',
					),
					array(		
						'flag_name'		=> 'Albania',
						'flag_image'	=> 'AL.gif',
					),
					array(		
						'flag_name'		=> 'Algeria',
						'flag_image'	=> 'DZ.gif',
					),
					array(		
						'flag_name'		=> 'American Samoa',
						'flag_image'	=> 'AS.gif',
					),
					array(	
						'flag_name'		=> 'Andorra',
						'flag_image'	=> 'AD.gif',
					),
					array(	
						'flag_name'		=> 'Angola',
						'flag_image'	=> 'AO.gif',
					),
					array(
						'flag_name'		=> 'Anguilla',
						'flag_image'	=> 'AI.gif',
					),
					array(	
						'flag_name'		=> 'Antigua &amp; Barbuda',
						'flag_image'	=> 'AG.gif',
					),       
					array(		
						'flag_name'		=> 'Argentina',
						'flag_image'	=> 'AR.gif',
					),
					array(	
						'flag_name'		=> 'Armenia',
						'flag_image'	=> 'AM.gif',
					),
					array(
						'flag_name'		=> 'Aruba',
						'flag_image'	=> 'AW.gif',
					),
					array(		
						'flag_name'		=> 'Australia',
						'flag_image'	=> 'AU.gif',
					),
					array(	
						'flag_name'		=> 'Austria',
						'flag_image'	=> 'AT.gif',
					),
					array(		
						'flag_name'		=> 'Azerbaijan',
						'flag_image'	=> 'AZ.gif',
					),
					array(		
						'flag_name'		=> 'Azores',
						'flag_image'	=> 'AP.gif',
					),
					array(	
						'flag_name'		=> 'Bahamas',
						'flag_image'	=> 'BS.gif',
					),
					array(	
						'flag_name'		=> 'Bahrain',
						'flag_image'	=> 'BH.gif',
					),
					array(	
						'flag_name'		=> 'Bangladesh',
						'flag_image'	=> 'BD.gif',
					),
					array(
						'flag_name'		=> 'Barbados',
						'flag_image'	=> 'BB.gif',
					),
					array(
						'flag_name'		=> 'Belarus',
						'flag_image'	=> 'BY.gif',
					),
					array(
						'flag_name'		=> 'Belgium',
						'flag_image'	=> 'BE.gif',
					),
					array(
						'flag_name'		=> 'Belize',
						'flag_image'	=> 'BZ.gif',
					),
					array(	
						'flag_name'		=> 'Benin',
						'flag_image'	=> 'BJ.gif',
					),
					array(		
						'flag_name'		=> 'Bermuda',
						'flag_image'	=> 'BM.gif',
					),
					array(	
					'flag_name'		=> 'Bhutan',
						'flag_image'	=> 'BT.gif',
					),
					array(
						'flag_name'		=> 'Bolivia',
						'flag_image'	=> 'BO.gif',
					),
					array(		
						'flag_name'		=> 'Bonaire',
						'flag_image'	=> 'BL.gif',
					),
					array(	
						'flag_name'		=> 'Bosnia &amp; Herzegovina',
						'flag_image'	=> 'BA.gif',
					),
					array(	
						'flag_name'		=> 'Botswana',
						'flag_image'	=> 'BW.gif',
					),
					array(	
						'flag_name'		=> 'Brazil',
						'flag_image'	=> 'BR.gif',
					),
					array(	
						'flag_name'		=> 'British Indian Ocean Ter',
						'flag_image'	=> 'IO.gif',
					),
					array(
						'flag_name'		=> 'Brunei',
						'flag_image'	=> 'BN.gif',
					),
					array(		
						'flag_name'		=> 'Bulgaria',
						'flag_image'	=> 'BG.gif',
					),
					array(		
						'flag_name'		=> 'Burkina Faso',
						'flag_image'	=> 'BF.gif',
					),
					array(	
						'flag_name'		=> 'Burundi',
						'flag_image'	=> 'BI.gif',
					),
					array(	
						'flag_name'		=> 'Cambodia',
						'flag_image'	=> 'KH.gif',
					),
					array(
						'flag_name'		=> 'Cameroon',
						'flag_image'	=> 'CM.gif',
					),
					array(		
						'flag_name'		=> 'Canada',
						'flag_image'	=> 'CA.gif',
					),
					array(	
						'flag_name'		=> 'Canary Islands',
						'flag_image'	=> 'IC.gif',
					),
					array(	
						'flag_name'		=> 'Cape Verde',
						'flag_image'	=> 'CV.gif',
					),
					array(	
						'flag_name'		=> 'Cayman Islands',
						'flag_image'	=> 'KY.gif',
					),
					array(		
						'flag_name'		=> 'Central African Republic',
						'flag_image'	=> 'CF.gif',
					),
					array(	
						'flag_name'		=> 'Chad',
						'flag_image'	=> 'TD.gif',
					),
					array(	
						'flag_name'		=> 'Channel Islands',
						'flag_image'	=> 'JI.gif',
					),
					array(	
						'flag_name'		=> 'Chile',
						'flag_image'	=> 'CL.gif',
					),
					array(	
						'flag_name'		=> 'China',
						'flag_image'	=> 'CN.gif',
					),
					array(	
						'flag_name'		=> 'Christmas Island',
						'flag_image'	=> 'CX.gif',
					),
					array(	
						'flag_name'		=> 'Cocos Island',
						'flag_image'	=> 'CC.gif',
					),
					array(	
						'flag_name'		=> 'Columbia',
						'flag_image'	=> 'CO.gif',
					),
					array(
						'flag_name'		=> 'Comoros',
						'flag_image'	=> 'KM.gif',
					),
					array(
						'flag_name'		=> 'Congo',
						'flag_image'	=> 'CG.gif',
					),
					array(		
						'flag_name'		=> 'Congo Democratic Republic',
						'flag_image'	=> 'CD.gif',
					),
					array(	
						'flag_name'		=> 'Cook Islands',
						'flag_image'	=> 'CK.gif',
					),
					array(	
						'flag_name'		=> 'Costa Rica',
						'flag_image'	=> 'CR.gif',
					),
					array(	
						'flag_name'		=> 'Cote D-Ivoire',
						'flag_image'	=> 'CI.gif',
					),
					array(	
						'flag_name'		=> 'Croatia',
						'flag_image'	=> 'HR.gif',
					),
					array(	
						'flag_name'		=> 'Cuba',
						'flag_image'	=> 'CU.gif',
					),
					array(
						'flag_name'		=> 'Curacao',
						'flag_image'	=> 'CB.gif',
					),
					array(	
						'flag_name'		=> 'Cyprus',
						'flag_image'	=> 'CY.gif',
					),
					array(	
						'flag_name'		=> 'Czech Republic',
						'flag_image'	=> 'CZ.gif',
					),
					array(	
						'flag_name'		=> 'Denmark',
						'flag_image'	=> 'DK.gif',
					),
					array(	
						'flag_name'		=> 'Djibouti',
						'flag_image'	=> 'DJ.gif',
					),
					array(	
						'flag_name'		=> 'Dominica',
						'flag_image'	=> 'DM.gif',
					),
					array(	
						'flag_name'		=> 'Dominican Republic',
						'flag_image'	=> 'DO.gif',
					),
					array(	
						'flag_name'		=> 'East Timor',
						'flag_image'	=> 'TL.gif',
					),
					array(	
						'flag_name'		=> 'Ecuador',
						'flag_image'	=> 'EC.gif',
					),
					array(	
						'flag_name'		=> 'Egypt',
						'flag_image'	=> 'EG.gif',
					),
					array(
						'flag_name'		=> 'El Salvador',
						'flag_image'	=> 'SV.gif',
					),
					array(	
						'flag_name'		=> 'Equatorial Guinea',
						'flag_image'	=> 'GQ.gif',
					),
					array(	
						'flag_name'		=> 'Eritrea',
						'flag_image'	=> 'ER.gif',
					),
					array(
						'flag_name'		=> 'Estonia',
						'flag_image'	=> 'EE.gif',
					),
					array(	
						'flag_name'		=> 'Ethiopia',
						'flag_image'	=> 'ET.gif',
					),
					array(	
						'flag_name'		=> 'Falkland Islands',
						'flag_image'	=> 'FK.gif',
					),
					array(	
						'flag_name'		=> 'Faroe Islands',
						'flag_image'	=> 'FO.gif',
					),
					array(		
						'flag_name'		=> 'Fiji',
						'flag_image'	=> 'FJ.gif',
					),
					array(	
						'flag_name'		=> 'Finland',
						'flag_image'	=> 'FI.gif',
					),
					array(	
						'flag_name'		=> 'France',
						'flag_image'	=> 'FR.gif',
					),
					array(
						'flag_name'		=> 'French Guiana',
						'flag_image'	=> 'GF.gif',
					),
					array(	
						'flag_name'		=> 'French Polynesia',
						'flag_image'	=> 'PF.gif',
					),
					array(	
						'flag_name'		=> 'French Southern Ter',
						'flag_image'	=> 'TF.gif',
					),
					array(	
						'flag_name'		=> 'Gabon',
						'flag_image'	=> 'GA.gif',
					),
					array(	
						'flag_name'		=> 'Gambia',
						'flag_image'	=> 'GM.gif',
					),
					array(	
						'flag_name'		=> 'Georgia',
						'flag_image'	=> 'GE.gif',
					),
					array(
						'flag_name'		=> 'Germany',
						'flag_image'	=> 'DE.gif',
					),
					array(	
						'flag_name'		=> 'Ghana',
						'flag_image'	=> 'GH.gif',
					),
					array(	
						'flag_name'		=> 'Gibraltar',
						'flag_image'	=> 'GI.gif',
					),
					array(	
						'flag_name'		=> 'Great Britain',
						'flag_image'	=> 'GB.gif',
					),
					array(	
						'flag_name'		=> 'Greece',
						'flag_image'	=> 'GR.gif',
					),
					array(	
						'flag_name'		=> 'Greenland',
						'flag_image'	=> 'GL.gif',
					),
					array(	
						'flag_name'		=> 'Grenada',
						'flag_image'	=> 'GD.gif',
					),
					array(	
						'flag_name'		=> 'Guadeloupe',
						'flag_image'	=> 'GP.gif',
					),
					array(	
						'flag_name'		=> 'Guam',
						'flag_image'	=> 'GU.gif',
					),
					array(	
						'flag_name'		=> 'Guatemala',
						'flag_image'	=> 'GT.gif',
					),
					array(	
						'flag_name'		=> 'Guinea',
						'flag_image'	=> 'GN.gif',
					),
					array(
						'flag_name'		=> 'Guinea Bissau',
						'flag_image'	=> 'GW.gif',
					),
					array(
						'flag_name'		=> 'Guyana',
						'flag_image'	=> 'GY.gif',
					),
					array(	
						'flag_name'		=> 'Haiti',
						'flag_image'	=> 'HT.gif',
					),
					array(	
						'flag_name'		=> 'Hawaii',
						'flag_image'	=> 'HW.gif',
					),
					array(	
						'flag_name'		=> 'Honduras',
						'flag_image'	=> 'HN.gif',
					),
					array(	
						'flag_name'		=> 'Hong Kong',
						'flag_image'	=> 'HK.gif',
					),
					array(		
						'flag_name'		=> 'Hungary',
						'flag_image'	=> 'HU.gif',
					),
					array(		
						'flag_name'		=> 'Iceland',
						'flag_image'	=> 'IS.gif',
					),
					array(		
						'flag_name'		=> 'India',
						'flag_image'	=> 'IN.gif',
					),
					array(		
						'flag_name'		=> 'Indonesia',
						'flag_image'	=> 'ID.gif',
					),
					array(	
						'flag_name'		=> 'Iran',
						'flag_image'	=> 'IR.gif',
					),
					array(	
						'flag_name'		=> 'Iraq',
						'flag_image'	=> 'IQ.gif',
					),
					array(	
						'flag_name'		=> 'Ireland',
						'flag_image'	=> 'IE.gif',
					),
					array(	
						'flag_name'		=> 'Isle of Man',
						'flag_image'	=> 'IM.gif',
					),
					array(
						'flag_name'		=> 'Israel',
						'flag_image'	=> 'IL.gif',
					),
					array(	
						'flag_name'		=> 'Italy',
						'flag_image'	=> 'IT.gif',
					),
					array(
						'flag_name'		=> 'Jamaica',
						'flag_image'	=> 'JM.gif',
					),
					array(	
						'flag_name'		=> 'Japan',
						'flag_image'	=> 'JP.gif',
					),
					array(	
						'flag_name'		=> 'Jordan',
						'flag_image'	=> 'JO.gif',
					),
					array(	
						'flag_name'		=> 'Kazakhstan',
						'flag_image'	=> 'KZ.gif',
					),
					array(	
						'flag_name'		=> 'Kenya',
						'flag_image'	=> 'KE.gif',
					),
					array(	
						'flag_name'		=> 'Kiribati',
						'flag_image'	=> 'KI.gif',
					),
					array(	
						'flag_name'		=> 'Korea North',
						'flag_image'	=> 'NK.gif',
					),
					array(	
						'flag_name'		=> 'Korea South',
						'flag_image'	=> 'KS.gif',
					),
					array(	
						'flag_name'		=> 'Kuwait',
						'flag_image'	=> 'KW.gif',
					),
					array(	
						'flag_name'		=> 'Kyrgyzstan',
						'flag_image'	=> 'KG.gif',
					),
					array(	
						'flag_name'		=> 'Laos',
						'flag_image'	=> 'LA.gif',
					),
					array(	
						'flag_name'		=> 'Latvia',
						'flag_image'	=> 'LV.gif',
					),
					array(	
						'flag_name'		=> 'Lebanon',
						'flag_image'	=> 'LB.gif',
					),
					array(	
						'flag_name'		=> 'Lesotho',
						'flag_image'	=> 'LS.gif',
					),
					array(	
						'flag_name'		=> 'Liberia',
						'flag_image'	=> 'LR.gif',
					),
					array(	
						'flag_name'		=> 'Libya',
						'flag_image'	=> 'LY.gif',
					),
					array(	
						'flag_name'		=> 'Liechtenstein',
						'flag_image'	=> 'LI.gif',
					),
					array(	
						'flag_name'		=> 'Lithuania',
						'flag_image'	=> 'LT.gif',
					),
					array(	
						'flag_name'		=> 'Luxembourg',
						'flag_image'	=> 'LU.gif',
					),
					array(	
						'flag_name'		=> 'Macau',
						'flag_image'	=> 'MO.gif',
					),
					array(	
						'flag_name'		=> 'Macedonia',
						'flag_image'	=> 'MK.gif',
					),
					array(	
						'flag_name'		=> 'Madagascar',
						'flag_image'	=> 'MG.gif',
					),
					array(	
						'flag_name'		=> 'Malawi',
						'flag_image'	=> 'MW.gif',
					),
					array(	
						'flag_name'		=> 'Malaysia',
						'flag_image'	=> 'MY.gif',
					),
					array(	
						'flag_name'		=> 'Maldives',
						'flag_image'	=> 'MV.gif',
					),
					array(	
						'flag_name'		=> 'Mali',
						'flag_image'	=> 'ML.gif',
					),
					array(	
						'flag_name'		=> 'Malta',
						'flag_image'	=> 'MT.gif',
					),
					array(	
						'flag_name'		=> 'Marshall Islands',
						'flag_image'	=> 'MH.gif',
					),
					array(	
						'flag_name'		=> 'Martinique',
						'flag_image'	=> 'MQ.gif',
					),
					array(	
						'flag_name'		=> 'Mauritania',
						'flag_image'	=> 'MR.gif',
					),
					array(	
						'flag_name'		=> 'Mauritius',
						'flag_image'	=> 'MU.gif',
					),
					array(	
						'flag_name'		=> 'Mayotte',
						'flag_image'	=> 'YT.gif',
					),
					array(	
						'flag_name'		=> 'Mexico',
						'flag_image'	=> 'MX.gif',
					),
					array(	
						'flag_name'		=> 'Micronesia',
						'flag_image'	=> 'FM.gif',
					),
					array(
						'flag_name'		=> 'Midway Islands',
						'flag_image'	=> 'MI.gif',
					),
					array(		
						'flag_name'		=> 'Moldova',
						'flag_image'	=> 'MD.gif',
					),
					array(	
						'flag_name'		=> 'Monaco',
						'flag_image'	=> 'MC.gif',
					),
					array(	
						'flag_name'		=> 'Mongolia',
						'flag_image'	=> 'MN.gif',
					),
					array(	
						'flag_name'		=> 'Montserrat',
						'flag_image'	=> 'MS.gif',
					),
					array(	
						'flag_name'		=> 'Morocco',
						'flag_image'	=> 'MA.gif',
					),
					array(	
						'flag_name'		=> 'Mozambique',
						'flag_image'	=> 'MZ.gif',
					),
					array(	
						'flag_name'		=> 'Myanmar',
						'flag_image'	=> 'MM.gif',
					),
					array(	
						'flag_name'		=> 'Nambia',
						'flag_image'	=> 'NA.gif',
					),
					array(	
						'flag_name'		=> 'Nauru',
						'flag_image'	=> 'NR.gif',
					),
					array(	
						'flag_name'		=> 'Nepal',
						'flag_image'	=> 'NP.gif',
					),
					array(	
						'flag_name'		=> 'Netherland Antilles',
						'flag_image'	=> 'AN.gif',
					),
					array(	
						'flag_name'		=> 'Netherlands',
						'flag_image'	=> 'NL.gif',
					),
					array(	
						'flag_name'		=> 'New Caledonia',
						'flag_image'	=> 'NC.gif',
					),
					array(	
						'flag_name'		=> 'New Zealand',
						'flag_image'	=> 'NZ.gif',
					),
					array(	
						'flag_name'		=> 'Nicaragua',
						'flag_image'	=> 'NI.gif',
					),
					array(	
						'flag_name'		=> 'Niger',
						'flag_image'	=> 'NE.gif',
					),
					array(
						'flag_name'		=> 'Nigeria',
						'flag_image'	=> 'NG.gif',
					),
					array(	
						'flag_name'		=> 'Niue',
						'flag_image'	=> 'NU.gif',
					),
					array(	
						'flag_name'		=> 'Norfolk Island',
						'flag_image'	=> 'NF.gif',
					),
					array(	
						'flag_name'		=> 'Norway',
						'flag_image'	=> 'NO.gif',
					),
					array(		
						'flag_name'		=> 'Oman',
						'flag_image'	=> 'OM.gif',
					),
					array(		
						'flag_name'		=> 'Pakistan',
						'flag_image'	=> 'PK.gif',
					),
					array(		
						'flag_name'		=> 'Palau Island',
						'flag_image'	=> 'PW.gif',
					),
					array(	
						'flag_name'		=> 'Palestine',
						'flag_image'	=> 'PS.gif',
					),
					array(	
						'flag_name'		=> 'Panama',
						'flag_image'	=> 'PA.gif',
					),
					array(	
						'flag_name'		=> 'Papua New Guinea',
						'flag_image'	=> 'PG.gif',
					),
					array(		
						'flag_name'		=> 'Paraguay',
						'flag_image'	=> 'PY.gif',
					),
					array(	
						'flag_name'		=> 'Peru',
						'flag_image'	=> 'PE.gif',
					),
					array(	
						'flag_name'		=> 'Philippines',
						'flag_image'	=> 'PH.gif',
					),
					array(	
						'flag_name'		=> 'Pitcairn Island',
						'flag_image'	=> 'PN.gif',
					),
					array(	
						'flag_name'		=> 'Poland',
						'flag_image'	=> 'PL.gif',
					),
					array(	
						'flag_name'		=> 'Portugal',
						'flag_image'	=> 'PT.gif',
					),
					array(	
						'flag_name'		=> 'Puerto Rico',
						'flag_image'	=> 'PR.gif',
					),
					array(	
						'flag_name'		=> 'Qatar',
						'flag_image'	=> 'QA.gif',
					),
					array(	
						'flag_name'		=> 'Reunion',
						'flag_image'	=> 'RE.gif',
					),
					array(	
						'flag_name'		=> 'Romania',
						'flag_image'	=> 'RO.gif',
					),
					array(	
						'flag_name'		=> 'Russia',
						'flag_image'	=> 'RU.gif',
					),
					array(	
						'flag_name'		=> 'Rwanda',
						'flag_image'	=> 'RW.gif',
					),
					array(	
						'flag_name'		=> 'Samoa',
						'flag_image'	=> 'WS.gif',
					),
					array(	
						'flag_name'		=> 'San Marino',
						'flag_image'	=> 'SM.gif',
					),
					array(	
						'flag_name'		=> 'Sao Tome &amp; Principe',
						'flag_image'	=> 'ST.gif',
					),
					array(	
						'flag_name'		=> 'Saudi Arabia',
						'flag_image'	=> 'SA.gif',
					),
					array(	
						'flag_name'		=> 'Senegal',
						'flag_image'	=> 'SN.gif',
					),
					array(	
						'flag_name'		=> 'Serbia &amp; Montenegro',
						'flag_image'	=> 'CS.gif',
					),
					array(	
						'flag_name'		=> 'Seychelles',
						'flag_image'	=> 'SC.gif',
					),
					array(	
						'flag_name'		=> 'Sierra Leone',
						'flag_image'	=> 'SL.gif',
					),
					array(	
						'flag_name'		=> 'Singapore',
						'flag_image'	=> 'SG.gif',
					),
					array(	
						'flag_name'		=> 'Slovakia',
						'flag_image'	=> 'SK.gif',
					),
					array(	
						'flag_name'		=> 'Slovenia',
						'flag_image'	=> 'SI.gif',
					),
					array(	
						'flag_name'		=> 'Solomon Islands',
						'flag_image'	=> 'SB.gif',
					),
					array(		
						'flag_name'		=> 'Somalia',
						'flag_image'	=> 'SO.gif',
					),
					array(	
						'flag_name'		=> 'South Africa',
						'flag_image'	=> 'ZA.gif',
					),
					array(		
						'flag_name'		=> 'Spain',
						'flag_image'	=> 'ES.gif',
					),
					array(		
						'flag_name'		=> 'Sri Lanka',
						'flag_image'	=> 'LK.gif',
					),
					array(		
						'flag_name'		=> 'St Helena',
						'flag_image'	=> 'SH.gif',
					),
					array(		
						'flag_name'		=> 'St Kitts-Nevis',
						'flag_image'	=> 'KN.gif',
					),
					array(		
						'flag_name'		=> 'St Lucia',
						'flag_image'	=> 'LC.gif',
					),
					array(		
						'flag_name'		=> 'St Maarten',
						'flag_image'	=> 'MB.gif',
					),
					array(		
						'flag_name'		=> 'St Pierre &amp; Miquelon',
						'flag_image'	=> 'PM.gif',
					),
					array(		
						'flag_name'		=> 'St Vincent &amp; Grenadines',
						'flag_image'	=> 'VC.gif',
					),
					array(		
						'flag_name'		=> 'Sudan',
						'flag_image'	=> 'SD.gif',
					),
					array(		
						'flag_name'		=> 'Suriname',
						'flag_image'	=> 'SR.gif',
					),
					array(		
						'flag_name'		=> 'Svalbard',
						'flag_image'	=> 'SJ.gif',
					),
					array(		
						'flag_name'		=> 'Swaziland',
						'flag_image'	=> 'SZ.gif',
					),
					array(		
						'flag_name'		=> 'Sweden',
						'flag_image'	=> 'SE.gif',
					),
						array(		
						'flag_name'		=> 'Switzerland',
						'flag_image'	=> 'CH.gif',
					),
					array(		
						'flag_name'		=> 'Syria',
						'flag_image'	=> 'SY.gif',
					),
					array(		
						'flag_name'		=> 'Tahiti',
						'flag_image'	=> 'TA.gif',
					),
					array(		
						'flag_name'		=> 'Taiwan',
						'flag_image'	=> 'TW.gif',
					),
					array(		
						'flag_name'		=> 'Tajikistan',
						'flag_image'	=> 'TJ.gif',
					),
					array(		
						'flag_name'		=> 'Tanzania',
						'flag_image'	=> 'TZ.gif',
					),
					array(		
						'flag_name'		=> 'Thailand',
						'flag_image'	=> 'TH.gif',
					),
					array(		
						'flag_name'		=> 'Togo',
						'flag_image'	=> 'TG.gif',
					),
					array(		
						'flag_name'		=> 'Tokelau',
						'flag_image'	=> 'TK.gif',
					),
					array(		
						'flag_name'		=> 'Tonga',
						'flag_image'	=> 'TO.gif',
					),
					array(		
						'flag_name'		=> 'Trinidad &amp; Tobago',
						'flag_image'	=> 'TT.gif',
					),
					array(		
						'flag_name'		=> 'Tunisia',
						'flag_image'	=> 'TN.gif',
					),
					array(		
						'flag_name'		=> 'Turkey',
						'flag_image'	=> 'TR.gif',
					),
					array(		
						'flag_name'		=> 'Turkmenistan',
						'flag_image'	=> 'TM.gif',
					),
					array(		
						'flag_name'		=> 'Turks &amp; Caicos Is',
						'flag_image'	=> 'TC.gif',
					),
					array(		
						'flag_name'		=> 'Tuvalu',
						'flag_image'	=> 'TV.gif',
					),
					array(		
						'flag_name'		=> 'Uganda',
						'flag_image'	=> 'UG.gif',
					),
					array(		
						'flag_name'		=> 'Ukraine',
						'flag_image'	=> 'UA.gif',
					),
					array(		
						'flag_name'		=> 'United Arab Emirates',
						'flag_image'	=> 'AE.gif',
					),
					array(		
						'flag_name'		=> 'United States of America',
						'flag_image'	=> 'US.gif',
					),
					array(		
						'flag_name'		=> 'Uruguay',
						'flag_image'	=> 'UY.gif',
					),
					array(		
						'flag_name'		=> 'Uzbekistan',
						'flag_image'	=> 'UZ.gif',
					),
					array(		
						'flag_name'		=> 'Vanuatu',
						'flag_image'	=> 'VU.gif',
					),
					array(		
						'flag_name'		=> 'Vatican City State',
						'flag_image'	=> 'VS.gif',
					),
					array(		
						'flag_name'		=> 'Venezuela',
						'flag_image'	=> 'VE.gif',
					),
					array(		
						'flag_name'		=> 'Vietnam',
						'flag_image'	=> 'VN.gif',
					),
					array(		
						'flag_name'		=> 'Virgin Islands (Brit)',
						'flag_image'	=> 'VG.gif',
					),
					array(		
						'flag_name'		=> 'Virgin Islands (USA)',
						'flag_image'	=> 'VI.gif',
					),
					array(		
						'flag_name'		=> 'Wake Island',
						'flag_image'	=> 'WK.gif',
					),
					array(		
						'flag_name'		=> 'Wallis &amp; Futana Is',
						'flag_image'	=> 'WF.gif',
					),
					array(		
						'flag_name'		=> 'Western Sahara',
						'flag_image'	=> 'EH.gif',
					),
					array(		
						'flag_name'		=> 'Yemen',
						'flag_image'	=> 'YE.gif',
					),
					array(		
						'flag_name'		=> 'Zambia',
						'flag_image'	=> 'ZM.gif',
					),
					array(		
						'flag_name'		=> 'Zimbabwe',
						'flag_image'	=> 'ZW.gif',
					),
															
				);
				$db->sql_multi_insert(FLAGS_DATA_TABLE, $sql_ary);
		
				return 'INSERT_IMAGES';
			}
		break;
	}			
}

function update_user_table($action, $version)
{
	global $db, $table_prefix, $umil;
	switch ($action)
	{
		case 'update' :
			$umil->table_column_update('phpbb_users', 'user_flag', array('UINT', 0));
		break;
	}
}

?>