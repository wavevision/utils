<?php declare (strict_types = 1);

namespace Wavevision\Utils\Tokenizer;

use Nette\SmartObject;
use Nette\Utils\FileSystem;
use function in_array;
use function is_array;
use function token_get_all;
use const T_NAMESPACE;
use const T_NS_SEPARATOR;
use const T_STRING;
use const T_WHITESPACE;

class Tokenizer
{

	use SmartObject;

	/**
	 * @param mixed[] $tokens
	 */
	public function getStructureNameFromFile(string $fileName, array $tokens): ?TokenizeResult
	{
		$allTokens = token_get_all(FileSystem::read($fileName));
		$result = $this->getStructure($allTokens, $tokens);
		if ($result !== null) {
			$namespace = $this->getNamespace($allTokens);
			return new TokenizeResult($result[0], $result[1], $namespace);
		}
		return null;
	}

	/**
	 * @param array<mixed> $allTokens
	 * @param array<mixed> $specifiedTokens
	 * @return array<mixed>
	 */
	private function getStructure(array $allTokens, array $specifiedTokens): ?array
	{
		$foundWhitespace = false;
		$matchedToken = null;
		foreach ($allTokens as $token) {
			if ($this->tokenMatchesOneType($token, $specifiedTokens)) {
				$matchedToken = $token[0];
			}
			if ($matchedToken) {
				if ($foundWhitespace) {
					if ($this->tokenMatchesType($token, T_STRING)) {
						return [$matchedToken, $token[1]];
					}
					if (!$this->tokenMatchesType($token, T_WHITESPACE)) {
						return null;
					}
				}
				$foundWhitespace = $this->tokenMatchesType($token, T_WHITESPACE);
			}
		}
		return null;
	}

	/**
	 * @param array<mixed> $allTokens
	 */
	private function getNamespace(array $allTokens): ?string
	{
		$namespace = null;
		$parseNamespace = false;
		foreach ($allTokens as $token) {
			if ($this->tokenMatchesType($token, T_NAMESPACE)) {
				$parseNamespace = true;
			}
			if ($parseNamespace) {
				if (is_array($token) && in_array($token[0], [T_STRING, T_NS_SEPARATOR])) {
					$namespace .= $token[1];
				} elseif ($token === ';') {
					return $namespace;
				}
			}
		}
		return null;
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
