<?php

namespace Realm\Command;

use Symfony\Component\Console\Helper\DescriptorHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Realm\Loader\DecorLoader;
use RuntimeException;

class DecorLoadCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->ignoreValidationErrors();

        $this
            ->setName('decor:load')
            ->setDescription('Load decor xml file and output contents')
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
            $filename = getcwd() . '/decor.xml';
        }
        $output->writeLn("Loading decor file: " . $filename);
        $realmLoader = new DecorLoader();
        $realm = $realmLoader->loadFile($filename);
        
        var_dump($realm);
        
        $concept = $realm->getConcept('xxxxx');
        print_r($concept);
        $codelist = $realm->getCodelist('yyyyy');
        print_r($codelist);
    }
}
