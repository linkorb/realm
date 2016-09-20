<?php

namespace Realm\Command;

use Symfony\Component\Console\Helper\DescriptorHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Realm\Loader\XmlRealmLoader;
use RuntimeException;

class LoadCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->ignoreValidationErrors();

        $this
            ->setName('load')
            ->setDescription('Load realm, and output contents')
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
        $projectPath = $input->getOption('realm');
        if (!$projectPath) {
            $projectPath = getcwd() . '/realm.xml';
        }
        $output->writeLn("Loading realm: " . $projectPath);
        $realmLoader = new XmlRealmLoader();
        $realm = $realmLoader->loadFile($projectPath);
        var_dump($realm);
    }
}
