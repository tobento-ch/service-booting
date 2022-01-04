<?php
declare(strict_types=1);

namespace Tobento\Demo\Booting;

error_reporting( -1 );
ini_set('display_errors', '1');

require __DIR__ . '/../../vendor/autoload.php';

use Tobento\Service\Booting\Booter;
use Tobento\Service\Booting\BootRegistry;
use Tobento\Service\Booting\AutowiringBootFactory;
use Tobento\Service\Container\Container;

// Any PSR-11 container
$container = new Container();

$booter = new Booter(
    bootFactory: new AutowiringBootFactory($container),
    name: 'app',
    bootMethods: ['boot'],
    terminateMethods: ['terminate'],
);

$booter->register(
    DependentBoot::class,
    RebootableBoot::class,
);

// you may define a priority:
// $booter->register(RebootableBoot::class, priority: 1900);

$booter->boot();
$booter->terminate();

echo '<pre>';
//print_r($booter->getBoots());
print_r($booter->getBooted());