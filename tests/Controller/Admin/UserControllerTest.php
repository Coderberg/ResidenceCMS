<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class UserControllerTest extends WebTestCase
{
    private const PHP_AUTH_USER = 'admin';
    private const PHP_AUTH_PW = 'admin';

    /**
     * This test changes the database contents by creating a new User.
     */
    public function testAdminNewUser()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);
        $crawler = $client->request('GET', '/admin/user/new');

        $form = $crawler->selectButton('Create user')->form([
            'user[full_name]' => 'test',
            'user[username]' => 'test',
            'user[phone]' => 'test',
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
        $this->assertSame('test', $user->getFullName());
        $this->assertSame('test', $user->getUsername());
    }

    /**
     * This test changes the database contents by editing a User.
     */
    public function testAdminEditUser()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW' => 'test',
        ]);

        $user = $client->getContainer()->get('doctrine')
            ->getRepository(User::class)
            ->findOneBy([
                'username' => 'test',
            ])->getId();

        $crawler = $client->request('GET', '/admin/user/'.$user.'/edit');

        $form = $crawler->selectButton('Save changes')->form([
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

    /**
     * This test changes the database contents by deleting a test User.
     */
    public function testAdminDeleteUser()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);

        $user = $client->getContainer()->get('doctrine')
            ->getRepository(User::class)->findOneBy([
                'username' => 'edited',
            ])->getId();

        $crawler = $client->request('GET', '/admin/user');
        $client->submit($crawler->filter('#delete-form-'.$user)->form());
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $this->assertNull($client->getContainer()->get('doctrine')
            ->getRepository(User::class)->findOneBy([
                'username' => 'edited',
            ]));
    }
}
