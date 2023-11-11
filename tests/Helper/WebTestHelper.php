<?php

declare(strict_types=1);

namespace App\Tests\Helper;

use App\DataFixtures\AppFixtures;
use App\Entity\Settings;
use App\Entity\User;
use App\Repository\SettingsRepository;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;

trait WebTestHelper
{
    public function authAsAdmin(WebTestCase $testCase): KernelBrowser
    {
        return $this->authAs($testCase, 'admin');
    }

    public function authAsUser(WebTestCase $testCase): KernelBrowser
    {
        return $this->authAs($testCase, 'user');
    }

    private function authAs(WebTestCase $testCase, string $username): KernelBrowser
    {
        $client = $testCase::createClient();
        $user = $this->getUser($client, $username);

        // simulate $user being logged in
        return $client->loginUser($user);
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

    public function updateSettings(KernelBrowser $client, array $settings): void
    {
        /**
         * @var SettingsRepository $repository
         */
        $repository = $this->getRepository($client, Settings::class);

        foreach ($settings as $key => $value) {
            $repository->updateSetting($key, $value);
        }
    }

    public function resetSettings(KernelBrowser $client): void
    {
        $settings = [];

        foreach (AppFixtures::getData() as [$key, $value]) {
            $settings[$key] = $value;
        }

        $this->updateSettings($client, $settings);
    }

    private function assertContainsWords(Response $response, array $words): void
    {
        foreach ($words as $word) {
            $this->assertStringContainsString($word, (string) $response->getContent());
        }
    }

    private function getCsrfToken(Crawler $crawler): ?string
    {
        return $crawler->filter('meta[name="csrf-token"]')->eq(0)->attr('content');
    }
}
