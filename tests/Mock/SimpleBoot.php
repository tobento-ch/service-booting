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
 
namespace Tobento\Service\Booting\Test\Mock;

use Tobento\Service\Booting\Boot;

/**
 * SimpleBoot
 */
class SimpleBoot extends Boot
{
    public const INFO = [
        'boot' => 'SimpleBoot boot description',
    ];
    
    public function boot(): void
    {
        // Do something
    }
}