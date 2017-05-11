<?php

namespace Realm\Command;

use Symfony\Component\Console\Helper\DescriptorHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Realm\Loader\DecorLoader;
use Realm\Writer\RealmWriter;
use Realm\Model\Project;
use RuntimeException;

class DecorConvertCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->ignoreValidationErrors();

        $this
            ->setName('decor:convert')
            ->setDescription('Load decor xml file and output contents')
            ->addOption(
                'filename',
                'f',
                InputOption::VALUE_REQUIRED,
                null
            )
            ->addOption(
                'output',
                'o',
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
            $filename = getcwd() . '/decor.xml';
        }
        $output->writeLn("Loading decor file: " . $filename);
        $project = new Project();

        $realmLoader = new DecorLoader();
        $realmLoader->loadFile($filename, $project);

        $path = $input->getOption('output');
        if (!$path) {
            $path = 'output/';
        }

        $realWriter = new RealmWriter();
        $realWriter->writeFiles($project, $path);
    }
}
