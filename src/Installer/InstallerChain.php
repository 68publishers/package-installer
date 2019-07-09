<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PackageInstaller\Installer;

use Psr;
use Nette;
use SixtyEightPublishers;

final class InstallerChain implements IInstaller
{
	use Nette\SmartObject;

	/** @var array  */
	private $installers = [];

	/**
	 * @param string                                                      $repository
	 * @param \SixtyEightPublishers\PackageInstaller\Installer\IInstaller $installer
	 *
	 * @return void
	 */
	public function addInstallerForRepository(string $repository, IInstaller $installer): void
	{
		$this->installers[$repository][] = $installer;
	}

	/****************** interface \SixtyEightPublishers\PackageInstaller\Installer\IInstaller ******************/

	/**
	 * {@inheritdoc}
	 */
	public function install(SixtyEightPublishers\PackageInstaller\Repository $repository, ?Psr\Log\LoggerInterface $logger = NULL): void
	{
		if (!isset($this->installers[$repository->name]) || 0 >= count($this->installers[$repository->name])) {
			throw new SixtyEightPublishers\PackageInstaller\Exception\InstallationException(sprintf(
				'No Installer is provided for repository "%s"',
				$repository->name
			));
		}

		$logger->info(sprintf('Running installation for the theme "%s"', $repository->name));

		/** @var \SixtyEightPublishers\PackageInstaller\Installer\IInstaller $installer */
		foreach ($this->installers[$repository->name] as $installer) {
			$installer->install($repository, $logger);
		}

		$logger->info(sprintf('SixtyEightPublishers\PackageInstaller "%s" was successfully installed.', $repository->name));
	}
}
