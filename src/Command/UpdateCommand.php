<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Profile;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class UpdateCommand extends Command
{
    private $entityManager;

    private $repository;

    protected static $defaultName = 'app:update';
    protected static $defaultDescription = 'Application Update Assistant (1.8.1 -> 1.9.0)';

    public function __construct(
        string $name = null,
        EntityManagerInterface $entityManager,
        UserRepository $repository
    ) {
        parent::__construct($name);
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (0 === $this->runMigrations($output)) {
            return $this->moveUserDataToProfile($input, $output);
        }

        return Command::FAILURE;
    }

    private function runMigrations(OutputInterface $output): int
    {
        $consoleInput = new ArrayInput([]);
        $consoleInput->setInteractive(false);
        $this->getApplication()
            ->find('doctrine:migrations:migrate')
            ->run($consoleInput, $output);

        return Command::SUCCESS;
    }

    private function moveUserDataToProfile($input, $output): int
    {
        try {
            $users = $this->findUsersWithoutProfile();

            foreach ($users as $user) {
                $this->createProfile($user);
            }

            $this->dropExtraColumns();
        } catch (\Exception $exception) {
            return Command::FAILURE;
        }

        $this->printMessage($input, $output);

        return Command::SUCCESS;
    }

    private function createProfile(array $user)
    {
        $profile = (new Profile())
            ->setUser($this->repository->find($user['id']))
            ->setFullName($user['full_name'])
            ->setPhone($user['full_name']);

        $this->entityManager->persist($profile);
        $this->entityManager->flush();
    }

    private function findUsersWithoutProfile()
    {
        $sql = 'SELECT id, full_name, phone FROM users WHERE id NOT IN (SELECT user_id FROM profile)';
        $connection = $this->entityManager->getConnection();
        $query = $connection->prepare($sql);
        $query->executeQuery();

        return $query->fetchAll();
    }

    private function dropExtraColumns()
    {
        $connection = $this->entityManager->getConnection();
        $sql1 = 'ALTER TABLE users DROP full_name';
        $sql2 = 'ALTER TABLE users DROP phone';
        $connection->prepare($sql1)->executeQuery();
        $connection->prepare($sql2)->executeQuery();
    }

    private function printMessage($input, $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->success('Database schema updated successfully!');
    }
}
