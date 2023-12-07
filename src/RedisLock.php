<?php

declare(strict_types=1);

namespace Deable\Redis;

use Deable\Redis\Exceptions\LockException;
use Deable\Redis\Retry\Retry;

/**
 * Class RedisLock
 *
 * @package Deable\Redis
 */
final class RedisLock
{
	private const MIN_WAIT_TIME = 1000;
	private const MAX_WAIT_TIME = 128000;

	private RedisClient $client;

	private int $lockTtl;

	private array $keys = [];

	public function __construct(RedisClient $client, ?int $lockTtl = null)
	{
		$this->client = $client;
		$this->lockTtl = $lockTtl ?? (int) ini_get('max_execution_time');
	}

	public function acquire(string $key, ?float $timeout = null): bool
	{
		if (isset($this->keys[$key])) {
			return $this->increaseTimeout($key);
		}

		$options = ['nx'];
		if ($this->lockTtl > 0) {
			$options['ex'] = $this->lockTtl;
		}

		$lockKey = $this->lockKey($key);
		$start = microtime(true);
		$wait = self::MIN_WAIT_TIME;
		while ($this->client->set($lockKey, '', $options) === false) {
			if ($timeout !== null && (microtime(true) - $start) >= $timeout) {
				return false;
			}

			usleep($wait);
			if ($wait < self::MAX_WAIT_TIME) {
				$wait *= 2;
			}
		}

		$this->keys[$key] = true;

		return true;
	}

	public function release(string $key): void
	{
		if (!isset($this->keys[$key])) {
			return;
		}
		$this->client->del($this->lockKey($key));
		unset($this->keys[$key]);
	}

	public function releaseAll(): void
	{
		foreach ($this->keys as $key => $locked) {
			$this->release($key);
		}
	}

	private function increaseTimeout(string $key): bool
	{
		if (!isset($this->keys[$key])) {
			return false;
		}
		if ($this->lockTtl > 0) {
			$lockKey = $this->lockKey($key);
			return $this->client->expire($lockKey, $this->lockTtl);
		}

		return true;
	}

	private function lockKey(string $key): string
	{
		return sprintf('lock:%s', $key);
	}

}
