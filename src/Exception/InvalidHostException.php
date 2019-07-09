<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PackageInstaller\Exception;

final class InvalidHostException extends InstallationException
{
	/**
	 * @param string $url
	 *
	 * @return \SixtyEightPublishers\PackageInstaller\Exception\InvalidHostException
	 */
	public static function canNotResolveHost(string $url): self
	{
		return new static(sprintf(
			'Can not resolve host from URL "%s"',
			$url
		));
	}

	/**
	 * @param string $host
	 *
	 * @return \SixtyEightPublishers\PackageInstaller\Exception\InvalidHostException
	 */
	public static function canNotResolveIpAddress(string $host): self
	{
		return new static(sprintf(
			'Can not resolve IP address for "%s"',
			$host
		));
	}
}
