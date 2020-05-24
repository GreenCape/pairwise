# Pairwise test case generator

Inspired by Java implementation https://github.com/RetailMeNot/pairwise. 

## Installation

With [Composer](https://getcomposer.org):

```bash
composer require richenzi/pairwise
```

## Usage

### From input

Test cases can be generated using direct input

```php
$testCases = Pairwise::fromData([
    'browser' => ['Chrome', 'Firefox', 'Opera', 'Safari', 'IE'],
    'os' => ['Windows', 'Ubuntu', 'Debian', 'MacOS'],
    'connectivity' => ['Wi-Fi', 'LTE', '3G', '4G', '5G']
])->generate();
```

or passing file path with data in correct format

```php
$testCases = Pairwise::fromFile('/path/to/file')->generate();
```

## License

This package is licensed under [The MIT License (MIT)](LICENSE).
