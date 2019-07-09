<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PackageInstaller\Installer;

use Psr;
use Nette;
use SixtyEightPublishers;

final class ComposerInstall implements IInstaller
{
	use Nette\SmartObject;

	/** @var \SixtyEightPublishers\PackageInstaller\Executor\IExecutor  */
	private $executor;

	/**
	 * @param \SixtyEightPublishers\PackageInstaller\Executor\IExecutor $executor
	 */
	public function __construct(SixtyEightPublishers\PackageInstaller\Executor\IExecutor $executor)
	{
		$this->executor = $executor;
	}

	/****************** interface \SixtyEightPublishers\PackageInstaller\Installer\IInstaller ******************/

	/**
	 * {@inheritdoc}
	 */
	public function install(SixtyEightPublishers\PackageInstaller\Repository $repository, ?Psr\Log\LoggerInterface $logger = NULL): void
	{
		$logger = $logger ?? new Psr\Log\NullLogger();

		if (!file_exists($repository->absolutePath . 'composer.json')) {
			throw new SixtyEightPublishers\PackageInstaller\Exception\InstallationException('Missing composer.json file.');
		} else {
			$logger->info('Running composer install:');
			$logger->info('========= COMPOSER OUTPUT START =========');
			$this->executor->execute(sprintf('composer install -d "%s"', $repository->relativePath), $logger);
			$logger->info('========= COMPOSER OUTPUT END =========');
		}
	}
}
