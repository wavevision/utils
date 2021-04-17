<p align="center"><a href="https://github.com/wavevision"><img alt="Wavevision s.r.o." src="https://wavevision.com/images/wavevision-logo.png" width="120" /></a></p>
<h1 align="center">Utils</h1>

[![CI](https://github.com/wavevision/utils/workflows/CI/badge.svg)](https://github.com/wavevision/utils/actions/workflows/ci.yml)
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
- [ContentTypes](./src/Utils/ContentTypes.php) – format extensions and filenames for content types
- [DOM](./src/Utils/DOM) – create and format data attributes for HTML elements
- [ExternalProgram](./src/Utils/ExternalProgram/Executor.php) – simple external command runner
- [FileInfo](./src/Utils/FileInfo.php) – get file info (basename, dirname, extension etc.)
- [Finder](./src/Utils/Finder.php) – adds sorting to [nette/finder](https://github.com/nette/finder)
- [ImageInfo](./src/Utils/ImageInfo.php) – get image content type and size
- [ImmutableObject](./src/Utils/ImmutableObject.php) – combines `Nette\SmartObject` and `withMutation` helper
- [Json](./src/Utils/Json.php) – JSON pretty encoder with PHP and JavaScript indents
- [Objects](./src/Utils/Objects.php) – dynamic get / set, get namespace, classname etc.
- [Path](./src/Utils/Path.php) – join path parts, stringify path object etc.
- [SerialNumber](./src/Utils/SerialNumber.php) – generate serial numbers from year and custom numbers
- [Server](./src/Utils/Server.php) – access some useful server info (e.g. file upload limit)
- [Strings](./src/Utils/Strings.php) – string helpers (encode, transform etc.)
- [Tokenizer](./src/Utils/Tokenizer/Tokenizer.php) – get structure from file (e.g. a class)
- [Validators](./src/Utils/Validators.php) – validate Czech and Slovak numbers (phone, personal, business)
- [Zip](./src/Utils/Zip) – simple ZIP archive helper (compress, extract)
