# Wavevision Utils

[![Build Status](https://travis-ci.org/wavevision/utils.svg?branch=master)](https://travis-ci.org/wavevision/utils)
[![Coverage Status](https://coveralls.io/repos/github/wavevision/utils/badge.svg?branch=master)](https://coveralls.io/github/wavevision/utils?branch=master)
[![PHPStan](https://img.shields.io/badge/style-level%20max-brightgreen.svg?label=phpstan)](https://github.com/phpstan/phpstan)

Set of useful PHP utilities and helpers extending [nette/utils](https://github.com/nette/utils).

## Installation

Via [Composer](https://getcomposer.org)

```bash
composer require wavevision/utils
```

## Contents

The package contains useful classes for:

- [Arrays](./src/Utils/Arrays.php) – array helpers (manipulate, sort, extract etc.)
- [Finder](./src/Utils/Finder.php) – adds sorting to [nette/finder](https://github.com/nette/finder)
- [Json](./src/Utils/Json.php) – JSON pretty encoder with PHP and JavaScript indents
- [Objects](./src/Utils/Objects.php) – dynamic get / set accessors
- [Path](./src/Utils/Path.php) – join path parts
- [Server](./src/Utils/Server.php) – access some useful server info (e.g. file upload limit)
- [Strings](./src/Utils/Strings.php) – string helpers (encode, transform etc.)
- [Tokenizer](./src/Utils/Tokenizer/Tokenizer.php) – get structure from file (e.g. a class)
- [Validators](./src/Utils/Validators.php) – validate Czech and Slovak numbers (phone, personal, business)
