<?php

use mithra62\BackupPro\Platforms\Controllers\Wordpress AS WpController;
use mithra62\BackupPro\Platforms\Controllers\Wordpress\Dashboard;
use mithra62\BackupPro\BackupPro AS BpInterface;

class BackupProDashboardController extends WpController implements BpInterface
{   
    use Dashboard;
    
}