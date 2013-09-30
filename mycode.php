<?php
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();

header("Content-Type: text/plain");

if ($encrypted = rawurldecode($_POST['code'])) {
	$key = 'e6wa8CmuMTrwWvj3';
	echo rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
} else if ($user->data['user_id'] != ANONYMOUS) {
        $key = 'e6wa8CmuMTrwWvj3';
        $string = $user->data['username'];

        echo base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
}

?>
