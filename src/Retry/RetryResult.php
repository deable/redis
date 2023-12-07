<?php

declare(strict_types=1);

namespace Deable\Redis\Retry;

use Throwable;

/**
 * Class RetryResult
 *
 * @package Deable\Redis\Retry
 */
final class RetryResult
{
	/** @var mixed */
	public $result;

	/** @var Throwable[] */
	public array $errors = [];

	public function throwException(callable $factory)
	{
		if ($this->result || empty($this->errors)) {
			return;
		}

		throw $factory(implode("\n", array_unique(array_map(static fn (Throwable $e) => $e->getMessage(), $this->errors))));
	}
}
