<?php

// Deny direct script access
if(!defined('BASEPATH')) exit('No direct script access allowed');

// Require the DebugException class
require_once('DebugException.php');

/**
 * Provide debugging functionality to a CodeIgniter application
 * @copyright Copyright (c) 2012 Nowhere Group Ltd
 * @author Ben Tadiar <ben@handcraftedbyben.co.uk>
 * @version 1.0.0
 */
class Debugger
{
	/**
	 * Forward declare CodeIgniter instance
	 * @var null|object
	 */
	private $CI = null;
	
	/**
	* Config array
	* @var array
	*/
	private $config = array();
	
	/**
	 * Forward declare breakpoints array 
	 * @var array
	 */
	private $breakpoints = array();
	
	/**
	 * Debug session observers
	 * @var array
	 */
	private $observers = array();
	
	/**
	 * Class constructor
	 * @return void
	 */
	public function __construct()
	{		
		// Get the CodeIgniter instance
		$this->CI =& get_instance();
		
		// Get the debugger configuration
		$this->config = $this->CI->config->item('debugger');
		
		// Set the default exception handler
		set_exception_handler(array($this, 'handleException'));
	}
	
	/**
	 * Bind an observer to the debug session
	 * $observer must implement the ObserverInterface
	 * When the logging functions are called, all registered
	 * observers will execute their handle() methods. This function
	 * is non-blocking and should an observer not be found, or not
	 * implement the interface, it simply won't be bound.
	 * @param string $observer The name of the observer class
	 * @return void
	 */
	public function bindObserver($observer)
	{
		$file = dirname(__FILE__) . '/Observer/' . $observer . '.php';
		if(file_exists($file)){
			require_once($file);
			$obj = new $observer($this->CI, $this->config);
			if($obj instanceof ObserverInterface){
				$this->observers[] = $obj;
			}
		}
	}
    
    /**
     * Add a breakpoint to the debug session
     * Breakpoints are user defined checkpoints in the application
     * that can provide significant debug information i.e an object
     * in it's current state, the response from an API call etc.
     * @param string $message A textual description of the debug breakpoint
     * @param mixed $data Data relevant to the breakpoint
     * @return boolean
     */
    public function addBreakpoint($message, $data = '')
    {
    	$this->breakpoints[] = array(
    		'message'   => $message,
    		'timestamp' => date('d-m-Y H:i:s'),
    		'data'      => $data,
    		'globals'   => array(
    			'get'     => $_GET,
    			'post'    => $_POST,
    			'cookie'  => $_COOKIE,
    			'session' => (isset($_SESSION)) ? $_SESSION : false,
    			'server'  => $_SERVER,
    		),
    	);
    	return true;
    }
    
    /**
     * Run the debugger on demand
	 * Call the handle method of all bound observers
	 * and send an email to the technical contacts
	 * @return void
     */
    public function debug()
    {
    	// Observer responses
    	$responses = array();
    	 
    	// Interate the observers and fire their handle() method
    	foreach($this->observers as $observer){
    		$responses[get_class($observer)] = $observer->handle($this->breakpoints);
    	}
    	
    	// Generate the notification email content
    	$viewData = array('responses' => $responses);
    	$message = $this->CI->load->view('Notification', $viewData, true);
    	 
    	// Prepare the to addresses
    	$to = implode(',', $this->config['emailTo']);
    	
    	// Set the message subject
    	$subject = '[' . $this->CI->config->item('base_url') . '] Debug Notification';
    	
    	// Prepare mail headers
    	$headers = array(
    		'From: ' . $this->config['emailFrom'],
    		'Content-Type:text/html; charset=utf-8'
    	);
    	
    	// Send the message
    	mail($to, $subject, $message, implode(PHP_EOL, $headers));
    }
    
    /**
     * Exception handler
     * If the Exception is a DebugException, call self::debug()
     * and display the debug view. Otherwise restore the
     * default/previous handler and rethrow the Exception
     * @todo May need to restore the default exception handler
     * @param object Exception $e
     */
    public function handleException(Exception $e = null)
    {	
    	if($e instanceof DebugException){
    		$this->debug();
    		echo $this->CI->load->view('View', null, true);
    	} else {
    		restore_exception_handler();
    		throw $e;
    	}
    }
}
