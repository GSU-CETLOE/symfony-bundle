# Test Plan

Walk through this list before tagging a release or after changing
`composer.json`. Run every command from the repository root.

## Setup

- [ ] `composer install` completes without errors.
- [ ] `composer validate` reports that `composer.json` is valid and the lock file is up to date.

## Automated checks

- [ ] `composer phpcs` reports no coding standard violations.
- [ ] `composer phpstan` reports no errors at level 9.
- [ ] `composer test` passes with no warnings, notices, or deprecations in the output.
- [ ] `composer check` runs all three in order and exits 0.

## Dependency contract

- [ ] Every package under `require` in `composer.json` (other than `php`) appears in `providesRequiredComponents()` in `tests/DependenciesTest.php`.
- [ ] `composer show symfony/*` lists only 6.4.x releases for the four required components.
- [ ] `composer audit` reports no security advisories.

## Consuming the package

- [ ] In a scratch Symfony bundle project, `composer require gsu-cetloe/symfony-bundle` installs the four Symfony components.
- [ ] A class extending `Symfony\Component\HttpKernel\Bundle\Bundle` autoloads in that project.

## Housekeeping

- [ ] `git status` shows nothing under `working/` or `vendor/` as untracked.
- [ ] README.md command table matches the `scripts` block in `composer.json`.
