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
 * RebootableBoot
 */
class RebootableBoot extends Boot
{
    public const REBOOTABLE = ['terminate'];

    public const INFO = [
        'boot' => 'RebootableBoot boot description',
        'terminate' => 'RebootableBoot terminate description',
    ];
    
    public function boot(): void
    {
        // Do something
    }
    
    public function terminate(): void
    {
        // Do something
    }
}