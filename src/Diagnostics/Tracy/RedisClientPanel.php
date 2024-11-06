<?php

declare(strict_types=1);

namespace Deable\Redis\Diagnostics\Tracy;

use Deable\Redis\Diagnostics\DiagnosticsHandler;
use Deable\Serializer\DefaultSerializer;
use Deable\Serializer\Serializer;
use Nette\Utils\Html;
use Redis;
use Tracy\Dumper;
use Tracy\IBarPanel;

/**
 * Class RedisClientPanel
 *
 * @package App\Services\Redis\Tracy
 */
final class RedisClientPanel implements IBarPanel, DiagnosticsHandler
{
	public int $maxRequests = 500;

	public int $count = 0;

	public float $totalTime = 0.0;

	public string $name;

	/** @var RedisClientPanelRequest[] */
	public array $requests = [];

	private ?RedisClientPanelRequest $last = null;

	public function __construct(string $name)
	{
		$this->name = $name;
	}

	public function getTab(): string
	{
		ob_start();
		require __DIR__ . '/RedisClientPanel.tab.phtml';

		return ob_get_clean() ?: '';
	}

	public function getPanel(): string
	{
		ob_start();
		require __DIR__ . '/RedisClientPanel.panel.phtml';

		return ob_get_clean() ?: '';
	}

	public function begin(string $cmd, array $args): void
	{
		$this->last = new RedisClientPanelRequest($cmd, $args);

		$this->count++;
		if ($this->count > $this->maxRequests) {
			return;
		}

		$this->requests[] = $this->last;
	}

	public function end($result): void
	{
		$this->last->end($result);
		$this->totalTime += $this->last->getTime();
	}

	public function formatResult($value): Html
	{
		$el = Html::el('div');
		if ($value instanceof Redis) {
			return $el->setAttribute('class', 'tracy-redis-muted')->setText('none');
		}
		if (is_string($value) && ($object = json_decode($value, true)) !== null) {
			return $el->addHtml(Dumper::toHtml($object, [Dumper::LIVE => true]));
		}
		if (is_string($value) && preg_match('~[\x20-\x7E\t\r\n]~', $value) > 0) {
			return $el->setAttribute('class', 'tracy-redis-muted')->setText('(binary)');
		}

		return $el->addHtml(Dumper::toHtml($value, [Dumper::LIVE => true]));
	}
}
