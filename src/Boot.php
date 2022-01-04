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
 * Boot
 */
abstract class Boot implements BootInterface
{
    /**
     * @var int The boot priority.
     */
    public const PRIORITY = 1000;

    /**
     * @var array<int, string> The rebootable methods.
     */
    public const REBOOTABLE = [];

    /**
     * @var array<int, string> The boots.
     */
    public const BOOT = [];
    
    /**
     * @var array<string, mixed> An info of the boot methods.
     */
    public const INFO = [];
    
    /**
     * Returns an info of the boot.
     *
     * @return array
     */
    public function info(): array
    {
        return static::INFO;
    }

    /**
     * Returns the boot priority.
     *
     * @return int
     */
    public function priority(): int
    {
        return static::PRIORITY;
    }
    
    /**
     * Returns the boots.
     *
     * @return array<int, string>
     */
    public function boots(): array
    {
        return static::BOOT;
    }
    
    /**
     * Returns whether the given method is rebootable.
     *
     * @param string $method
     * @return bool
     */
    public function isRebootable(string $method): bool
    {
        return in_array($method, static::REBOOTABLE);
    }
}