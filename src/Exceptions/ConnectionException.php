<?php

declare(strict_types=1);

namespace Deable\Redis\Exceptions;

use Deable\Redis\RedisClientConfig;
use RuntimeException;

/**
 * Class ConnectionException
 *
 * @package Deable\Redis\Exceptions
 */
class ConnectionException extends RuntimeException implements RedisThrowable
{

	public static function create(RedisClientConfig $config, string $message): self
	{
		$address = $config->getAddress();

		return new self("Cannot connect to $address, errors during connection:\n$message");
	}

}
