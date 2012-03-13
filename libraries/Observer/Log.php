<?php

// Deny direct script access
if(!defined('BASEPATH')) exit('No direct script access allowed');

// Require the interface
require_once('ObserverInterface.php');

/**
 * Provide error log functionality to the Debug library
 * @copyright Copyright (c) 2012 Nowhere Group Ltd
 * @author Ben Tadiar <ben@handcraftedbben.co.uk>
 * @version 1.0.0
 */
class Log implements ObserverInterface
{
	/**
	* Config array
	* @var array
	*/
	private $config = array();
	
	/**
	 * Class contructor
	 * Set the observer config options
	 * @param object $CI
	 * @param array $config
	 * @return void
	 */
	public function __construct($CI, array $config)
	{
		// Setup the observer
		$this->CI = $CI;
		$this->config = $config;
	}
	
	/**
	 * Handle the debug session
	 * @see ObserverInterface::handle()
	 * @return string
	 */
	public function handle(array $breakpoints)
	{
		$logDir  = realpath(APPPATH) . '/logs/';
		$logFile = $logDir . $this->config['prefix'] . mt_rand() . '.' . $this->config['extension'];
		$logEntry = serialize($breakpoints);
		if(file_put_contents($logFile, $logEntry, LOCK_EX) > 0){
			return 'Debug session written to ' . $logFile;
		}
		return 'Unable to write to log file ' . $logFile;
	}
}
