<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class InstallCommand extends Command
{
    protected static $defaultName = 'app:install';

    protected function configure()
    {
        $this->setDescription('Install the application');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->getCommands() as $c) {
            $input = new ArrayInput($c['arguments']);
            $input->setInteractive(false);
            $this->getApplication()
                ->find($c['command'])
                ->run($input, $output);
        }

        return Command::SUCCESS;
    }

    private function getCommands(): array
    {
        return [
            [
                'command' => 'doctrine:database:create',
                'arguments' => [
                    '--if-not-exists' => true,
                ],
            ],
            [
                'command' => 'doctrine:migrations:migrate',
                'arguments' => [],
            ],
            [
                'command' => 'doctrine:fixtures:load',
                'arguments' => [],
            ],
        ];
    }
}
