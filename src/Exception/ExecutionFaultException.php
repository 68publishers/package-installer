<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PackageInstaller\Exception;

final class ExecutionFaultException extends \RuntimeException implements IException
{
	/** @var string  */
	private $task;

	/** @var int  */
	private $returnCode;

	/**
	 * @param string $task
	 * @param int    $returnCode
	 */
	public function __construct(string $task, int $returnCode)
	{
		parent::__construct(sprintf(
			'Execution of process [%s] ended with error code %d',
			$task,
			$returnCode
		));

		$this->task = $task;
		$this->returnCode = $returnCode;
	}

	/**
	 * @param string $task
	 * @param int    $returnCode
	 *
	 * @return \SixtyEightPublishers\PackageInstaller\Exception\ExecutionFaultException
	 */
	public static function error(string $task, int $returnCode): self
	{
		return new static($task, $returnCode);
	}
}
