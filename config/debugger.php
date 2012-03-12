<?php

### DEBUGGER LIBRARY ###

// Array of email addresses to send debug notifications to
$debugger['emailTo'][] = 'Ben Tadiar <ben@handcraftedbyben.co.uk>';
$debugger['emailTo'][] = 'Ben Tadiar <ben@bentadiar.co.uk>';

// Email address to send debug notifications from
$debugger['emailFrom'] = 'Ben Tadiar <ben@handcraftedbyben.co.uk>';

### LOG FILE OBSERVER ###

// Log file prefix
$debugger['prefix']    = 'debug_';

// Log file extension, excluding the dot
$debugger['extension'] = 'log';

### DATABASE OBSERVER ###

// Database configuration to use. See /application/config/database.php
$debugger['dbConfig']  = 'default';

// Database table to store debug entries in
$debugger['table']     = 'debug';

//==================== DO NOT CHANGE BELOW THIS LINE ====================

// Set the debugger configuration
$config['debugger'] = $debugger;