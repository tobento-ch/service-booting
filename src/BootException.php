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

use Exception;
use Throwable;

/**
 * BootException
 */
class BootException extends Exception
{
    /**
     * Create a new BootException.
     *
     * @param BooterInterface $booter
     * @param BootRegistry $bootRegistry
     * @param string $method The boot method
     * @param string $message The message
     * @param int $code
     * @param null|Throwable $previous
     */
    public function __construct(
        protected BooterInterface $booter,
        protected BootRegistry $bootRegistry,
        protected string $method,
        string $message = '',
        int $code = 0,
        null|Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get the booter.
     *
     * @return BooterInterface
     */
    public function booter(): BooterInterface
    {
        return $this->booter;
    }
    
    /**
     * Get the bootRegistry.
     *
     * @return BootRegistry
     */
    public function bootRegistry(): BootRegistry
    {
        return $this->bootRegistry;
    }
    
    /**
     * Get the method.
     *
     * @return string
     */
    public function method(): string
    {
        return $this->method;
    }    
}