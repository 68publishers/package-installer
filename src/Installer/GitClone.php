<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PackageInstaller\Installer;

use Psr;
use Nette;
use SixtyEightPublishers;

final class GitClone implements IInstaller
{
	use Nette\SmartObject;

	/** @var \SixtyEightPublishers\PackageInstaller\Executor\IExecutor  */
	private $executor;

	/** @var int|NULL  */
	private $depth;

	/**
	 * @param \SixtyEightPublishers\PackageInstaller\Executor\IExecutor $executor
	 * @param int|NULL                                                  $depth
	 */
	public function __construct(SixtyEightPublishers\PackageInstaller\Executor\IExecutor $executor, ?int $depth = 1)
	{
		$this->executor = $executor;
		$this->depth = $depth;
	}

	/**
	 * @param \SixtyEightPublishers\PackageInstaller\Repository $repository
	 * @param \Psr\Log\LoggerInterface                          $logger
	 *
	 * @return void
	 */
	private function cloneRepository(SixtyEightPublishers\PackageInstaller\Repository $repository, Psr\Log\LoggerInterface $logger): void
	{
		$this->executor->execute(sprintf(
			'git clone -b %s --single-branch%s %s %s',
			$repository->branch,
			NULL === $this->depth ? '' : ' --depth ' . $this->depth,
			$repository->repositoryUrl,
			$repository->relativePath
		), $logger);
	}

	/****************** interface \SixtyEightPublishers\PackageInstaller\Installer\IInstaller ******************/

	/**
	 * {@inheritdoc}
	 */
	public function install(SixtyEightPublishers\PackageInstaller\Repository $repository, ?Psr\Log\LoggerInterface $logger = NULL): void
	{
		$logger = $logger ?? new Psr\Log\NullLogger();

		$logger->info(sprintf(
			'Cloning repository %s (branch: %s):',
			$repository->repositoryUrl,
			$repository->branch
		));
		$logger->info('========= GIT OUTPUT START =========');

		try {
			$this->cloneRepository($repository, new Logger\LoggerWrapper($logger));
		} catch (SixtyEightPublishers\PackageInstaller\Exception\HostKeyVerificationFailedException $e) {
			$logger->info('========= GIT OUTPUT END =========');
			$logger->info(sprintf(
				'Host %s (%s) is not between known hosts. Performing automatic registration...',
				$repository->host,
				$repository->hostIpAddress
			));

			$this->executor->execute(sprintf(
				'ssh-keygen -F %s || ssh-keyscan %s >> %s',
				$repository->host,
				$repository->host,
				SixtyEightPublishers\PackageInstaller\Helpers::concatPaths($_SERVER['HOME'] ?? '~', '.ssh/known_hosts', FALSE)
			), $logger);

			$logger->info('========= GIT OUTPUT START =========');

			$this->cloneRepository($repository, $logger);
		}

		$logger->info('========= GIT OUTPUT END =========');
	}
}
