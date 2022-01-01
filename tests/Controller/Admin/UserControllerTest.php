<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class UserControllerTest extends WebTestCase
{
    private const SERVER = [
        'PHP_AUTH_USER' => 'admin',
        'PHP_AUTH_PW' => 'admin',
    ];

    /**
     * This test changes the database contents by creating a new User.
     */
    public function testAdminNewUser(): void
    {
        $client = static::createClient([], self::SERVER);
        $crawler = $client->request('GET', '/en/admin/user/new');

        $form = $crawler->selectButton('Create user')->form([
            'user[profile][full_name]' => 'test',
            'user[username]' => 'test',
            'user[profile][phone]' => 'test',
            'user[email]' => 'test@test.com',
            'user[password]' => 'test',
        ]);
        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $user = $client->getContainer()->get('doctrine')
            ->getRepository(User::class)->findOneBy([
                'username' => 'test',
            ]);

        $this->assertNotNull($user);
        $this->assertSame('test', $user->getProfile()->getFullName());
        $this->assertSame('test', $user->getUsername());
    }

    public function testUserPermissions(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW' => 'test',
        ]);

        $client->request('GET', '/en/user/property');
        $this->assertResponseIsSuccessful();

        $client->request('GET', '/en/admin');
        $this->assertResponseStatusCodeSame(403);
    }

    /**
     * This test changes the database contents by editing a User.
     */
    public function testAdminEditUser(): void
    {
        $client = static::createClient([], self::SERVER);

        $user = $client->getContainer()->get('doctrine')
            ->getRepository(User::class)
            ->findOneBy([
                'username' => 'test',
            ])->getId();

        $crawler = $client->request('GET', '/en/admin/user/'.$user.'/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'user[roles]' => ['ROLE_ADMIN'],
            'user[username]' => 'edited',
            'user[password]' => 'test',
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $editedUser = $client->getContainer()->get('doctrine')
            ->getRepository(User::class)->findOneBy([
                'id' => $user,
            ]);

        $this->assertSame('edited', $editedUser->getUsername());
    }

    public function testAdminPermissions(): void
    {
        $client = static::createClient([], self::SERVER);

        $client->request('GET', '/en/user/property');
        $this->assertResponseIsSuccessful();

        $client->request('GET', '/en/admin');
        $this->assertResponseIsSuccessful();
    }

    /**
     * This test changes the database contents by deleting a test User.
     */
    public function testAdminDeleteUser(): void
    {
        $client = static::createClient([], self::SERVER);

        $user = $client->getContainer()->get('doctrine')
            ->getRepository(User::class)->findOneBy([
                'username' => 'edited',
            ])->getId();

        $crawler = $client->request('GET', '/en/admin/user');
        $client->submit($crawler->filter('#delete-form-'.$user)->form());
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $this->assertNull($client->getContainer()->get('doctrine')
            ->getRepository(User::class)->findOneBy([
                'username' => 'edited',
            ]));
    }
}
