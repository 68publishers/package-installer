<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PackageInstaller\Executor;

use Psr;

interface IExecutor
{
	/**
	 * @param string                        $commandLine
	 * @param NULL|\Psr\Log\LoggerInterface $logger
	 *
	 * @return void
	 * @throws \SixtyEightPublishers\PackageInstaller\Exception\ExecutionFaultException
	 */
	public function execute(string $commandLine, ?Psr\Log\LoggerInterface $logger = NULL): void;
}
