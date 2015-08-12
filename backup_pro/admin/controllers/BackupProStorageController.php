<?php

use mithra62\BackupPro\Platforms\Controllers\Wordpress AS WpController;
use mithra62\BackupPro\Platforms\Controllers\Wordpress\Storage;
use mithra62\BackupPro\BackupPro AS BpInterface;

class BackupProStorageController extends WpController implements BpInterface
{   
    use Storage; 
    
}
