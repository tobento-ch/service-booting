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

/**
 * BooterInterface.
 */
interface BooterInterface
{
    /**
     * Register a boot or multiple. 
     *
     * @param mixed $boots
     * @return static $this
     * @throws InvalidBootException
     */
    public function register(mixed ...$boots): static;
            
    /**
     * Boots the registered boots.
     *
     * @return void
     */    
    public function boot(): void;

    /**
     * Terminates the registered boots.
     *
     * @return void
     */    
    public function terminate(): void;

    /**
     * Returns a boot registry.
     *
     * @return null|BootRegistry
     */    
    public function get(string $name): null|BootRegistry;
    
    /**
     * Returns the specified boot or null if none.
     *
     * @return null|BootInterface
     */    
    public function getBoot(string $name): null|BootInterface;
    
    /**
     * Gets the registered boots.
     *
     * @return array
     */    
    public function getBoots(): array;

    /**
     * Gets the booted boots.
     *
     * @return array
     */    
    public function getBooted(): array;
}