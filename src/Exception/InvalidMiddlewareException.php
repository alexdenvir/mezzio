<?php

/**
 * @see       https://github.com/mezzio/mezzio for the canonical source repository
 * @copyright https://github.com/mezzio/mezzio/blob/master/COPYRIGHT.md
 * @license   https://github.com/mezzio/mezzio/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Mezzio\Exception;

use Psr\Container\ContainerExceptionInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

class InvalidMiddlewareException extends RuntimeException implements
    ContainerExceptionInterface,
    ExceptionInterface
{
    /**
     * @param mixed $middleware The middleware that does not fulfill the
     *     expectations of MiddlewarePipe::pipe
     */
    public static function forMiddleware($middleware) : self
    {
        return new self(sprintf(
            'Middleware "%s" is neither a string service name, a PHP callable,'
            . ' a %s instance, a %s instance, or an array of such arguments',
            is_object($middleware) ? get_class($middleware) : gettype($middleware),
            MiddlewareInterface::class,
            RequestHandlerInterface::class
        ));
    }

    /**
     * @param mixed $service The actual service created by the container.
     */
    public static function forMiddlewareService(string $name, $service) : self
    {
        return new self(sprintf(
            'Service "%s" did not to resolve to a %s instance; resolved to "%s"',
            $name,
            MiddlewareInterface::class,
            is_object($service) ? get_class($service) : gettype($service)
        ));
    }
}
