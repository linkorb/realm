<?php

namespace Realm\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Realm\Loader\XmlRealmLoader;
use Realm\Model\Project;
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
            throw new RuntimeException('Please pass a realm to load');
        }
        $output->writeLn('Loading realm: ' . $realmId);
        $project = new Project();
        $realmLoader = new XmlRealmLoader();
        $realm = $realmLoader->load($realmId, $project);
        var_dump($realm);
    }
}
