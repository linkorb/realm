<?php

namespace Realm\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;

use Realm\Loader\XmlRealmLoader;
use Realm\Model\Project;

class TerminologyEditorCommand extends Command
{
    protected static $defaultName = 'terminology:editor';

    private $contextualConceptAttributes;
    private $defaultLanguageCode;
    private $propertyAccessor;
    private $realmLoader;

    public function __construct(
        XmlRealmLoader $realmLoader,
        PropertyAccessor $propertyAccessor,
        $defaultLanguageCode,
        $contextualConceptAttributes,
        $name = null
    ) {
        $this->realmLoader = $realmLoader;
        $this->propertyAccessor = $propertyAccessor;
        $this->defaultLanguageCode = $defaultLanguageCode;
        $this->contextualConceptAttributes = $contextualConceptAttributes;
        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Prepare a file of terminilogy for editors')
            ->addArgument(
                'realm',
                InputArgument::REQUIRED,
                'The name of a realm project (e.g. "peri22xx") which will be located immediately below REALM_PATH'
            )
            ->addArgument(
                'property',
                InputArgument::REQUIRED,
                'The name of a concept property (either existing or not) to be edited'
            )
            ->addOption(
                'language',
                'l',
                InputOption::VALUE_OPTIONAL,
                'Select properties in the given language',
                $this->defaultLanguageCode
            )
            ->addOption(
                'context',
                'c',
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'The name of a concept property which provides context to assist with editing'
            )
            ->addOption(
                'csv',
                null,
                InputOption::VALUE_NONE,
                'Output comma separated values instead of tab separated values'
            )
        ;
        if (false !== $dir = getcwd()) {
            $this->addArgument(
                'path',
                InputArgument::OPTIONAL,
                'Path to which to write the terminology file (use "-" for stdout)',
                $dir
            );
        } else {
            $this->addArgument(
                'path',
                InputArgument::REQUIRED,
                'Path to which to write the terminology file (use "-" for stdout)'
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $propertyToEdit = $input->getArgument('property');
        $editorLanguage = $input->getOption('language');
        $outputToStdOut = '-' === $input->getArgument('path');
        $outputFileExtention = 'tsv';
        $outputDelimiter = "\t";

        if ($input->getOption('csv')) {
            $outputFileExtention = 'csv';
            $outputDelimiter = ',';
        }
        $fileHandle = null;
        if (!$outputToStdOut) {
            $fileHandle = fopen(
                "{$input->getArgument('path')}/{$propertyToEdit}.{$editorLanguage}.{$outputFileExtention}",
                'wb'
            );
        } else {
            $fileHandle = fopen('php://stdout', 'wb');
        }

        $project = new Project();
        $this->realmLoader->load($input->getArgument('realm'), $project);

        $header = ['CONCEPT', strtoupper($propertyToEdit)];
        foreach ($input->getOption('context') as $propertyName) {
            $header[] = strtoupper($propertyName);
        }
        foreach ($this->contextualConceptAttributes as $attrName) {
            $header[] = strtoupper($attrName);
        }
        fputcsv($fileHandle, $header, $outputDelimiter);

        foreach ($project->getConcepts() as $id => $concept) {
            $row = [$id];
            if ($concept->hasProperty($propertyToEdit, $editorLanguage)) {
                $row[] = $concept->getPropertyValue($propertyToEdit, $editorLanguage);
            } else {
                $row[] = '';
            }
            foreach ($input->getOption('context') as $propertyName) {
                if ($concept->hasProperty($propertyName, $editorLanguage)) {
                    $row[] = $concept->getPropertyValue($propertyName, $editorLanguage);
                } else {
                    $row[] = '';
                }
            }
            foreach ($this->contextualConceptAttributes as $attrPath) {
                if (!$this->propertyAccessor->isReadable($concept, $attrPath)) {
                    $row[] = '';
                    continue;
                }
                $attrValue = $this->propertyAccessor->getValue($concept, $attrPath);
                $row[] = null === $attrValue ? '' : $attrValue;
            }
            fputcsv($fileHandle, $row, $outputDelimiter);
        }

        if (!$outputToStdOut) {
            fclose($fileHandle);
        }
        return 0;
    }
}
