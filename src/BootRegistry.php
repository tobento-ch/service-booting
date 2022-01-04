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
 * BootRegistry
 */
class BootRegistry
{    
    /**
     * Create a new BootRegistry.
     *
     * @param string $booterName The booter name.
     * @param BootInterface $boot The boot.
     * @param null|int $priority The priority.
     * @param null|string $name A unique boot name.
     * @param array<int, string> $booted The methods booted.
     */
    public function __construct(
        private string $booterName,
        private BootInterface $boot,
        private null|int $priority = null,
        private null|string $name = null,
        private array $booted = [],
    ) {
        $this->name = $name ?: $boot::class;
    }

    /**
     * Get the name of the booter.
     *
     * @return string
     */
    public function booterName(): string
    {
        return $this->booterName;
    }
    
    /**
     * Get the name of the boot.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name ?: $this->boot::class;
    }

    /**
     * Get the boot.
     *
     * @return BootInterface
     */
    public function boot(): BootInterface
    {
        return $this->boot;
    }    
    
    /**
     * Get the priority of the boot.
     *
     * @return int
     */
    public function priority(): int
    {
        return $this->priority ?: $this->boot()->priority();
    }

    /**
     * Set the method to be booted.
     *
     * @param string The method name such as 'register'
     * @return void
     */
    public function addBooted(string $method): void
    {
        $this->booted[] = $method;
    }

    /**
     * Get the methods booted.
     *
     * @return array<int, string> ['registered', 'boot']
     */
    public function booted(): array
    {
        return $this->booted;
    }
}