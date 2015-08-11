<?php

use mithra62\BackupPro\Platforms\Controllers\Wordpress AS WpController;
use mithra62\BackupPro\Platforms\Controllers\Wordpress\Backup;

class BackupProBackupController extends WpController
{   
    use Backup;
    
}