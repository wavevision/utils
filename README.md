# Wavevision Utils

[![Build Status](https://travis-ci.org/wavevision/utils.svg?branch=master)](https://travis-ci.org/wavevision/utils)
[![Coverage Status](https://coveralls.io/repos/github/wavevision/utils/badge.svg?branch=master)](https://coveralls.io/github/wavevision/utils?branch=master)
[![PHPStan](https://img.shields.io/badge/style-level%20max-brightgreen.svg?label=phpstan)](https://github.com/phpstan/phpstan)

Set of useful PHP utilities and helpers extending [nette/utils](https://github.com/nette/utils).

**Requirements:**

- Php 7.2

## Installation

Via [Composer](https://getcomposer.org)

```bash
composer require wavevision/utils
```

## Contents

The package contains useful classes for:

- [Arrays](./src/Utils/Arrays.php) – array helpers (manipulate, sort, extract etc.)
- [Json](./src/Utils/Json.php) – JSON pretty encoder with PHP and JavaScript indents
- [Objects](./src/Utils/Objects.php) – dynamic get / set accessors
- [Strings](./src/Utils/Strings.php) – string helpers (encode, transform etc.)
- [Tokenizer](./src/Utils/Tokenizer.php) – get structure from file (e.g. PHP class)
