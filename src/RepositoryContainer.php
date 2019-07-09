<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PackageInstaller;

use Nette;
use SixtyEightPublishers;

final class RepositoryContainer implements \IteratorAggregate, \Countable
{
	use Nette\SmartObject;

	/** @var string  */
	private $cwd;

	/** @var string  */
	private $repositoryPath;

	/** @var string  */
	private $distPath;

	/** @var \SixtyEightPublishers\PackageInstaller\Repository[]  */
	private $repositories = [];

	/**
	 * @param string $cwd
	 * @param string $repositoryPath
	 * @param string $distPath
	 */
	public function __construct(string $cwd, string $repositoryPath, string $distPath)
	{
		$this->cwd = $cwd;
		$this->repositoryPath = $repositoryPath;
		$this->distPath = $distPath;
	}

	/**
	 * @param string      $name
	 * @param string      $repositoryUrl
	 * @param NULL|string $branch
	 *
	 * @return void
	 */
	public function addRepository(string $name, string $repositoryUrl, ?string $branch = NULL): void
	{
		$this->repositories[] = $repository = new Repository(
			$name,
			$repositoryUrl,
			$this->cwd,
			Helpers::concatPaths($this->repositoryPath, $name),
			Helpers::concatPaths($this->distPath, $name)
		);

		if (NULl !== $branch) {
			$repository->setBranch($branch);
		}
	}

	/**
	 * @return \SixtyEightPublishers\PackageInstaller\Repository[]
	 */
	public function getRepositories(): array
	{
		return $this->repositories;
	}

	/**
	 * @param string $name
	 *
	 * @return \SixtyEightPublishers\PackageInstaller\Repository
	 * @throws \SixtyEightPublishers\PackageInstaller\Exception\RepositoryException
	 */
	public function findRepository(string $name): Repository
	{
		foreach ($this->getRepositories() as $repository) {
			if ($repository->getName() === $name) {
				return $repository;
			}
		}

		throw SixtyEightPublishers\PackageInstaller\Exception\RepositoryException::missingRepository($name);
	}

	/******************** interface \IteratorAggregate ********************/

	/**
	 * {@inheritdoc}
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->getRepositories());
	}

	/******************** interface \Countable ********************/

	/**
	 * {@inheritdoc}
	 */
	public function count()
	{
		return count($this->repositories);
	}
}
