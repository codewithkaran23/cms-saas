<?php
// admin/logout.php
require_once '../core/init.php';

// Log out and send back to the main landing page
Auth::logout('');
