<?php declare (strict_types = 1);

namespace Wavevision\Utils;

use Nette\SmartObject;
use Nette\Utils\FileSystem;

class Tokenizer
{

	use SmartObject;

	public function getClassNameFromFile(string $fileName): ?string
	{
		[$class] = $this->getStructureNameFromFile($fileName, [T_CLASS]);
		return $class;
	}

	/**
	 * @param string $fileName
	 * @param array<mixed> $tokens
	 * @return array<string|null>|null
	 */
	public function getStructureNameFromFile(string $fileName, array $tokens): ?array
	{
		$namespace = $structure = null;
		$parseNamespace = $parseStructure = false;
		$matchedToken = null;
		foreach (token_get_all(FileSystem::read($fileName)) as $token) {
			if ($this->tokenMatchesType($token, T_NAMESPACE)) {
				$parseNamespace = true;
			}
			if ($this->tokenMatchesOneType($token, $tokens)) {
				$matchedToken = $token[0];
				$parseStructure = true;
			}
			if ($parseNamespace) {
				$this->parseNamespace($token, $namespace, $parseNamespace);
			}
			if ($parseStructure && $this->tokenMatchesType($token, T_STRING)) {
				$structure = $token[1];
				break;
			}
		}
		if ($structure === null) {
			return null;
		}
		return [$namespace ? $namespace . '\\' . $structure : $structure, $matchedToken];
	}

	/**
	 * @param mixed $token
	 * @param string|null $namespace
	 * @param bool $parseNamespace
	 */
	private function parseNamespace($token, ?string &$namespace, bool &$parseNamespace): void
	{
		if (is_array($token) && in_array($token[0], [T_STRING, T_NS_SEPARATOR])) {
			$namespace .= $token[1];
		} elseif ($token === ';') {
			$parseNamespace = false;
		}
	}

	/**
	 * @param mixed $token
	 * @param array<int> $types
	 * @return array<mixed>|null
	 */
	private function tokenMatchesOneType($token, array $types): ?array
	{
		foreach ($types as $type) {
			if ($this->tokenMatchesType($token, $type)) {
				return $token;
			}
		}
		return null;
	}

	/**
	 * @param mixed $token
	 * @param int $type
	 * @return bool
	 */
	private function tokenMatchesType($token, int $type): bool
	{
		return is_array($token) && $token[0] === $type;
	}
}
