<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./backup_pro/includes/BackupProLoader.php
 */

/**
 * Backup Pro - Hook Loader
 *
 * Abstraction to load up all the hooks BP needs for WP
 *
 * @package 	Wordpress
 * @author		Eric Lamb <eric@mithra62.com>
 */
class BackupProLoader 
{
    /**
     * The actions we're registering
     * @var array
     */
	protected $actions = array();
	
	/**
	 * The filters we're registering
	 * @var array
	 */
	protected $filters = array();
	
	/**
	 * Set it up
	 */
	public function __construct() 
	{
		$this->actions = array();
		$this->filters = array();
	}
	
	/**
	 * Adds an action to the collection
	 * @param string $hook
	 * @param object $component
	 * @param string $callback
	 * @param number $priority
	 * @param number $accepted_args
	 */
	public function addAction( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) 
	{
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}
	
	/**
	 * Adds a filter to the collection
	 * @param string $hook
	 * @param object $component
	 * @param string $callback
	 * @param number $priority
	 * @param number $accepted_args
	 */
	public function addFilter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) 
	{
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}
	
	/**
	 * Compiles all the hooks into a collection
	 * @param array $hooks
	 * @param string $hook
	 * @param object $component
	 * @param string $callback
	 * @param number $priority
	 * @param number $accepted_args
	 * @return array
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) 
	{
		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		);
		return $hooks;
	}

	/**
	 * Compiles the collected actions and filters with Wordpress
	 */
	public function run() 
	{

		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}
	}

}
