<?php declare(strict_types = 1);

namespace Wavevision\Utils;

use Nette\SmartObject;
use function error_get_last;
use function fclose;
use function fgetcsv;
use function fopen;
use function fputcsv;
use function fwrite;
use function rewind;
use function stream_get_contents;

class File
{

	use SmartObject;

	/**
	 * @var resource
	 */
	private $resource;

	/**
	 * @param resource $resource
	 */
	private function __construct($resource)
	{
		$this->resource = $resource;
	}

	public static function open(string $filename, string $mode): self
	{
		$resource = @fopen($filename, $mode);
		if ($resource) {
			return new self($resource);
		}
		throw new InvalidState(self::getLastError());
	}

	/**
	 * @param array<mixed> $fields
	 */
	public function putCsv(
		array $fields,
		string $delimiter = ",",
		string $enclosure = '"',
		string $escape_char = "\\"
	): int {
		$result = @fputcsv($this->resource, $fields, $delimiter, $enclosure, $escape_char);
		if ($result === false) {
			throw new InvalidState(self::getLastError());
		}
		return $result;
	}

	/**
	 * @return array<mixed>|null
	 */
	public function getCsv(
		int $length = 0,
		string $delimiter = ',',
		string $enclosure = '"',
		string $escape = '\\'
	): ?array {
		$result = fgetcsv($this->resource, $length, $delimiter, $enclosure, $escape);
		if ($result) {
			return $result;
		}
		return null;
	}

	public function rewind(): void
	{
		rewind($this->resource);
	}

	public function write(string $content): int
	{
		$result = @fwrite($this->resource, $content);
		if ($result === false) {
			throw new InvalidState(self::getLastError());
		}
		return $result;
	}

	public function getContents(): string
	{
		$result = @stream_get_contents($this->resource);
		if ($result === false) {
			throw new InvalidState(self::getLastError());
		}
		return $result;
	}

	public function close(): void
	{
		fclose($this->resource);
	}

	private static function getLastError(): string
	{
		/** @var array<mixed> $error */
		$error = error_get_last();
		return $error['message'];
	}

}
