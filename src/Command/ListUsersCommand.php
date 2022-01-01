<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ListUsersCommand extends Command
{
    protected static $defaultName = 'app:list-users';

    /**
     * @var UserRepository
     */
    private $users;

    public function __construct(UserRepository $users)
    {
        parent::__construct();
        $this->users = $users;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Lists all the existing users')
            ->addOption(
                'limit',
                null,
                InputOption::VALUE_OPTIONAL,
                'Limits the number of users listed',
                50
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $limit = $input->getOption('limit');

        $allUsers = $this->users->findBy([], ['id' => 'DESC'], $limit);

        $usersAsPlainArrays = array_map(function (User $user) {
            return [
                $user->getId(),
                $user->getProfile()->getFullName(),
                $user->getUsername(),
                $user->getEmail(),
                implode(', ', $user->getRoles()),
            ];
        }, $allUsers);

        $bufferedOutput = new BufferedOutput();
        $io = new SymfonyStyle($input, $bufferedOutput);
        $io->table(
            ['ID', 'Full Name', 'Username', 'Email', 'Roles'],
            $usersAsPlainArrays
        );

        $usersAsATable = $bufferedOutput->fetch();
        $output->write($usersAsATable);

        return Command::SUCCESS;
    }
}
