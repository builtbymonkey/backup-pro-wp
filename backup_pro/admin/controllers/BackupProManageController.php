<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Controllers/Wordpress/Manage.php
 */

use mithra62\BackupPro\Platforms\Controllers\Wordpress AS WpController;
use mithra62\BackupPro\Platforms\Controllers\Wordpress\Manage;
use mithra62\BackupPro\BackupPro AS BpInterface;

class BackupProManageController extends WpController implements BpInterface
{   
    use Manage;
    
}