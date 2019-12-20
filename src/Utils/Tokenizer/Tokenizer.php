<?php declare (strict_types = 1);

namespace Wavevision\Utils\Tokenizer;

use Nette\SmartObject;
use Nette\Utils\FileSystem;

class Tokenizer
{

	use SmartObject;

	/**
	 * @param mixed[] $tokens
	 */
	public function getStructureNameFromFile(string $fileName, array $tokens): ?TokenizeResult
	{
		$namespace = $structure = null;
		$parseNamespace = $parseStructure = false;
		$foundStructure = false;
		$foundWhitespace = false;
		$matchedToken = null;
		foreach (token_get_all(FileSystem::read($fileName)) as $token) {
			if ($this->tokenMatchesType($token, T_NAMESPACE)) {
				$parseNamespace = true;
			}
			if ($parseNamespace) {
				$this->parseNamespace($token, $namespace, $parseNamespace);
			}
			if ($this->tokenMatchesOneType($token, $tokens)) {
				$matchedToken = $token[0];
				$foundStructure = true;
			}
			if ($foundStructure && $foundWhitespace && $this->tokenMatchesType($token, T_STRING)) {
				$structure = $token[1];
				break;
			}
			$foundWhitespace = $this->tokenMatchesType($token, T_WHITESPACE);
		}
		if ($structure === null) {
			return null;
		}
		return new TokenizeResult((int)$matchedToken, (string)$structure, $namespace);
	}

	/**
	 * @param mixed $token
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
	 */
	private function tokenMatchesType($token, int $type): bool
	{
		return is_array($token) && $token[0] === $type;
	}

}
