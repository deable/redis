<?php

declare(strict_types=1);

namespace Deable\Redis\Diagnostics;

/**
 * Interface DiagnosticsHandler
 *
 * @package Deable\Redis\Diagnostics
 */
interface DiagnosticsHandler
{
	public function begin(string $cmd, array $args);

	public function end($result);
}
