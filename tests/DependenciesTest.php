<?php

declare(strict_types=1);

namespace GsuCetloe\SymfonyBundle\Tests;

use Composer\InstalledVersions;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Yaml\Yaml;

/**
 * This package ships no code of its own. Its whole contract is the set of
 * Symfony components it pulls in, so these tests verify that contract:
 * every required component is declared, installed at the promised major
 * and minor version, and autoloadable.
 */
final class DependenciesTest extends TestCase
{
    private const SUPPORTED_SYMFONY_VERSION_PREFIX = '6.4.';

    /**
     * Maps each required Symfony package to one class it is expected to provide.
     *
     * @return \Generator<string, array{string, class-string}>
     */
    public static function providesRequiredComponents(): \Generator
    {
        yield 'symfony/config' => ['symfony/config', TreeBuilder::class];
        yield 'symfony/dependency-injection' => ['symfony/dependency-injection', ContainerBuilder::class];
        yield 'symfony/http-kernel' => ['symfony/http-kernel', Bundle::class];
        yield 'symfony/yaml' => ['symfony/yaml', Yaml::class];
    }

    /**
     * Guards against a package being added to composer.json without a matching smoke test.
     */
    public function testEveryRequiredPackageHasASmokeTest(): void
    {
        $composerJson = file_get_contents(__DIR__ . '/../composer.json');
        self::assertNotFalse($composerJson, 'composer.json must be readable');

        /** @var array{require: array<string, string>} $composer */
        $composer = json_decode($composerJson, true, 512, JSON_THROW_ON_ERROR);

        $requiredPackages = array_keys($composer['require']);
        $requiredPackages = array_values(array_filter(
            $requiredPackages,
            static fn (string $package): bool => $package !== 'php'
        ));
        sort($requiredPackages);

        $testedPackages = array_keys(iterator_to_array(self::providesRequiredComponents()));
        sort($testedPackages);

        self::assertSame($requiredPackages, $testedPackages);
    }

    #[DataProvider('providesRequiredComponents')]
    public function testRequiredComponentIsInstalled(string $package): void
    {
        self::assertTrue(
            InstalledVersions::isInstalled($package),
            sprintf('%s should be installed', $package)
        );
    }

    #[DataProvider('providesRequiredComponents')]
    public function testRequiredComponentIsAtSupportedVersion(string $package): void
    {
        $installedVersion = InstalledVersions::getPrettyVersion($package);

        self::assertNotNull($installedVersion, sprintf('%s should report a version', $package));
        self::assertStringStartsWith(
            'v' . self::SUPPORTED_SYMFONY_VERSION_PREFIX,
            $installedVersion,
            sprintf('%s should be a %s release', $package, self::SUPPORTED_SYMFONY_VERSION_PREFIX . '*')
        );
    }

    /**
     * @param class-string $className
     */
    #[DataProvider('providesRequiredComponents')]
    public function testRequiredComponentIsAutoloadable(string $package, string $className): void
    {
        self::assertTrue(
            class_exists($className),
            sprintf('%s should provide %s', $package, $className)
        );
    }
}
