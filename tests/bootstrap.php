<?php
// The Nette Tester command-line runner can be
// invoked through the command: ../vendor/bin/tester .
if (@!include __DIR__ . '/../vendor/autoload.php') {
	echo 'Install Nette Tester using `composer install`';
	exit(1);
}
require __DIR__ . "/helpers.php";
// configure environment
Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');
