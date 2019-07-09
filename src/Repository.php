<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PackageInstaller;

use Nette;
use SixtyEightPublishers;

/**
 * @property-read string $name
 * @property-read string $repositoryUrl
 * @property-read string $absolutePath
 * @property-read string $absoluteDistPath
 * @property-read string $relativePath
 * @property-read string $relativeDistPath
 * @property-read string $host
 * @property-read string $hostIpAddress
 * @property-read string $branch
 */
final class Repository
{
	use Nette\SmartObject;

	const 	DEFAULT_BRANCH = 'master';

	/** @var string  */
	private $name;

	/** @var string  */
	private $repositoryUrl;

	/** @var string  */
	private $cwd;

	/** @var string  */
	private $repositoryPath;

	/** @var string  */
	private $distPath;

	/** @var string  */
	private $branch = self::DEFAULT_BRANCH;

	/** @var NULL|string */
	private $host;

	/**
	 * @param string $name
	 * @param string $repositoryUrl
	 * @param string $cwd
	 * @param string $repositoryPath
	 * @param string $distPath
	 */
	public function __construct(string $name, string $repositoryUrl, string $cwd, string $repositoryPath, string $distPath)
	{
		$this->name = $name;
		$this->repositoryUrl = $repositoryUrl;
		$this->cwd = $cwd;
		$this->repositoryPath = $repositoryPath;
		$this->distPath = $distPath;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getRepositoryUrl(): string
	{
		return $this->repositoryUrl;
	}

	/**
	 * @return string
	 */
	public function getAbsolutePath(): string
	{
		return Helpers::concatPaths($this->cwd, $this->repositoryPath);
	}

	/**
	 * @return string
	 */
	public function getAbsoluteDistPath(): string
	{
		return Helpers::concatPaths($this->cwd, $this->distPath);
	}

	/**
	 * @return string
	 */
	public function getRelativePath(): string
	{
		return $this->repositoryPath;
	}

	/**
	 * @return string
	 */
	public function getRelativeDistPath(): string
	{
		return $this->distPath;
	}

	/**
	 * @return string
	 * @throws \SixtyEightPublishers\PackageInstaller\Exception\InvalidHostException
	 */
	public function getHost(): string
	{
		if (NULL === $this->host) {
			$url = new Nette\Http\Url($this->repositoryUrl);

			if (empty($url->host)) {
				preg_match('/^(.+\@)(?<HOST>.+\.[a-z]+)(\:.+\.git)$/', $this->repositoryUrl, $matches);

				if (!isset($matches['HOST'])) {
					throw SixtyEightPublishers\PackageInstaller\Exception\InvalidHostException::canNotResolveHost($this->repositoryUrl);
				}

				$this->host = $matches['HOST'];
			} else {
				$this->host = $url->host;
			}
		}

		return $this->host;
	}

	/**
	 * @return string
	 * @throws \SixtyEightPublishers\PackageInstaller\Exception\InvalidHostException
	 */
	public function getHostIpAddress(): string
	{
		if ($this->host === ($ip = gethostbyname($this->host))) {
			throw SixtyEightPublishers\PackageInstaller\Exception\InvalidHostException::canNotResolveIpAddress($this->host);
		}

		return $ip;
	}

	/**
	 * @return string
	 */
	public function getBranch(): string
	{
		return $this->branch;
	}

	/**
	 * @param string $branch
	 *
	 * @return void
	 */
	public function setBranch(string $branch): void
	{
		$this->branch = $branch;
	}
}
