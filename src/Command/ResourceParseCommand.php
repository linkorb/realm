<?php

namespace Realm\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Realm\Loader\XmlRealmLoader;
use Realm\Loader\XmlResourceLoader;
use Realm\Model\Project;
use Realm\Writer\ResourceXmlWriter;
use RuntimeException;

class ResourceParseCommand extends Command
{
    protected static $defaultName = 'resource:parse';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->ignoreValidationErrors();

        $this
            ->setDescription('Load realm, and output contents')
            ->addOption(
                'realm',
                'r',
                InputOption::VALUE_REQUIRED,
                null
            )
            ->addOption(
                'mode',
                'm',
                InputOption::VALUE_REQUIRED,
                'Mode',
                'pure'
            )
            ->addOption(
                'language',
                'l',
                InputOption::VALUE_REQUIRED,
                'Output language',
                'en-US'
            )
            ->addArgument(
                'filename',
                InputArgument::REQUIRED,
                'resource.xml filename'
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('filename');
        if ($filename == '-') {
            $string = '';
            while (!feof(STDIN)) {
                $string .= fread(STDIN, 1024);
            }
        } else {
            if (!file_exists($filename)) {
                throw new RuntimeException('File not found: ' . $filename);
            }
            $string = file_get_contents($filename);
        }
        $realmId = $input->getOption('realm');
        $mode = $input->getOption('mode');

        $language = $input->getOption('language');
        if (!$realmId) {
            throw new RuntimeException('Please pass a realm to load');
        }
        $project = new Project();
        $realmLoader = new XmlRealmLoader();
        $realm = $realmLoader->load($realmId, $project);

        $resourceLoader = new XmlResourceLoader();
        $resource = $resourceLoader->loadString($string, $realm);
        $resource->setLanguage($language);

        $writer = new ResourceXmlWriter();
        $doc = $writer->write($resource, $mode);
        echo $doc->saveXml();
        //var_dump($realm);
    }
}
