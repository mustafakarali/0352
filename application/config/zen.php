<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| Zen configuration
| -------------------------------------------------------------------
| This file will contain the settings for the Zen library.
|
| 'adapter'   = the cache adapter you want the system to use (apc, file, memcached, dummy)
| 'expires'   = the default number of seconds an item should remain in cache
| 'folder'	  = when using the file adapter you can choose to store the items in a sub 
|               directory of CodeIgniter's cache folder
| 'extension' = the extension to add to all cache files
*/

$config['adapter']   = 'file';
$config['expires']   = 5*60;

// only for file cache adapter
$config['folder']    = 'zen';
$config['extension'] = '.zen';