# gsu-cetloe/symfony-bundle

Common dependencies for Symfony bundles.

## What it does

This package contains no PHP code of its own. It exists to pin one agreed-upon
set of Symfony 6.4 components so that every bundle built on it shares the same
foundation. Requiring this package in a bundle pulls in:

| Package                        | Version | Provides                                   |
| ------------------------------ | ------- | ------------------------------------------ |
| `symfony/config`               | 6.4.*   | Configuration tree definitions and loaders |
| `symfony/dependency-injection` | 6.4.*   | Service container and compiler passes      |
| `symfony/http-kernel`          | 6.4.*   | The `Bundle` base class and kernel events  |
| `symfony/yaml`                 | 6.4.*   | YAML parsing for service and config files  |

When a Symfony version bump is needed, change it here once and every dependent
bundle picks it up on its next `composer update`.

Requires PHP 8.1 or newer.

## Using it in a bundle

Add the package to your bundle's `composer.json`:

```bash
composer require gsu-cetloe/symfony-bundle
```

If the package is not available from Packagist in your environment, point
Composer at the GitHub repository first:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/GSU-CETLOE/symfony-bundle"
        }
    ]
}
```

Your bundle then extends the Symfony base class as usual. No code from this
package needs to be referenced directly:

```php
<?php

namespace Acme\ExampleBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

final class AcmeExampleBundle extends Bundle
{
}
```

## Setup for development

1. Install [Composer](https://getcomposer.org/) if you do not already have it.
2. Clone this repository and install dependencies:

   ```bash
   git clone https://github.com/GSU-CETLOE/symfony-bundle.git
   cd symfony-bundle
   composer install
   ```

The lock file is resolved against a PHP 8.1 platform so that it matches the
lowest PHP version this package supports, regardless of the PHP version on the
machine running Composer.

## Checks

| Command            | What it does                                                 |
| ------------------ | ------------------------------------------------------------ |
| `composer test`    | Runs the PHPUnit suite                                       |
| `composer phpstan` | Runs PHPStan static analysis at level 9                      |
| `composer phpcs`   | Checks coding standards (PSR-12) with PHP_CodeSniffer        |
| `composer check`   | Runs phpcs, PHPStan, and the test suite in that order        |

Examples:

```bash
composer check
composer test -- --filter testRequiredComponentIsAutoloadable
```

Caches and other generated files go in the `working/` directory, which is
ignored by git.

## Tests

Because the package ships no code, the tests verify its dependency contract
instead. For each required Symfony package they check that it is declared in
`composer.json`, installed, at a 6.4 release, and autoloadable. A guard test
fails if a package is added to `composer.json` without a matching smoke test.
See [TESTPLAN.md](TESTPLAN.md) for the manual walkthrough.

## License

MIT. See [LICENCE](LICENCE).
