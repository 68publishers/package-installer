<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PackageInstaller\Exception;

final class RepositoryException extends \Exception implements IException
{
	/**
	 * @param string $name
	 *
	 * @return \SixtyEightPublishers\PackageInstaller\Exception\RepositoryException
	 */
	public static function missingRepository(string $name): self
	{
		return new static(sprintf(
			'Missing repository (theme) with name "%s".',
			$name
		));
	}
}
