<?php

declare(strict_types=1);

namespace Deable\Redis\Exceptions;

/**
 * Class LockException
 *
 * @package Deable\Redis\Exceptions
 */
class LockException extends RedisClientException
{

	public static function acquireTimeout(): self
	{
		return new self('Redis lock could not be acquired.');
	}

	public static function create(string $message): self
	{
		return new self("Redis lock could not be acquired. Concurrency is way too high:\n$message");
	}

}
