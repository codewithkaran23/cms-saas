<?php
// logout.php
require_once 'core/init.php';
session_destroy();
redirect('index.php');
