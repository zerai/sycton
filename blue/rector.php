<?php declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Symfony\Set\SymfonySetList;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $rectorConfig->phpVersion(PhpVersion::PHP_81);
    $rectorConfig->symfonyContainerXml(__DIR__ . '/var/cache/dev/App_KernelDevDebugContainer.xml');

    // define sets of rules
    $rectorConfig->sets([
        /**
         * PHP
         */
        LevelSetList::UP_TO_PHP_81,

        /**
         * SYMFONY
         */
        SymfonySetList::SYMFONY_62,
        SymfonySetList::SYMFONY_CODE_QUALITY,
        /**
         * enable SymfonyLevelSetList::UP_TO_* only on transition
         * @see https://getrector.com/blog/5-common-mistakes-in-rector-config-and-how-to-avoid-them
         */
        //SymfonyLevelSetList::UP_TO_SYMFONY_62,


        /**
         * DOCTRINE
         */
        DoctrineSetList::DOCTRINE_DBAL_30,
        DoctrineSetList::DOCTRINE_ORM_29,
        DoctrineSetList::DOCTRINE_CODE_QUALITY,

        /**
         * PHPUNIT
         */
        PHPUnitSetList::PHPUNIT_91,
        /**
         * enable PHPUnitLevelSetList::UP_TO_* only on transition
         * @see https://getrector.com/blog/5-common-mistakes-in-rector-config-and-how-to-avoid-them
         */
        //PHPUnitLevelSetList::UP_TO_PHPUNIT_90

    ]);
    // register a single rule
    //$rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);
};
