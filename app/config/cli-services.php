<?php

/*
 * This file contains service definitions and is intended to be loaded by
 * Symfony\Component\DependencyInjection\Loader\PhpFileLoader.
 */

use Symfony\Component\DependencyInjection\Definition;

// Service Definition template
$definition = new Definition();
$definition
    ->setAutowired(true)
    ->setAutoconfigured(true)
    ->setPublic(false)
;

// application commands (which need to be public services)
$definition->setPublic(true);
$this->registerClasses($definition, 'Realm\\Command\\', '../../src/Command');
