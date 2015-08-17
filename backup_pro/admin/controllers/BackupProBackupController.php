<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./backup_pro/admin/controllers/BackupProBackupController.php
 */

use mithra62\BackupPro\Platforms\Controllers\Wordpress AS WpController;
use mithra62\Traits\Log;

/**
 * Backup Pro - Wordpress Backup Controller
 *
 * Contains the Backup Controller Actions for Wordpress
 *
 * @package 	BackupPro\Wordpress\Controllers
 * @author		Eric Lamb <eric@mithra62.com>
 */
class BackupProBackupController extends WpController
{   
    use Log;
    
    /**
     * Manually execute a database backup
     */
    public function backup_database()
    {
        @session_write_close();
        $error = $this->services['errors'];
        $backup = $this->services['backup']->setStoragePath($this->settings['working_directory']);
        $error->clearErrors()->checkStorageLocations($this->settings['storage_details'])
              ->checkBackupDirs($backup->getStorage());
        if( $error->totalErrors() == '0' )
        {
            ini_set('memory_limit', -1);
            set_time_limit(0);
    
            $db_info = $this->platform->getDbCredentials();
            if( $backup->setDbInfo($db_info)->database($db_info['database'], $this->settings, $this->services['shell']) )
            {
                $backups = $this->services['backups']->setBackupPath($this->settings['working_directory'])
                                                     ->getAllBackups($this->settings['storage_details']);
    
                $backup->getStorage()->getCleanup()->setStorageDetails($this->settings['storage_details'])
                                     ->setBackups($backups)
                                     ->setDetails($this->services['backups']->getDetails())
                                     ->autoThreshold($this->settings['auto_threshold'])
                                     ->counts($this->settings['max_db_backups'])
                                     ->duplicates($this->settings['allow_duplicates']);
                return true;
            }
        }
    }
    
    /**
     * Manually execute a file backup
     */
    public function backup_files()
    {
        @session_write_close();
        $error = $this->services['errors'];
        $backup = $this->services['backup']->setStoragePath($this->settings['working_directory']);
        $error->clearErrors()->checkStorageLocations($this->settings['storage_details'])
              ->checkBackupDirs($backup->getStorage())
              ->checkFileBackupLocations($this->settings['backup_file_location']);
        if( $error->totalErrors() == 0 )
        {
            ini_set('memory_limit', -1);
            set_time_limit(0);
            if( $backup->files($this->settings, $this->services['files'], $this->services['regex']) )
            {
                $backups = $this->services['backups']->setBackupPath($this->settings['working_directory'])
                                ->getAllBackups($this->settings['storage_details']);
    
                $backup->getStorage()->getCleanup()->setStorageDetails($this->settings['storage_details'])
                                     ->setBackups($backups)
                                     ->setDetails($this->services['backups']->getDetails())
                                     ->autoThreshold($this->settings['auto_threshold'])
                                     ->counts($this->settings['max_file_backups'], 'files')
                                     ->duplicates($this->settings['allow_duplicates']);
                return true;
            }
        }
        else
        {
            $url = $this->url_base.'file_backups';
            if( $error->getError() == 'no_backup_file_location' )
            {
                $url = $this->url_base.'settings&section=files';
            }
            
            ee()->session->set_flashdata('message_error', $this->services['lang']->__($error->getError()));
            ee()->functions->redirect($url);
        }
    }    
    
    public function backup($type = 'database')
    {
        //$type = ee()->input->get_post('type', TRUE);
        //ee()->view->cp_page_title = $this->services['lang']->__('backup_'.$type);
        $proc_url = FALSE;
        switch($type)
        {
            case 'database':
                $proc_url = $this->url_base.'backup_database';
                $template = 'admin/views/backup_db';
                $errors = $this->services['errors']->clearErrors()->checkWorkingDirectory($this->settings['working_directory'])
                                                 ->checkStorageLocations($this->settings['storage_details'])
                                                 ->getErrors();
            break;
            
            case 'files':
                $proc_url = $this->url_base.'backup_files';
                $template = 'admin/views/backup_files';
                $errors = $this->services['errors']->clearErrors()->checkWorkingDirectory($this->settings['working_directory'])
                                                 ->checkStorageLocations($this->settings['storage_details'])
                                                 ->checkFileBackupLocations($this->settings['backup_file_location'])
                                                 ->getErrors();
            break;
        }
    
        if(!$proc_url)
        {
            wp_redirect($this->url_base.'settings&section=storage&backup_fail=yes');
            exit;
        }
    
        //ee()->cp->add_js_script('ui', 'progressbar');
        //ee()->javascript->output('$("#progressbar").progressbar({ value: 0 });');
        //ee()->javascript->compile();
    
        $variables = array('proc_url' => $proc_url);
        $variables['errors'] = $errors;
        $variables['proc_url'] = $proc_url;
        $variables['url_base'] = $this->url_base;
        $variables['backup_type'] = $type;
        //$vars['menu_data'] = ee()->backup_pro->get_dashboard_view_menu();
        $variables['method'] = '';
        $variables['view_helper'] = $this->view_helper;
        $variables['url_base'] = $this->url_base;
        $this->renderTemplate($template, $variables);
    }
    
}