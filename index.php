<?php

// Path to your craft/ folder
$craftPath = 'craftapp';
define('CRAFT_BASE_PATH', __DIR__.'/craft/');

// Do not edit below this line
$path = rtrim($craftPath, '/').'/app/index.php';

if (!is_file($path))
{
	http_response_code(503);
	exit('Could not find your craft/ folder. Please ensure that <strong><code>$craftPath</code></strong> is set correctly in '.__FILE__);
}

require_once $path;
