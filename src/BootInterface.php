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
 * BootInterface.
 */
interface BootInterface
{
    /**
     * Returns an info of the boot.
     *
     * @return array
     */
    public function info(): array;

    /**
     * Returns the boot priority.
     *
     * @return int
     */
    public function priority(): int;
    
    /**
     * Returns the boots.
     *
     * @return array<int, string>
     */
    public function boots(): array;   
    
    /**
     * Returns whether the given method is rebootable.
     *
     * @param string $method
     * @return bool
     */
    public function isRebootable(string $method): bool;
}