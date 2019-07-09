<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PackageInstaller\Installer;

use Psr;
use SixtyEightPublishers\PackageInstaller;

interface IInstaller
{
	/**
	 * @param \SixtyEightPublishers\PackageInstaller\Repository $repository
	 * @param \Psr\Log\LoggerInterface|NULL                     $logger
	 *
	 * @return void
	 * @throws \SixtyEightPublishers\PackageInstaller\Exception\InstallationException
	 */
	public function install(PackageInstaller\Repository $repository, ?Psr\Log\LoggerInterface $logger = NULL): void;
}
