<?php

/**
 * TOBENTO
 *
 * @copyright   Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

declare(strict_types=1);

namespace Tobento\Service\Booting\Test\League;

use Tobento\Service\Booting\Test\BooterBootingTest as DefaultBooterBootingTest;
use Tobento\Service\Booting\Booter;
use Tobento\Service\Booting\BooterInterface;
use Tobento\Service\Booting\AutowiringBootFactory;
use League\Container\Container as LeagueContainer;
use Tobento\Service\Booting\Test\Mock\DependentBoot;
use Tobento\Service\Booting\Test\Mock\FooBoot;
use Tobento\Service\Booting\Test\Mock\RebootableBoot;
use Tobento\Service\Booting\Test\Mock\SimpleBoot;
use Tobento\Service\Booting\Test\Mock\HigherPriorityBoot;
    
/**
 * BooterBootingTest
 */
class BooterBootingTest extends DefaultBooterBootingTest
{
    protected function createBooter(
        array $bootMethods = ['boot'],
        array $terminateMethods = ['terminate'],
    ): BooterInterface {
        
        return new Booter(
            bootFactory: new AutowiringBootFactory(new LeagueContainer()),
            name: 'app',
            bootMethods: $bootMethods,
            terminateMethods: $terminateMethods,
        );
    }
}