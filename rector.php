<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PHPUnit\CodeQuality\Rector\ClassMethod\ReplaceTestFunctionPrefixWithAttributeRector;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/lib',
        __DIR__ . '/test',
    ])
    ->withPhpVersion(PhpVersion::PHP_81)
    ->withAttributesSets()
    ->withPhpSets(php85: true)
    ->withTypeCoverageLevel(10)
    ->withPreparedSets(deadCode: true, codeQuality: true, codingStyle: true, phpunitCodeQuality: true)
    ->withImportNames(removeUnusedImports: true)
    ->withComposerBased(phpunit: true)
    ->withRules([
        ReplaceTestFunctionPrefixWithAttributeRector::class,
        DeclareStrictTypesRector::class,
    ]);
