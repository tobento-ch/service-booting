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

namespace Tobento\Service\Booting;

use Psr\Container\ContainerInterface;
use Tobento\Service\Autowire\Autowire;
use Tobento\Service\Autowire\AutowireException;
use Tobento\Service\Container\Container;
use League\Container\Container as LeagueContainer;
use Symfony\Component\DependencyInjection\Container as SymfonyContainer;

/**
 * AutowiringBootFactory
 */
class AutowiringBootFactory implements BootFactoryInterface
{
    /**
     * @var Autowire
     */
    protected Autowire $autowire;
    
    /**
     * Create a new AutowiringBootFactory
     *
     * @param ContainerInterface $container
     */    
    public function __construct(
        ContainerInterface $container
    ) {
        $this->autowire = new Autowire($container);
    }
    
    /**
     * Create a new Boot.
     *
     * @param mixed $boot
     * @return BootInterface
     * @throws InvalidBootException
     */
    public function createBoot(mixed $boot): BootInterface
    {
        // if it is already an instance, just return.
        if ($boot instanceof BootInterface) {
            return $this->bindToContainer($boot);
        }
        
        $parameters = [];
            
        if (
            is_array($boot) 
            && isset($boot[0])
            && is_string($boot[0])
        ) {
            $parameters = $boot;
            
            // remove boot
            array_shift($parameters);

            $boot = $boot[0];
        }
        
        if (!is_string($boot))
        {
            throw new InvalidBootException($boot);
        }    
        
        try {
            if ($this->autowire->container()->has($boot)) {
                $boot = $this->autowire->container()->get($boot);
            } else {
                $boot = $this->autowire->resolve($boot, $parameters);   
            }
        } catch (AutowireException $e) {
            throw new InvalidBootException($boot, $e->getMessage());
        }
        
        if (! $boot instanceof BootInterface)
        {
            throw new InvalidBootException($boot);
        }
        
        return $this->bindToContainer($boot);
    }
    
    /**
     * Call a boot method.
     *
     * @param BootInterface $boot
     * @param string $method
     * @return void
     */
    public function callBootMethod(BootInterface $boot, string $method): void
    {
        $this->autowire->call([$boot, $method]);
    }
    
    /**
     * Bind boot to container for autowiring boot.
     *
     * @param BootInterface $boot
     * @return BootInterface
     */
    protected function bindToContainer(BootInterface $boot): BootInterface
    {
        $container = $this->autowire->container();
        
        switch (true) {                
            case $container instanceof Container:
                $container->set($boot::class, $boot);
                break;
            case $container instanceof SymfonyContainer:
                $container->set($boot::class, $boot);
                break;
            case $container instanceof LeagueContainer:
                $container->add($boot::class, $boot);
                break;
        }            
        
        return $boot;
    }    
}