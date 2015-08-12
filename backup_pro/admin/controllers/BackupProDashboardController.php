<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./backup_pro/admin/controllers/BackupProDashboardController.php
 */
 
use mithra62\BackupPro\Platforms\Controllers\Wordpress AS WpController;
use mithra62\BackupPro\Platforms\Controllers\Wordpress\Dashboard;
use mithra62\BackupPro\BackupPro AS BpInterface;

class BackupProDashboardController extends WpController implements BpInterface
{   
    use Dashboard;
    
}