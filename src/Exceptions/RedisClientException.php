<?php

declare(strict_types=1);

namespace Deable\Redis\Exceptions;

use RuntimeException;

/**
 * Class RedisClientException
 *
 * @package Deable\Redis\Exceptions
 */
class RedisClientException extends RuntimeException implements RedisThrowable
{
	public ?string $request = null;

	public ?string $response = null;
}
