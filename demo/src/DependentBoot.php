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
 
namespace Tobento\Demo\Booting;

use Tobento\Service\Booting\Boot;

/**
 * DependentBoot
 */
class DependentBoot extends Boot
{
    public const BOOT = [
        SimpleBoot::class,
    ];

    public const INFO = [
        'boot' => 'DependentBoot boot description',
        'terminate' => 'DependentBoot terminate description',
    ];
    
    public function boot(SimpleBoot $simpleBoot): void
    {
        // Do something
    }
    
    public function terminate(): void
    {
        // Do something
    }
}