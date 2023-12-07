<?php

declare(strict_types=1);

namespace Deable\Redis;

use Deable\Redis\Diagnostics\Tracy\RedisClientPanel;
use Nette\Application\Application;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\ServiceDefinition;
use Nette\Schema\Expect;
use Nette\Schema\Schema;

/**
 * Class RedisExtension
 *
 * @package Deable\Redis
 */
final class RedisExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'config' => Expect::structure([
				'host' => Expect::string()->required(),
				'port' => Expect::int(6379),
				'timeout' => Expect::float(5.0),
				'database' => Expect::int(0),
				'persistent' => Expect::bool(false),
				'auth' => Expect::string(),
			])->castTo('array'),
			'autowired' => Expect::bool(false),
			'debugger' => Expect::bool(false),
		]);
	}

	public function beforeCompile(): void
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('config'))
			->setType(RedisClientConfig::class)
			->setArguments($this->config->config)
			->setAutowired(false);

		$client = $builder->addDefinition($this->prefix('client'))
			->setType(RedisClient::class)
			->setArgument('config', $this->prefix('@config'))
			->setAutowired($this->config->autowired);

		/** @var ServiceDefinition $application */
		$application = $builder->getDefinitionByType(Application::class);
		$application->addSetup([self::class, 'registerClient'], ['@self', $this->prefix('@client')]);

		if ($this->config->debugger) {
			$builder->addDefinition($this->prefix('panel'))
				->setType(RedisClientPanel::class)
				->setArgument('name', $this->name)
				->setAutowired(false);

			$client->addSetup('setDiagnosticsHandler', [$this->prefix('@panel')]);
			$client->addSetup('@Tracy\Bar::addPanel', [$this->prefix('@panel')]);
		}
	}

	public static function registerClient(Application $application, RedisClient $client): void
	{
		$application->onShutdown[] = [$client, 'close'];
	}

}
