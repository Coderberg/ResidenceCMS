<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Profile;
use App\Entity\PropertyDescription;
use App\Repository\PropertyRepository;
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
    protected static $defaultName = 'app:update';
    protected static $defaultDescription = 'Application Update Assistant (1.8.1 -> 1.9.0)';
    private $propertyRepository;
    private $userRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        PropertyRepository $propertyRepository,
        UserRepository $userRepository,
        string $name = null
    ) {
        parent::__construct($name);
        $this->entityManager = $entityManager;
        $this->propertyRepository = $propertyRepository;
        $this->userRepository = $userRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (0 !== $this->runMigrations($output)) {
            return Command::FAILURE;
        }

        try {
            $this->moveUserDataToProfile();
            $this->movePropertyDescription();
            $this->dropExtraColumns();
            $this->printMessage('success', $input, $output);
        } catch (\Exception $exception) {
            $this->printMessage('fail', $input, $output, $exception->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
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

    private function movePropertyDescription()
    {
        $properties = $this->findPropertiesWithoutDescription();

        foreach ($properties as $propertiy) {
            $this->createDescription($propertiy);
        }
    }

    private function moveUserDataToProfile()
    {
        $users = $this->findUsersWithoutProfile();

        foreach ($users as $user) {
            $this->createProfile($user);
        }
    }

    private function createProfile(array $user)
    {
        $profile = (new Profile())
            ->setUser($this->userRepository->find($user['id']))
            ->setFullName($user['full_name'])
            ->setPhone($user['phone']);

        $this->save($profile);
    }

    private function findUsersWithoutProfile()
    {
        $sql = 'SELECT id, full_name, phone FROM users WHERE id NOT IN (SELECT user_id FROM profile)';

        return $this->executeSql($sql)->fetchAll();
    }

    private function findPropertiesWithoutDescription()
    {
        $sql = 'SELECT id, title, content, meta_title, meta_description
                    FROM property
                    WHERE id
                    NOT IN (SELECT property_id FROM property_description)';

        return $this->executeSql($sql)->fetchAll();
    }

    private function createDescription(array $property)
    {
        $profile = (new PropertyDescription())
            ->setProperty($this->propertyRepository->find($property['id']))
            ->setTitle($property['title'])
            ->setContent($property['content'])
            ->setMetaTitle($property['meta_title'])
            ->setMetaDescription($property['meta_description']);

        $this->save($profile);
    }

    private function dropExtraColumns(): void
    {
        $this->executeSql('ALTER TABLE users DROP full_name');
        $this->executeSql('ALTER TABLE users DROP phone');
        $this->executeSql('ALTER TABLE property DROP title');
        $this->executeSql('ALTER TABLE property DROP content');
        $this->executeSql('ALTER TABLE property DROP meta_title');
        $this->executeSql('ALTER TABLE property DROP meta_description');
    }

    private function printMessage(string $type, $input, $output, $message = ''): void
    {
        $io = new SymfonyStyle($input, $output);
        $io->success('Database schema updated successfully!');

        'success' === $type
            ? $io->success('Database schema updated successfully!')
            : $io->error($message);
    }

    private function save($entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    private function executeSql($sql)
    {
        $connection = $this->entityManager->getConnection();
        $query = $connection->prepare($sql);

        return $query->executeQuery();
    }
}
