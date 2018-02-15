<?php

/*
 * This file contains service definitions and is intended to be loaded by
 * Symfony\Component\DependencyInjection\Loader\PhpFileLoader.
 */

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

use Realm\Command\TerminologyEditorCommand;

// Service Definition template
$definition = new Definition();
$definition
    ->setAutowired(true)
    ->setAutoconfigured(true)
    ->setPublic(false)
;

// container params
$container->setParameter('default_terminology_editor_language_code', 'nl_NL');
$container->setParameter(
    'contextual_concept_attributes',
    [
        'shortName',
        'oid',
        'type',
        'status',
        'codelist.displayName',
        'codelist.id',
    ]
);

// libs
$container->register(PropertyAccessor::class, PropertyAccessor::class)
    ->setFactory([PropertyAccess::class, 'createPropertyAccessor'])
;

// application services
$this->registerClasses($definition, 'Realm\\Loader\\', '../../src/Loader');

// application commands (which need to be public services)
$definition->setPublic(true);
$this->registerClasses($definition, 'Realm\\Command\\', '../../src/Command');
$container
    ->getDefinition(TerminologyEditorCommand::class)
    ->setArgument('$defaultLanguageCode', '%default_terminology_editor_language_code%')
    ->setArgument('$contextualConceptAttributes', '%contextual_concept_attributes%')
;
