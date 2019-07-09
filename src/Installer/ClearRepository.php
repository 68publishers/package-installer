<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PackageInstaller\Installer;

use Psr;
use Nette;
use SixtyEightPublishers;

final class ClearRepository implements SixtyEightPublishers\PackageInstaller\Installer\IInstaller
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

		if (is_dir($repository->absolutePath)) {
			(new SetPermissions($this->executor))->install($repository, $logger);

			$logger->info(sprintf('Deleting existing directory %s', $repository->absolutePath));

			Nette\Utils\FileSystem::delete($repository->absolutePath);
		}

		Nette\Utils\FileSystem::createDir($repository->absolutePath);
	}
}
