<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PackageInstaller\Installer;

use Psr;
use Nette;
use SixtyEightPublishers;

final class CopyDist implements IInstaller
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

		if (!is_dir($repositoryDist = SixtyEightPublishers\PackageInstaller\Helpers::concatPaths($repository->absolutePath, 'dist'))) {
			throw new SixtyEightPublishers\PackageInstaller\Exception\InstallationException('Missing "dist" directory.');
		} else {
			$logger->info(sprintf('Copying "dist" directory into %s', $repository->absoluteDistPath));
			Nette\Utils\FileSystem::copy($repositoryDist, $repository->absoluteDistPath, TRUE);
		}
	}
}
