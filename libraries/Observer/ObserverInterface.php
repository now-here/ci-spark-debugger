<?php

// Deny direct script access
if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Debug observer interface
 * @copyright Copyright (c) 2012 Nowhere Group Ltd
 * @author Ben Tadiar <ben@handcraftedbyben.co.uk>
 * @version 1.0.0
 */
interface ObserverInterface
{
	public function __construct($CI, array $config);
	public function handle(array $breakpoints);
}