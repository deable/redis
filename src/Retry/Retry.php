<?php

declare(strict_types=1);

namespace Deable\Redis\Retry;

use Throwable;

/**
 * Class Retry
 *
 * @package Deable\Redis\Retry
 */
final class Retry
{

	public static function run(int $maxAttempts, callable $func): RetryResult
	{
		$result = new RetryResult();
		$attempt = 0;
		do {
			try {
				$result->result = $func();

				return $result;
			} catch (Throwable $e) {
				$result->errors[] = $e;
			}
		} while ($attempt++ < $maxAttempts);

		return $result;
	}

}
