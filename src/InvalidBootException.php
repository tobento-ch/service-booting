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

use InvalidArgumentException;
use Throwable;

/**
 * InvalidBootException
 */
class InvalidBootException extends InvalidArgumentException
{
    /**
     * Create a new InvalidMiddlewareException
     *
     * @param mixed $boot
     * @param string $message The message
     * @param int $code
     * @param null|Throwable $previous
     */
    public function __construct(
        protected mixed $boot,
        string $message = '',
        int $code = 0,
        null|Throwable $previous = null
    ) {
        if ($message === '') {
            
            $boot = $this->convertBootToString($boot);
            
            $message = 'Boot ['.$boot.'] is invalid';    
        }
        
        parent::__construct($message, $code, $previous);
    }
    
    /**
     * Get the boot.
     *
     * @return mixed
     */
    public function boot(): mixed
    {
        return $this->boot;
    }

    /**
     * Convert boot to string.
     *
     * @param mixed $boot
     * @return string
     */
    protected function convertBootToString(mixed $boot): string
    {
        if (is_string($boot)) {
            return $boot;
        }
        
        if (is_object($boot)) {
            return $boot::class;
        }
        
        return '';
    }
}