<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PackageInstaller\Installer\Logger;

use Psr;
use Nette;
use SixtyEightPublishers;

final class LoggerWrapper implements Psr\Log\LoggerInterface
{
	use Psr\Log\LoggerTrait;

	const 	MESSAGE_HOST_KEY_VERIFICATION_FAILED = 'Host key verification failed';

	/** @var \Psr\Log\LoggerInterface  */
	private $logger;

	/**
	 * @param \Psr\Log\LoggerInterface $logger
	 */
	public function __construct(Psr\Log\LoggerInterface $logger)
	{
		$this->logger = $logger;
	}

	/******************** interface \Psr\Log\LoggerInterface ********************/

	/**
	 * {@inheritdoc}
	 *
	 * @throws \SixtyEightPublishers\PackageInstaller\Exception\HostKeyVerificationFailedException
	 */
	public function log($level, $message, array $context = [])
	{
		$this->logger->log($level, $message, $context);

		if (Nette\Utils\Strings::contains((string) $message, self::MESSAGE_HOST_KEY_VERIFICATION_FAILED)) {
			throw SixtyEightPublishers\PackageInstaller\Exception\HostKeyVerificationFailedException::error();
		}
	}
}
