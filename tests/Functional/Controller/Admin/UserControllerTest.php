<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Admin;

use App\Entity\User;
use App\Tests\Helper\WebTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class UserControllerTest extends WebTestCase
{
    use WebTestHelper;

    /**
     * This test changes the database contents by creating a new User.
     */
    public function testAdminNewUser(): void
    {
        $client = $this->authAsAdmin($this);
        $crawler = $client->request(Request::METHOD_GET, '/en/admin/user/new');

        $form = $crawler->selectButton('Create user')->form([
            'user[profile][full_name]' => 'test',
            'user[username]' => 'test',
            'user[profile][phone]' => 'test',
            'user[email]' => 'test@test.com',
            'user[email_verified]' => true,
            'user[password]' => 'test',
        ]);
        $client->submit($form);

        $this->assertSame(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );
        $user = $this->getUser($client, 'test');

        $this->assertNotNull($user);
        $this->assertSame('test', $user->getProfile()->getFullName());
        $this->assertSame('test', $user->getUsername());
        $this->assertNotNull($user->getEmailVerifiedAt());
    }

    public function testUserPermissions(): void
    {
        $client = self::createClient([], [
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW' => 'test',
        ]);

        $client->request(Request::METHOD_GET, '/en/user/property');
        $this->assertResponseIsSuccessful();

        $client->request(Request::METHOD_GET, '/en/admin');
        $this->assertResponseStatusCodeSame(403);
    }

    /**
     * This test changes the database contents by editing a User.
     */
    public function testAdminEditUser(): void
    {
        $client = $this->authAsAdmin($this);
        $user = $this->getUser($client, 'test')->getId();

        $crawler = $client->request(Request::METHOD_GET, '/en/admin/user/'.$user.'/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'user[roles]' => ['ROLE_ADMIN'],
            'user[username]' => 'edited',
            'user[email_verified]' => false,
            'user[password]' => 'test',
        ]);

        $client->submit($form);
        $this->assertSame(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );

        $editedUser = $this->getRepository($client, User::class)->findOneBy([
            'id' => $user,
        ]);

        $this->assertSame('edited', $editedUser->getUsername());
        $this->assertNull($editedUser->getEmailVerifiedAt());
    }

    public function testAdminPermissions(): void
    {
        $client = $this->authAsAdmin($this);

        $client->request(Request::METHOD_GET, '/en/user/property');
        $this->assertResponseIsSuccessful();

        $client->request(Request::METHOD_GET, '/en/admin');
        $this->assertResponseIsSuccessful();
    }

    /**
     * This test changes the database contents by deleting a test User.
     */
    public function testAdminDeleteUser(): void
    {
        $client = $this->authAsAdmin($this);
        $user = $this->getUser($client, 'edited')->getId();

        $crawler = $client->request(Request::METHOD_GET, '/en/admin/user');
        $client->submit($crawler->filter('#delete-form-'.$user)->form());
        $this->assertSame(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );

        $this->assertNull($this->getUser($client, 'edited'));
    }
}
