<?php

namespace Realm\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Realm\Loader\XmlRealmLoader;
use Realm\Loader\SpreadsheetLoader;
use Realm\Model\Resource;
use Realm\Model\ResourceSection;
use Realm\Model\Value;
use Realm\Writer\ResourceXmlWriter;

use RuntimeException;

class SpreadsheetExampleCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->ignoreValidationErrors();

        $this
            ->setName('spreadsheet:example')
            ->setDescription('Parse spreadsheet and output resource example')
            ->addOption(
                'filename',
                'f',
                InputOption::VALUE_REQUIRED,
                null
            )
            ->addOption(
                'realm',
                'r',
                InputOption::VALUE_REQUIRED,
                null
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getOption('filename');
        if (!$filename) {
            throw new RuntimeException('Please specify import .tsv');
        }

        $realmId = $input->getOption('realm');
        if (!$realmId) {
            throw new RuntimeException('Please pass a realm as context for this spreadsheet');
        }
        $realmLoader = new XmlRealmLoader();
        $realm = $realmLoader->load($realmId);

        $output->writeLn('Loading spreadsheet tsv file: ' . $filename);
        $loader = new SpreadsheetLoader();
        $rows = $loader->load($filename);
        //print_r($rows);

        $resource = new Resource();
        foreach ($rows as $row) {
            if (($row['concept'] != '') && ($row['concept'][0] != '_')) {
                $sectionId = $row['section_id'];
                $conceptId = trim(strtolower($row['concept']));
                if (!$realm->hasConcept($conceptId)) {
                    throw new RuntimeException('Unknown conceptId: ' . $conceptId);
                }
                $concept = $realm->getConcept($conceptId);
                $exampleValue = trim($row['example_value']);
                if ($resource->hasSection($sectionId)) {
                    $section = $resource->getSection($sectionId);
                } else {
                    $section = new ResourceSection();
                    $section->setId($sectionId);
                    $resource->addSection($section);

                    $sectionTypeId = trim(strtolower($row['section_type']));
                    if (!$realm->hasSectionType($sectionTypeId)) {
                        throw new RuntimeException('Unknown sectionTypeId: ' . $sectionTypeId);
                    }
                    $sectionType = $realm->getSectionType($sectionTypeId);
                    $section->setType($sectionType);
                }
                $value = new Value();
                $value->setConcept($concept);
                $value->setValue($exampleValue);
                $value->setLabel($row['label']);
                $section->addValue($value);
            }
        }
        //print_r($resource);
        $writer = new ResourceXmlWriter();
        $xml = $writer->write($resource);
        exit($xml);
    }
}
