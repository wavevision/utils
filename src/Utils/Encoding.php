<?php declare (strict_types = 1);

namespace Wavevision\Utils;

use Nette\StaticClass;

class Encoding
{

	use StaticClass;

	public const EMOJI_PATTERNS = [
		'/[\x{1F600}-\x{1F64F}]/u',
		'/[\x{1F300}-\x{1F5FF}]/u',
		'/[\x{1F680}-\x{1F6FF}]/u',
		'/[\x{2600}-\x{26FF}]/u',
		'/[\x{2700}-\x{27BF}]/u',
	];

	public const LATIN = 'ISO-8859-2';

	public const UTF = 'UTF-8';

	// phpcs:disable
	public const UTF_2_WIN_PATTERN = '/[^\x00-\x7F\xa0\xa4\xa6-\xa9\xab-\xae\xb0\xb1\xb4-\xb8\xbb\xc1\xc2\xc4\xc7\xc9\xcb\xcd\xce\xd3\xd4\xd6\xd7\xda\xdc\xdd\xdf\xe1\xe2\xe4\xe7\xe9\xeb\xed\xee\xf3\xf4\xf6\xf7\xfa\xfc\xfd\x{102}-\x{107}\x{10c}-\x{111}\x{118}-\x{11b}\x{139}\x{13a}\x{13d}\x{13e}\x{141}-\x{144}\x{147}\x{148}\x{150}\x{151}\x{154}\x{155}\x{158}-\x{15b}\x{15e}-\x{165}\x{16e}-\x{171}\x{179}-\x{17e}\x{2c7}\x{2d8}\x{2d9}\x{2db}\x{2dd}\x{2013}\x{2014}\x{2018}-\x{201a}\x{201c}-\x{201e}\x{2020}-\x{2022}\x{2026}\x{2030}\x{2039}\x{203a}\x{20ac}\x{2122}]/u';

	// phpcs:enable
	public const UTF_2_WIN_TABLE = [
		"\xe2\x82\xac" => "\x80",
		"\xe2\x80\x9a" => "\x82",
		"\xe2\x80\x9e" => "\x84",
		"\xe2\x80\xa6" => "\x85",
		"\xe2\x80\xa0" => "\x86",
		"\xe2\x80\xa1" => "\x87",
		"\xe2\x80\xb0" => "\x89",
		"\xc5\xa0" => "\x8a",
		"\xe2\x80\xb9" => "\x8b",
		"\xc5\x9a" => "\x8c",
		"\xc5\xa4" => "\x8d",
		"\xc5\xbd" => "\x8e",
		"\xc5\xb9" => "\x8f",
		"\xe2\x80\x98" => "\x91",
		"\xe2\x80\x99" => "\x92",
		"\xe2\x80\x9c" => "\x93",
		"\xe2\x80\x9d" => "\x94",
		"\xe2\x80\xa2" => "\x95",
		"\xe2\x80\x93" => "\x96",
		"\xe2\x80\x94" => "\x97",
		"\xe2\x84\xa2" => "\x99",
		"\xc5\xa1" => "\x9a",
		"\xe2\x80\xba" => "\x9b",
		"\xc5\x9b" => "\x9c",
		"\xc5\xa5" => "\x9d",
		"\xc5\xbe" => "\x9e",
		"\xc5\xba" => "\x9f",
		"\xc2\xa0" => "\xa0",
		"\xcb\x87" => "\xa1",
		"\xcb\x98" => "\xa2",
		"\xc5\x81" => "\xa3",
		"\xc2\xa4" => "\xa4",
		"\xc4\x84" => "\xa5",
		"\xc2\xa6" => "\xa6",
		"\xc2\xa7" => "\xa7",
		"\xc2\xa8" => "\xa8",
		"\xc2\xa9" => "\xa9",
		"\xc5\x9e" => "\xaa",
		"\xc2\xab" => "\xab",
		"\xc2\xac" => "\xac",
		"\xc2\xad" => "\xad",
		"\xc2\xae" => "\xae",
		"\xc5\xbb" => "\xaf",
		"\xc2\xb0" => "\xb0",
		"\xc2\xb1" => "\xb1",
		"\xcb\x9b" => "\xb2",
		"\xc5\x82" => "\xb3",
		"\xc2\xb4" => "\xb4",
		"\xc2\xb5" => "\xb5",
		"\xc2\xb6" => "\xb6",
		"\xc2\xb7" => "\xb7",
		"\xc2\xb8" => "\xb8",
		"\xc4\x85" => "\xb9",
		"\xc5\x9f" => "\xba",
		"\xc2\xbb" => "\xbb",
		"\xc4\xbd" => "\xbc",
		"\xcb\x9d" => "\xbd",
		"\xc4\xbe" => "\xbe",
		"\xc5\xbc" => "\xbf",
		"\xc5\x94" => "\xc0",
		"\xc3\x81" => "\xc1",
		"\xc3\x82" => "\xc2",
		"\xc4\x82" => "\xc3",
		"\xc3\x84" => "\xc4",
		"\xc4\xb9" => "\xc5",
		"\xc4\x86" => "\xc6",
		"\xc3\x87" => "\xc7",
		"\xc4\x8c" => "\xc8",
		"\xc3\x89" => "\xc9",
		"\xc4\x98" => "\xca",
		"\xc3\x8b" => "\xcb",
		"\xc4\x9a" => "\xcc",
		"\xc3\x8d" => "\xcd",
		"\xc3\x8e" => "\xce",
		"\xc4\x8e" => "\xcf",
		"\xc4\x90" => "\xd0",
		"\xc5\x83" => "\xd1",
		"\xc5\x87" => "\xd2",
		"\xc3\x93" => "\xd3",
		"\xc3\x94" => "\xd4",
		"\xc5\x90" => "\xd5",
		"\xc3\x96" => "\xd6",
		"\xc3\x97" => "\xd7",
		"\xc5\x98" => "\xd8",
		"\xc5\xae" => "\xd9",
		"\xc3\x9a" => "\xda",
		"\xc5\xb0" => "\xdb",
		"\xc3\x9c" => "\xdc",
		"\xc3\x9d" => "\xdd",
		"\xc5\xa2" => "\xde",
		"\xc3\x9f" => "\xdf",
		"\xc5\x95" => "\xe0",
		"\xc3\xa1" => "\xe1",
		"\xc3\xa2" => "\xe2",
		"\xc4\x83" => "\xe3",
		"\xc3\xa4" => "\xe4",
		"\xc4\xba" => "\xe5",
		"\xc4\x87" => "\xe6",
		"\xc3\xa7" => "\xe7",
		"\xc4\x8d" => "\xe8",
		"\xc3\xa9" => "\xe9",
		"\xc4\x99" => "\xea",
		"\xc3\xab" => "\xeb",
		"\xc4\x9b" => "\xec",
		"\xc3\xad" => "\xed",
		"\xc3\xae" => "\xee",
		"\xc4\x8f" => "\xef",
		"\xc4\x91" => "\xf0",
		"\xc5\x84" => "\xf1",
		"\xc5\x88" => "\xf2",
		"\xc3\xb3" => "\xf3",
		"\xc3\xb4" => "\xf4",
		"\xc5\x91" => "\xf5",
		"\xc3\xb6" => "\xf6",
		"\xc3\xb7" => "\xf7",
		"\xc5\x99" => "\xf8",
		"\xc5\xaf" => "\xf9",
		"\xc3\xba" => "\xfa",
		"\xc5\xb1" => "\xfb",
		"\xc3\xbc" => "\xfc",
		"\xc3\xbd" => "\xfd",
		"\xc5\xa3" => "\xfe",
		"\xcb\x99" => "\xff",
	];

	public const UTF_PATTERN = '/[\x80-\x{1FF}\x{2000}-\x{3FFF}]/u';

	public const WIN_PATTERN = '/[\x7F-\x9F\xBC]/';

	public const WINDOWS_1250 = 'WINDOWS-1250';

}
