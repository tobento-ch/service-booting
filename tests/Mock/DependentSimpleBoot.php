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
 * DependentSimpleBoot
 */
class DependentSimpleBoot extends Boot
{
    public const BOOT = [
        SimpleBoot::class,
    ];

    public const INFO = [
        'boot' => 'DependentBoot boot description',
        'terminate' => 'DependentBoot terminate description',
    ];
    
    /**
     * @var null|SimpleBoot
     */    
    protected array $boots = [];    
    
    public function boot(SimpleBoot $simpleBoot): void
    {
        // Do something
        $this->simpleBoot = $simpleBoot;
    }
    
    public function terminate(): void
    {
        // Do something
    }
    
    public function getSimpleBoot(): null|SimpleBoot
    {
        return $this->simpleBoot;
    }    
}