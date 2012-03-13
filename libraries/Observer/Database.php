<?php

// Deny direct script access
if(!defined('BASEPATH')) exit('No direct script access allowed');

// Require the interface
require_once('ObserverInterface.php');

/**
 * Provide functionality to store debug sessions in the database
 * @copyright Copyright (c) 2012 Nowhere Group Ltd
 * @author Ben Tadiar <ben@handcraftedbben.co.uk>
 * @version 1.0.0
 */
class Database implements ObserverInterface
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
		$this->setup();
	}
	
	/**
	 * Setup the observer
	 * Get the database object and create debug table if required
	 * @return void
	 */
	private function setup()
	{
		// Load the database object
		$this->CI->load->database($this->config['dbConfig']);
		
		// Check if the debug table exists
		if(!$this->CI->db->table_exists($this->config['table'])){
			// Create the table if it doesn't exist
			$this->CI->load->dbforge();
			$this->CI->dbforge->add_field('id');
			$this->CI->dbforge->add_field('debug_time datetime NOT NULL');
			$this->CI->dbforge->add_field('debug_breakpoints mediumtext NOT NULL');
			$this->CI->dbforge->create_table($this->config['table']);
		}
	}
	
	/**
	 * Handle the debug session
	 * @see ObserverInterface::handle()
	 * @return string
	 */
	public function handle(array $breakpoints)
	{	
		// Prepare the SQL query and parameters
		$table = $this->config['table'];
		$query = "INSERT INTO $table (debug_time, debug_breakpoints) VALUES (?, ?)";
		$binds = array(date('Y-m-d H:i:s'), serialize($breakpoints));
		
		// Get the database details for use in return message
		$db = $this->CI->db->database;
		$host = $this->CI->db->hostname;
		
		// Execute the query and return
		if($this->CI->db->query($query, $binds)){
			$id = $this->CI->db->insert_id();
			return "Debug session stored in $db.$table ($host) with record ID $id";
		}
		return "Failed to store debug session in $db.$table ($host)";
	}
}
