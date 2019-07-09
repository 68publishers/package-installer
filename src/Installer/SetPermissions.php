<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PackageInstaller\Installer;

use Psr;
use Nette;
use SixtyEightPublishers;

final class SetPermissions implements IInstaller
{
	use Nette\SmartObject;

	/** @var \SixtyEightPublishers\PackageInstaller\Executor\IExecutor  */
	private $executor;

	/** @var int  */
	private $chmod;

	/**
	 * @param \SixtyEightPublishers\PackageInstaller\Executor\IExecutor $executor
	 * @param int                                                       $chmod
	 */
	public function __construct(SixtyEightPublishers\PackageInstaller\Executor\IExecutor $executor, int $chmod = 0666)
	{
		$this->executor = $executor;
		$this->chmod = $chmod;
	}

	/****************** interface \SixtyEightPublishers\PackageInstaller\Installer\IInstaller ******************/

	/**
	 * {@inheritdoc}
	 */
	public function install(SixtyEightPublishers\PackageInstaller\Repository $repository, ?Psr\Log\LoggerInterface $logger = NULL): void
	{
		$logger = $logger ?? new Psr\Log\NullLogger();

		$logger->info('Permissions settings in progress...');

		/** @var \SplFileInfo $file */
		foreach (Nette\Utils\Finder::findFiles('*')->from($repository->absolutePath) as $file) {
			chmod($file->getRealPath(), $this->chmod);
		}
	}
}
