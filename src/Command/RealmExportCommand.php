<?php

namespace Realm\Command;

use Symfony\Component\Console\Helper\DescriptorHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Realm\Loader\XmlRealmLoader;
use Realm\Writer\RealmWriter;
use Realm\Model\Project;
use RuntimeException;

class RealmExportCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->ignoreValidationErrors();

        $this
            ->setName('realm:export')
            ->setDescription('Load realm, and export contents')
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
        $realmId = $input->getOption('realm');
        if (!$realmId) {
            throw new RuntimeException("Please pass a realm to load");
        }
        $output->writeLn("Loading realm: " . $realmId);
        $project = new Project();
        $realmLoader = new XmlRealmLoader();
        $realm = $realmLoader->load($realmId, $project);
        $writer = new RealmWriter();

        $single = true; // TODO: expose through cli option?

        if ($single) {
            $filename = 'build.xml';
            $output->writeLn("Writing to file: " . $filename);
            $writer->writeFile($project, $filename);
        } else {
            $path = 'build/';
            $output->writeLn("Writing to directory: " . $path);
            $writer->writeFiles($project, $path);
        }
    }
}
