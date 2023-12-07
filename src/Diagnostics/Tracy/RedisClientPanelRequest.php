<?php

declare(strict_types=1);

namespace Deable\Redis\Diagnostics\Tracy;

/**
 * Class RedisClientPanelRequest
 *
 * @package App\Services\Redis\Tracy
 */
final class RedisClientPanelRequest
{
	public string $cmd;

	public array $args;

	public float $start;

	/** @var mixed */
	public $result;

	public ?float $stop = null;

	public function __construct(string $cmd, array $args)
	{
		$this->cmd = $cmd;
		$this->args = $args;
		$this->start = microtime(true);
	}

	public function end($result): void
	{
		$this->result = $result;
		$this->stop = microtime(true);
	}

	public function getTime(): float
	{
		return $this->stop === null ? 0.0 : 1000 * ($this->stop - $this->start);
	}
}
