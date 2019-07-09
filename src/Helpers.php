<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PackageInstaller;

use Nette;

final class Helpers
{
	use Nette\StaticClass;

	/**
	 * @param string $first
	 * @param string $second
	 * @param bool   $isDir
	 *
	 * @return string
	 */
	public static function concatPaths(string $first, string $second, bool $isDir = TRUE): string
	{
		return sprintf(
			'%s/%s%s',
			rtrim($first, '\\/'),
			trim($second, '\\/'),
			TRUE === $isDir ? '/' : ''
		);
	}
}
