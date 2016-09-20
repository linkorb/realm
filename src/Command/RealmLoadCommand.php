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

class RealmLoadCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->ignoreValidationErrors();

        $this
            ->setName('realm:load')
            ->setDescription('Load realm, and output contents')
            ->addOption(
                'filename',
                'f',
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
            $filename = getcwd() . '/realm.xml';
        }
        $output->writeLn("Loading realm file: " . $filename);
        $realmLoader = new XmlRealmLoader();
        $realm = $realmLoader->loadFile($filename);
        var_dump($realm);
    }
}
