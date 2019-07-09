<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PackageInstaller\Exception;

final class HostKeyVerificationFailedException extends InstallationException
{
	/**
	 * @return \SixtyEightPublishers\PackageInstaller\Exception\HostKeyVerificationFailedException
	 */
	public static function error(): self
	{
		return new static('Host is not verified! Please add current host between SSH\'s "known_hosts"');
	}
}
