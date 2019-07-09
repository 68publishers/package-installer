<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PackageInstaller\DI;

use Nette;
use SixtyEightPublishers;

final class PackageInstallerExtension extends Nette\DI\CompilerExtension
{
	/** @var array  */
	private $defaults = [
		'paths' => [
			'cwd' => '%appDir%/../',
			'repository' => 'temp/packages/', # relatively from cwd
			'dist' => 'www/packages/', # relatively from cwd
		],
		'packages' => [],
	];

	/** @var array  */
	private $packageDefaults = [
		'url' => NULL,
		'branch' => NULL,
		'installers' => [],
	];

	/**
	 * {@inheritdoc}
	 */
	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$repositoryContainer = $builder->addDefinition($this->prefix('repository_container'))
			->setType(SixtyEightPublishers\PackageInstaller\RepositoryContainer::class)
			->setAutowired(FALSE)
			->setArguments([
				'cwd' => $cwd = Nette\DI\Helpers::expand($config['paths']['cwd'], $builder->parameters),
				'repositoryPath' => $config['paths']['repository'],
				'distPath' => $config['paths']['dist'],
			]);

		$builder->addDefinition($this->prefix('executor'))
			->setType(SixtyEightPublishers\PackageInstaller\Executor\IExecutor::class)
			->setFactory(SixtyEightPublishers\PackageInstaller\Executor\SymfonyProcessExecutor::class)
			->setArguments([
				'cwd' => $cwd,
			]);

		$installerChain = $builder->addDefinition($this->prefix('installer'))
			->setType(SixtyEightPublishers\PackageInstaller\Installer\InstallerChain::class)
			->setAutowired(FALSE);

		foreach ($config['packages'] as $name => $package) {
			Nette\Utils\Validators::assert($package, 'array');

			$package = $this->validateConfig($this->packageDefaults, $package);

			Nette\Utils\Validators::assertField($package, 'url', 'string');
			Nette\Utils\Validators::assertField($package, 'branch', 'null|string');
			Nette\Utils\Validators::assertField($package, 'installers', 'array');

			if (empty($package['installers'])) {
				throw new Nette\InvalidStateException(sprintf(
					'Missing installers for repository "%s". Please defined almost one in option %s.packages%s.installers',
					$name,
					$this->name,
					$name
				));
			}

			$repositoryContainer->addSetup('addRepository', [
				'name' => $name,
				'repositoryUrl' => $package['url'],
				'branch' => $package['branch'],
			]);

			foreach ($package['installers'] as $installer) {
				$installerChain->addSetup('addInstallerForRepository', [
					'repository' => $name,
					'installer' => is_string($installer) ? new Nette\DI\Statement($installer) : $installer,
				]);
			}
		}

		$builder->addDefinition($this->prefix('command.install_package'))
			->setType(SixtyEightPublishers\PackageInstaller\Console\InstallPackageCommand::class)
			->setArguments([
				'repositoryContainer' => $repositoryContainer,
				'installer' => $installerChain,
			]);
	}
}
