<?php

declare(strict_types=1);

namespace Deable\Redis\Exceptions;

use Deable\Redis\RedisClientConfig;

/**
 * Class TransactionException
 *
 * @package Deable\Redis\Exceptions
 */
class TransactionException extends RedisClientException
{

	public static function create(RedisClientConfig $config, ?string $message): self
	{
		$address = $config->getAddress();

		return new self("Transaction on $address was aborted! $message");
	}

}
