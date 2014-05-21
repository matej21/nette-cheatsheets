<?php
/** @var \Nette\DI\Container $container */
$container = require __DIR__ . '/../app/bootstrap.php';
/** @var \Nette\Application\Application $application */
$application = $container->getByType('Nette\Application\Application');
$application->run();
