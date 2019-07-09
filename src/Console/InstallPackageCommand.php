<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PackageInstaller\Console;

use Nette;
use Symfony;
use SixtyEightPublishers;

final class InstallPackageCommand extends Symfony\Component\Console\Command\Command
{
	use Nette\SmartObject;

	/** @var \SixtyEightPublishers\PackageInstaller\RepositoryContainer  */
	private $repositoryContainer;

	/** @var \SixtyEightPublishers\PackageInstaller\Installer\IInstaller  */
	private $installer;

	/**
	 * @param \SixtyEightPublishers\PackageInstaller\RepositoryContainer  $repositoryContainer
	 * @param \SixtyEightPublishers\PackageInstaller\Installer\IInstaller $installer
	 */
	public function __construct(SixtyEightPublishers\PackageInstaller\RepositoryContainer $repositoryContainer, SixtyEightPublishers\PackageInstaller\Installer\IInstaller $installer)
	{
		parent::__construct();

		$this->repositoryContainer = $repositoryContainer;
		$this->installer = $installer;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function configure(): void
	{
		$this->setName('package:install')
			->setDescription('Installs all packages defined in configuration')
			->addArgument('singleRepository', Symfony\Component\Console\Input\InputArgument::OPTIONAL, 'You can specify name of package and branch in one string separated by ":" character (eg. "my-theme", "my-theme:branch")', NULL);
	}

	/**
	 * {@inheritdoc}
	 */
	public function execute(Symfony\Component\Console\Input\InputInterface $input, Symfony\Component\Console\Output\OutputInterface $output)
	{
		$output->setVerbosity(Symfony\Component\Console\Output\OutputInterface::VERBOSITY_VERY_VERBOSE);

		$logger = new Symfony\Component\Console\Logger\ConsoleLogger($output);

		if (NULL !== ($singleRepository = $input->getArgument('singleRepository'))) {
			[$name, $branch] = explode(':', $singleRepository) + [$singleRepository, NULL];
			$repository = $this->repositoryContainer->findRepository($name);

			if (NULL !== $branch) {
				$repository->setBranch($branch);
			}

			$this->installer->install($repository, $logger);
		} else {
			/** @var \SixtyEightPublishers\PackageInstaller\Repository $repository */
			foreach ($this->repositoryContainer as $repository) {
				$this->installer->install($repository, $logger);
				$logger->info(PHP_EOL . PHP_EOL);
			}
		}
	}
}
