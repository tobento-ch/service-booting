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
 * BootFactoryInterface
 */
interface BootFactoryInterface
{   
    /**
     * Create a new Boot.
     *
     * @param mixed $boot
     * @return BootInterface
     * @throws InvalidBootException
     */
    public function createBoot(mixed $boot): BootInterface;
}