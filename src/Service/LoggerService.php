<?php
declare(strict_types=1);

namespace App\Service;

use Monolog\Logger;

/**
 * Class LoggerService
 * @package App\Service
 */
final class LoggerService
{
	/**
 	 * @var Logger
	 */
	private $logger;

	/**
	 * LoggerService constructor.
	 * @param Logger $logger
	 */
	public function __construct(Logger $logger)
	{
		$this->logger = $logger;
	}

	public function add($level, $message)
	{
		$this->logger->$level($message);
	}
}
