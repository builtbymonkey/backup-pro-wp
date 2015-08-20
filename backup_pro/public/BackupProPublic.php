<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./backup_pro/public/BackupProPublic.php
 */
 
use mithra62\BackupPro\Platforms\Controllers\Wordpress AS WpController;
use mithra62\BackupPro\BackupPro AS BpInterface;

/**
 * Backup Pro - Wordpress Public Dispatcher
 *
 * Abstracts setting up the administration details
 *
 * @package 	Wordpress
 * @author		Eric Lamb <eric@mithra62.com>
 */
class BackupProPublic extends WpController implements BpInterface
{
    /**
     * The shortname for the plugin
     * @var string
     */
	private $plugin_name = self::name;

	/**
	 * The version of the plugin
	 * @var number
	 */
	private $version = self::version;
	
	/**
	 * Processes the Cron Backup
	 */
	public function procCronBackup()
	{
	    if( $this->getPost('backup_pro') == $this->settings['cron_query_key'] && $this->getPost('backup') != '' )
	    {
	        $page = new BackupProCronController();
	        $page->setContext($this->context);
	        $page->cron();
	        exit;
	    }
	}
	
	/**
	 * Processes the Integrity Check Cron
	 */
	public function procIntegrityCron()
	{
	    if( $this->getPost('backup_pro') == $this->settings['cron_query_key'] && $this->getPost('integrity') == 'check' )
	    {
	        $page = new BackupProCronController();
	        $page->setContext($this->context);
	        $page->integrity();
	        exit;
	    }
	}
}
