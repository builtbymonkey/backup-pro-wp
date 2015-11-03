<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./backup_pro/includes/BackupProi18n.php
 */

/**
 * Backup Pro - Wordpress Translation Abstraction
 *
 * Handles installing Backup Pro for Wordpress
 *
 * @package 	Wordpress
 * @author		Eric Lamb <eric@mithra62.com>
 */
class BackupProi18n 
{
    /**
     * The Wordpress domain to use for translation
     * @var string
     */
	private $domain;

	/**
	 * Loads up the text domain 
	 */
	public function loadPluginTextdomain() {

		load_plugin_textdomain(
			$this->domain,
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}
	
	/**
	 * Sets the text domain to use for translation
	 * @param unknown $domain
	 */
	public function setDomain( $domain ) {
		$this->domain = $domain;
	}

}
