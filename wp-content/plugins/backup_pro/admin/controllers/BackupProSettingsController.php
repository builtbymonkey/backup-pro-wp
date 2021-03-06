<?php

use mithra62\BackupPro\Platforms\Controllers\Wordpress AS WpController;
use mithra62\BackupPro\BackupPro AS BpInterface;

class BackupProSettingsController extends WpController implements BpInterface
{   
    /**
     * The Settings Control Panel page
     */
    public function settings()
    {
        $section = $this->getPost('section', 'general');
        $variables = array('form_data' => $this->settings, 'form_errors' => $this->returnEmpty($this->settings));
        $variables['form_data']['cron_notify_emails'] = implode("\n", $this->settings['cron_notify_emails']);
        $variables['form_data']['exclude_paths'] = implode("\n", $this->settings['exclude_paths']);
        $variables['form_data']['backup_file_location'] = implode("\n", $this->settings['backup_file_location']);
        $variables['form_data']['db_backup_archive_pre_sql'] = implode("\n", $this->settings['db_backup_archive_pre_sql']);
        $variables['form_data']['db_backup_archive_post_sql'] = implode("\n", $this->settings['db_backup_archive_post_sql']);
        $variables['form_data']['db_backup_execute_pre_sql'] = implode("\n", $this->settings['db_backup_execute_pre_sql']);
        $variables['form_data']['db_backup_execute_post_sql'] = implode("\n", $this->settings['db_backup_execute_post_sql']);
        $variables['form_data']['backup_missed_schedule_notify_emails'] = implode("\n", $this->settings['backup_missed_schedule_notify_emails']);
        
        if( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
            $data = array();
            $data = array_map( 'stripslashes_deep', $_POST );
    
            $variables['form_data'] = array_merge(array('db_backup_ignore_tables' => '', 'db_backup_ignore_table_data' => ''), $data);
            $backup = $this->services['backups'];
            $backups = $backup->setBackupPath($this->settings['working_directory'])->getAllBackups($this->settings['storage_details']);
            $data['meta'] = $backup->getBackupMeta($backups);
            $extra = array('db_creds' => $this->platform->getDbCredentials());
            $settings_errors = $this->services['settings']->validate($data, $extra);
            if( $settings_errors )
            {
                $variables['form_errors'] = array_merge($variables['form_errors'], $settings_errors);
            }
        }
    
        $variables['section']= $section;
        $variables['db_tables'] = $this->services['db']->getTables();
        $variables['backup_cron_commands'] = $this->platform->getBackupCronCommands($this->settings);
        $variables['ia_cron_commands'] = $this->platform->getIaCronCommands($this->settings);
        $variables['errors'] = $this->errors;
        $variables['threshold_options'] = $this->services['settings']->getAutoPruneThresholdOptions();
        $variables['available_db_backup_engines'] = $this->services['backup']->getDataBase()->getAvailableEnginesOptions();
        $variables['menu_data'] = $this->backup_lib->getSettingsViewMenu();
    
        $variables['view_helper'] = $this->view_helper;
        $variables['url_base'] = $this->url_base;
        $variables['theme_folder_url'] = plugin_dir_url(self::name);
        $variables['rest_api_route_entry'] = $this->platform->getRestApiRouteEntry($this->settings);
        
        //ee()->view->cp_page_title = $this->services['lang']->__($variables['section'].'_bp_settings_menu');
        //return ee()->load->view('settings', $variables, true);
        $template = 'admin/views/settings';
        $this->renderTemplate($template, $variables);
    }
    
}