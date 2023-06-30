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

use Throwable;

/**
 * Booter.
 */
class Booter implements BooterInterface
{                
    /**
     * @var array<string, BootRegistry> The registered boots ['name' => BootRegistry, ...]
     */    
    protected array $boots = [];

    /**
     * @var array The boots which has been booted.
     */    
    protected array $booted = [];  
    
    /**
     * Create a new Booter.
     *
     * @param BootFactoryInterface $bootFactory
     * @param string $name A booter name.
     * @param array<int, string> $bootMethods
     * @param array<int, string> $terminateMethods
     */
    public function __construct(
        protected BootFactoryInterface $bootFactory,
        protected string $name = 'default',
        protected array $bootMethods = ['boot'],
        protected array $terminateMethods = ['terminate'],
    ) {}
    
    /**
     * Register a boot or multiple. 
     *
     * @param mixed $boots
     * @return static $this
     * @throws InvalidBootException
     */
    public function register(mixed ...$boots): static
    {
        $priority = $boots['priority'] ?? null;
        unset($boots['priority']);

        foreach($boots as $boot)
        {            
            $bootObj = $this->bootFactory->createBoot($boot);
            
            if (isset($this->boots[$bootObj::class])) {
                continue;
            }
            
            if (!empty($bootObj->boots())) {
                $this->register(...$bootObj->boots());    
            }
            
            $bootRegistry = new BootRegistry(
                $this->name,
                $bootObj,
                $priority
            );
            
            $this->boots[$bootRegistry->name()] = $bootRegistry;   
        }
        
        return $this;
    }
            
    /**
     * Boots the registered boots.
     *
     * @return void
     * @throws BootException
     */    
    public function boot(): void
    {
        $boots = $this->boots;
        
        // Sort by priority. Highest first.        
        uasort(
            $boots,
            fn(BootRegistry $a, BootRegistry $b): int => $b->priority() <=> $a->priority()
        );
        
        // Booting the boot methods.
        foreach($this->bootMethods as $method)
        {
            foreach($boots as $bootRegistry)
            {
                $this->booting($method, $bootRegistry);
            }
        }
    }

    /**
     * Terminates the registered boots.
     *
     * @return void
     * @throws BootException
     */    
    public function terminate(): void
    {
        $boots = $this->boots;
        
        $boots = array_reverse($boots);
        
        // Sort boots priority but on the opposite way.    
        uasort($boots, function(BootRegistry $a, BootRegistry $b): int {            
            return $a->priority() <=> $b->priority();
        });
            
        // Booting the terminate methods.
        foreach($this->terminateMethods as $method)
        {
            foreach($boots as $bootRegistry)
            {
                $this->booting($method, $bootRegistry);
            }
        }
    }

    /**
     * Returns a boot registry.
     *
     * @param string $name
     * @return null|BootRegistry
     */    
    public function get(string $name): null|BootRegistry
    {
        return $this->boots[$name] ?? null;
    }
    
    /**
     * Returns the specified boot or null if none.
     *
     * @param string $name
     * @return null|BootInterface
     */    
    public function getBoot(string $name): null|BootInterface
    {
        return $this->get($name)?->boot();
    }    
    
    /**
     * Gets the registered boots.
     *
     * @return array<string, BootRegistry>
     */    
    public function getBoots(): array
    {
        return $this->boots;
    }

    /**
     * Gets the booted boots.
     *
     * @return array
     */    
    public function getBooted(): array
    {
        return $this->booted;
    }
    
    /**
     * Booting the given method.
     *
     * @param string $method The booting method such as register, boot.
     * @param BootRegistry $bootRegistry
     * @return void
     * @throws BootException
     */        
    protected function booting(string $method, BootRegistry $bootRegistry): void
    {        
        // Rebooting if boot method is rebootable.
        if (
            ! $bootRegistry->boot()->isRebootable($method)
            && in_array($method, $bootRegistry->booted())
        ){
            return;
        }  
        
        if (! method_exists($bootRegistry->boot(), $method)) {
            return;
        }

        $bootRegistry->addBooted($method);

        $this->booted[] = [
            'name' => $this->name,
            'boot' => $bootRegistry->name(),
            'method' => $method,
            'priority' => $bootRegistry->priority(),
            'info' => $bootRegistry->boot()->info()[$method] ?? '',
        ];

        try {
            $this->bootFactory->callBootMethod($bootRegistry->boot(), $method);
        } catch (Throwable $t) {
            throw new BootException(
                $this,
                $bootRegistry,
                $method,
                $t->getMessage(),
                (int)$t->getCode(),
                $t
            );
        }       
    }
}