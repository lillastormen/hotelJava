<?php

declare(strict_types=1);

// Always require autoload when using packages
require(__DIR__ . '/vendor/autoload.php');

// Tell PHP to use this fine package
use Dotenv\Dotenv;

// "Connect" to .env and load it's content into $_ENV
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo $_ENV['API_KEY'];

// Write variable to .env
$envContent = file_get_contents(__DIR__ . '/.env');

$envContent .= "\n" . "USERNAME=RUNE";

file_put_contents(__DIR__ . '/.env', $envContent);

/* This will return an error/warning the first time you run this file, or when .env doesn't contain any property called USERNAME.
That's because we read the contents of .env on line 13, and then altered the file. So the new information has not been read by dotenv.
 */
echo $_ENV['USERNAME'];