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
 * FailureBoot
 */
class FailureBoot extends Boot
{    
    public function boot(): void
    {
        // test for BootException
        $this->do();
    }
    
    public function terminate(): void
    {
        // test for BootException
        $this->do();
    }    
}