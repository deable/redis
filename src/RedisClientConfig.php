<?php

declare(strict_types=1);

namespace Deable\Redis;

/**
 * Class RedisClientConfig
 *
 * @package Deable\Redis
 */
final class RedisClientConfig
{
	public string $host;

	public int $port;

	public float $timeout;

	public int $database;

	public bool $persistent;

	public ?string $auth;

	public function __construct(string $host, int $port, float $timeout = 0.0, int $database = 0, bool $persistent = false, ?string $auth = null)
	{
		$this->host = $host;
		$this->port = $port;
		$this->timeout = $timeout;
		$this->database = $database;
		$this->persistent = $persistent;
		$this->auth = $auth;
	}

	public function getAddress(): string
	{
		return sprintf('%s:%d', $this->host, $this->port);
	}

}
