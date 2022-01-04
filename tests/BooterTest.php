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
use Tobento\Service\Booting\BootRegistry;
use Tobento\Service\Booting\InvalidBootException;
use Tobento\Service\Booting\BootException;
use Tobento\Service\Container\Container;
use Tobento\Service\Booting\Test\Mock\DependentBoot;
use Tobento\Service\Booting\Test\Mock\RebootableBoot;
use Tobento\Service\Booting\Test\Mock\SimpleBoot;
use Tobento\Service\Booting\Test\Mock\FailureBoot;
    
/**
 * BooterTest
 */
class BooterTest extends TestCase
{    
    public function testThatImplementsBooterInterface()
    {
        $booter = new Booter(
            bootFactory: new AutowiringBootFactory(new Container()),
        );
        
        $this->assertInstanceOf(
            BooterInterface::class,
            $booter
        );
    }

    public function testRegisterMethod()
    {
        $booter = new Booter(
            bootFactory: new AutowiringBootFactory(new Container()),
        );
        
        $booter->register(SimpleBoot::class);
        
        $this->assertSame(
            1,
            count($booter->getBoots())
        );
    }
    
    public function testRegisterMethodMulitple()
    {
        $booter = new Booter(
            bootFactory: new AutowiringBootFactory(new Container()),
        );
        
        $booter->register(
            SimpleBoot::class,
            RebootableBoot::class,
        );
        
        $this->assertSame(
            2,
            count($booter->getBoots())
        );
    }    
    
    public function testRegisterMethodThrowsInvalidBootException()
    {
        $this->expectException(InvalidBootException::class);
        
        $booter = new Booter(
            bootFactory: new AutowiringBootFactory(new Container()),
        );
        
        $booter->register(InvaidBoot::class);
    }
    
    public function testGetMethod()
    {
        $booter = new Booter(
            bootFactory: new AutowiringBootFactory(new Container()),
        );
        
        $booter->register(SimpleBoot::class);
        
        $this->assertInstanceOf(
            BootRegistry::class,
            $booter->get(SimpleBoot::class)
        );
        
        $this->assertSame(
            null,
            $booter->get(InvalidBoot::class)
        );        
    }
    
    public function testGetBootMethod()
    {
        $booter = new Booter(
            bootFactory: new AutowiringBootFactory(new Container()),
        );
        
        $boot = new SimpleBoot();
        
        $booter->register($boot);
        
        $this->assertSame(
            $boot,
            $booter->getBoot(SimpleBoot::class)
        );
        
        $this->assertSame(
            null,
            $booter->getBoot(InvalidBoot::class)
        );
    }
    
    public function testGetBootsMethod()
    {
        $booter = new Booter(
            bootFactory: new AutowiringBootFactory(new Container()),
        );
        
        $booter->register(
            SimpleBoot::class,
            RebootableBoot::class,
        );
        
        $this->assertSame(
            2,
            count($booter->getBoots())
        );
        
        $this->assertInstanceOf(
            BootRegistry::class,
            $booter->getBoots()[SimpleBoot::class]
        );       
    }
    
    public function testGetBootedMethodReturnsEmptyArrayIfNotYetBooted()
    {
        $booter = new Booter(
            bootFactory: new AutowiringBootFactory(new Container()),
        );
        
        $booter->register(
            SimpleBoot::class,
            RebootableBoot::class,
        );
        
        $this->assertSame(
            0,
            count($booter->getBooted())
        );     
    }
    
    public function testGetBootedMethod()
    {
        $booter = new Booter(
            bootFactory: new AutowiringBootFactory(new Container()),
        );
        
        $booter->register(
            SimpleBoot::class,
            RebootableBoot::class,
        );
        
        $booter->boot();
            
        $this->assertSame(
            2,
            count($booter->getBooted())
        );     
    }
    
    public function testBootMethodThrowsBootException()
    {
        $this->expectException(BootException::class);
        
        $booter = new Booter(
            bootFactory: new AutowiringBootFactory(new Container()),
        );
        
        $booter->register(FailureBoot::class);
        
        $booter->boot();     
    }
    
    public function testTerminateMethodThrowsBootException()
    {
        $this->expectException(BootException::class);
        
        $booter = new Booter(
            bootFactory: new AutowiringBootFactory(new Container()),
        );
        
        $booter->register(FailureBoot::class);
        
        $booter->terminate();     
    }
    
    public function testDependentBootsAreGettingRegistered()
    {
        $booter = new Booter(
            bootFactory: new AutowiringBootFactory(new Container()),
        );
        
        $booter->register(DependentBoot::class);
            
        $this->assertInstanceOf(
            DependentBoot::class,
            $booter->getBoot(DependentBoot::class)
        );
        
        $this->assertInstanceOf(
            SimpleBoot::class,
            $booter->getBoot(SimpleBoot::class)
        );        
    }    
}