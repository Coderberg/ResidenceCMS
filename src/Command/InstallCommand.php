<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:install',
    description: 'Install the application',
)]
final class InstallCommand extends Command
{
    /**
     * @throws ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->getCommands() as $command) {
            $consoleInput = new ArrayInput($command['arguments']);
            $consoleInput->setInteractive(false);
            $this->getApplication()
                ->find($command['command'])
                ->run($consoleInput, $output);
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
