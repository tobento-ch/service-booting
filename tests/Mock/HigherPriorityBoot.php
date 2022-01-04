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
 * HigherPriorityBoot
 */
class HigherPriorityBoot extends Boot
{
    public const PRIORITY = 2000;
    
    public function register(): void
    {
        // Do something
    }
    
    public function boot(): void
    {
        // Do something
    }
    
    public function end(): void
    {
        // Do something
    }
    
    public function terminate(): void
    {
        // Do something
    } 
}