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

namespace Tobento\Service\Booting\Test;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Booting\Booter;
use Tobento\Service\Booting\BooterInterface;
use Tobento\Service\Booting\AutowiringBootFactory;
use Tobento\Service\Container\Container;
use Tobento\Service\Booting\Test\Mock\DependentBoot;
use Tobento\Service\Booting\Test\Mock\FooBoot;
use Tobento\Service\Booting\Test\Mock\RebootableBoot;
use Tobento\Service\Booting\Test\Mock\SimpleBoot;
use Tobento\Service\Booting\Test\Mock\HigherPriorityBoot;
    
/**
 * BooterBootingTest
 */
class BooterBootingTest extends TestCase
{
    protected function createBooter(
        array $bootMethods = ['boot'],
        array $terminateMethods = ['terminate'],
    ): BooterInterface {
        
        return new Booter(
            bootFactory: new AutowiringBootFactory(new Container()),
            name: 'app',
            bootMethods: $bootMethods,
            terminateMethods: $terminateMethods,
        );
    }

    public function testBooting()
    {
        $booter = $this->createBooter();
        
        $booter->register(SimpleBoot::class);
        $booter->register(FooBoot::class);
        $booter->register(RebootableBoot::class);
        
        $booter->boot();
        $booter->terminate();
        
        $order = array_map(function($booted) {
            return $booted['boot'].'@'.$booted['method'];
        }, $booter->getBooted());

        
        $this->assertSame(
            [
                'Tobento\Service\Booting\Test\Mock\SimpleBoot@boot',
                'Tobento\Service\Booting\Test\Mock\FooBoot@boot',
                'Tobento\Service\Booting\Test\Mock\RebootableBoot@boot',
                'Tobento\Service\Booting\Test\Mock\RebootableBoot@terminate',
                'Tobento\Service\Booting\Test\Mock\FooBoot@terminate',
            ],
            $order
        );        
    }
    
    public function testBootingWithMoreMethods()
    {
        $booter = $this->createBooter(['register', 'boot'], ['end', 'terminate']);
        
        $booter->register(HigherPriorityBoot::class);
        $booter->register(FooBoot::class);
        
        $booter->boot();
        $booter->terminate();
        
        $order = array_map(function($booted) {
            return $booted['boot'].'@'.$booted['method'];
        }, $booter->getBooted());

        
        $this->assertSame(
            [
                'Tobento\Service\Booting\Test\Mock\HigherPriorityBoot@register',
                'Tobento\Service\Booting\Test\Mock\FooBoot@register',
                'Tobento\Service\Booting\Test\Mock\HigherPriorityBoot@boot',
                'Tobento\Service\Booting\Test\Mock\FooBoot@boot',
                'Tobento\Service\Booting\Test\Mock\FooBoot@end',
                'Tobento\Service\Booting\Test\Mock\HigherPriorityBoot@end',                
                'Tobento\Service\Booting\Test\Mock\FooBoot@terminate',
                'Tobento\Service\Booting\Test\Mock\HigherPriorityBoot@terminate',
            ],
            $order
        );        
    }
    
    public function testBootMethodAreCalledOnce()
    {
        $booter = $this->createBooter();
        
        $booter->register(FooBoot::class);
        
        $booter->boot();
        $booter->terminate();
        $booter->boot();
        $booter->terminate();
        $booter->boot();
        $booter->terminate();
        
        $this->assertSame(
            1,
            $booter->getBoot(FooBoot::class)->getBootCount()
        );
        
        $this->assertSame(
            1,
            $booter->getBoot(FooBoot::class)->getTerminateCount()
        );        
    }
    
    public function testBootMethodAreAlwaysCalledIfSetWithRebootable()
    {
        $booter = $this->createBooter();
        
        $booter->register(RebootableBoot::class);
        
        $booter->boot();
        $booter->terminate();
        $booter->boot();
        $booter->terminate();
        $booter->boot();
        $booter->terminate();
        
        $this->assertSame(
            3,
            $booter->getBoot(RebootableBoot::class)->getBootCount()
        );
        
        $this->assertSame(
            3,
            $booter->getBoot(RebootableBoot::class)->getTerminateCount()
        );        
    }    
    
    public function testUsesBootPriority()
    {
        $booter = $this->createBooter();
        
        $booter->register(FooBoot::class);
        $booter->register(HigherPriorityBoot::class);
        
        $booter->boot();
        $booter->terminate();
        
        $order = array_map(function($booted) {
            return $booted['boot'].'@'.$booted['method'];
        }, $booter->getBooted());

        
        $this->assertSame(
            [
                'Tobento\Service\Booting\Test\Mock\HigherPriorityBoot@boot',
                'Tobento\Service\Booting\Test\Mock\FooBoot@boot',
                'Tobento\Service\Booting\Test\Mock\FooBoot@terminate',
                'Tobento\Service\Booting\Test\Mock\HigherPriorityBoot@terminate',
            ],
            $order
        );        
    }
    
    public function testUsesRegisterPriorityIfSet()
    {
        $booter = $this->createBooter();
        
        $booter->register(FooBoot::class);
        $booter->register(HigherPriorityBoot::class, priority: 500);
        
        $booter->boot();
        $booter->terminate();
        
        $order = array_map(function($booted) {
            return $booted['boot'].'@'.$booted['method'];
        }, $booter->getBooted());

        
        $this->assertSame(
            [
                'Tobento\Service\Booting\Test\Mock\FooBoot@boot',
                'Tobento\Service\Booting\Test\Mock\HigherPriorityBoot@boot',
                'Tobento\Service\Booting\Test\Mock\HigherPriorityBoot@terminate',
                'Tobento\Service\Booting\Test\Mock\FooBoot@terminate',
            ],
            $order
        );        
    }    
}