<?php

// Deny direct script access
if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Debug Exception class
 * Throwing this type of Exception will trigger the debugger
 * @copyright Copyright (c) 2012 Nowhere Group Ltd
 * @author Ben Tadiar <ben@handcraftedbyben.co.uk>
 * @version 1.0.0
 */
class DebugException extends Exception
{
	/**
	 * Call the base Exception __construct method
	 * @param string $message Defined so message cannot be empty
	 * @param int $code Exception code (default: 0)
	 * @param Exception $previous
	 */
	public function __construct($message, $code = 0, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
