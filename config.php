<?php
// phpBB 3.0.x auto-generated configuration file
// Do not change anything in this file!
$dbms = 'mysqli'; //database type it may not be mysql
$dbhost = 'ysfhq-db.cg1v8w3lirpf.us-east-1.rds.amazonaws.com';
$dbport = '3306';
$dbname = 'production';
$dbuser = 'production';
$dbpasswd = 'e6wa8CmuMTrwWvj3';
$table_prefix = 'phpbb_';

$acm_type = 'apc';
@define('PHPBB_ACM_MEMCACHE_HOST', 'ysfhq-memcache.dmstxr.cfg.use1.cache.amazonaws.com'); // Memcache server hostname
@define('PHPBB_ACM_MEMCACHE_PORT', 11211); // Memcache server port
@define('PHPBB_ACM_MEMCACHE_COMPRESS', false); // Compress stored data
$load_extensions = 'memcache';

@define('PHPBB_INSTALLED', true);
//@define('DEBUG', true);
//@define('DEBUG_EXTRA', true);
@define('PHPBB_ROOT_PATH', '/var/www/forum.ysfhq.com/');
?>
