<?php
require __DIR__ . '/../vendor/autoload.php';
umask(0);
$configurator = new \Nette\Configurator();
$configurator->setTempDirectory(__DIR__ . '/../temp');
//$configurator->setDebugMode(TRUE);
$configurator->enableDebugger(__DIR__ . '/../log');
$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');

return $configurator->createContainer();
