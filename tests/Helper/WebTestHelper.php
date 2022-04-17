<?php

declare(strict_types=1);

namespace App\Tests\Helper;

use App\Entity\User;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

trait WebTestHelper
{
    public function authAsAdmin(WebTestCase $testCase): KernelBrowser
    {
        return $testCase::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => 'admin',
        ]);
    }

    public function authAsUser(WebTestCase $testCase): KernelBrowser
    {
        return $testCase::createClient([], [
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW' => 'user',
        ]);
    }

    public function getRepository(KernelBrowser $client, string $entity): ObjectRepository
    {
        return $client->getContainer()->get('doctrine')
            ->getRepository($entity);
    }

    public function getUser(KernelBrowser $client, string $login): ?User
    {
        return $this->getRepository($client, User::class)
            ->findOneBy([
                'username' => $login,
            ]);
    }

    public function restoreUserPassword(WebTestCase $case): Crawler
    {
        $client = $this->authAsAdmin($case);
        $user = $this->getUser($client, 'user')->getId();
        $crawler = $client->request('GET', '/en/admin/user/'.$user.'/edit');
        $form = $crawler->selectButton('Save changes')->form([
            'user[password]' => 'user',
        ]);

        return $client->submit($form);
    }
}
