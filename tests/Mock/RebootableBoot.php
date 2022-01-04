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
 * RebootableBoot
 */
class RebootableBoot extends Boot
{
    public const REBOOTABLE = ['boot', 'terminate'];

    public const INFO = [
        'boot' => 'RebootableBoot boot description',
        'terminate' => 'RebootableBoot terminate description',
    ];
    
    protected int $bootCount = 0;
    
    protected int $terminateCount = 0;    
    
    public function register(): void
    {
        // Do something
    }
    
    public function boot(): void
    {
        // Do something
        $this->bootCount++;
    }
    
    public function getBootCount(): int
    {
        return $this->bootCount;
    }    
    
    public function end(): void
    {
        // Do something
    }
    
    public function terminate(): void
    {
        // Do something
        $this->terminateCount++;
    }
    
    public function getTerminateCount(): int
    {
        return $this->terminateCount;
    }
}