<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PackageInstaller\Executor;

use Psr;
use Nette;
use Symfony;
use SixtyEightPublishers\PackageInstaller;

final class SymfonyProcessExecutor implements IExecutor
{
	use Nette\SmartObject;

	/** @var string  */
	private $cwd;

	/**
	 * @param string $cwd
	 */
	public function __construct(string $cwd)
	{
		$this->cwd = $cwd;
	}

	/******************** interface \SixtyEightPublishers\PackageInstaller\Executor\SymfonyProcessExecutor ********************/

	/**
	 * {@inheritdoc}
	 */
	public function execute(string $commandLine, ?Psr\Log\LoggerInterface $logger = NULL): void
	{
		$process = Symfony\Component\Process\Process::fromShellCommandline($commandLine, $this->cwd);

		$process->setPty(TRUE);
		$process->setTimeout(NULL);

		$exitCode = $process->run(function ($_, $row) use ($logger, $process) {
			try {
				if (NULL !== $logger) {
					$logger->info($row);
				}
			} catch (\Throwable $e) {
				$process->stop();

				throw $e;
			}
		});
		if (0 !== $exitCode) {
			throw PackageInstaller\Exception\ExecutionFaultException::error($commandLine, $exitCode);
		}
	}
}
