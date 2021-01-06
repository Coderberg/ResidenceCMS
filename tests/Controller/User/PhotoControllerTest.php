<?php

declare(strict_types=1);

namespace App\Tests\Controller\User;

use App\Entity\Property;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class PhotoControllerTest extends WebTestCase
{
    private const USER = [
        'PHP_AUTH_USER' => 'user',
        'PHP_AUTH_PW' => 'user',
    ];

    public function testEditingForbidden()
    {
        $client = static::createClient([], self::USER);

        $user = $client->getContainer()->get('doctrine')
            ->getRepository(User::class)
            ->findOneBy(['username' => 'admin']);

        $property = $client->getContainer()->get('doctrine')
            ->getRepository(Property::class)
            ->findOneBy(['author' => $user]);

        $client->request('GET', sprintf('/en/user/photo/%d/edit', $property->getId()));
        $this->assertResponseStatusCodeSame(403);
    }

    public function testUploadPhoto()
    {
        $client = static::createClient([], self::USER);

        $property = $client->getContainer()->get('doctrine')
            ->getRepository(Property::class)
            ->findOneBy(['slug' => 'furnished-renovated-2-bedroom-2-bathroom-flat']);

        $crawler = $client->request('GET', sprintf('/en/user/photo/%d/edit', $property->getId()));
        $this->assertResponseIsSuccessful();

        $this->assertStringContainsString('Upload photos', $crawler->filter('h3')->text());

        $photo = __DIR__.'/../../../public/images/bg.jpg';

        $form = $crawler->filter('.js-photo-dropzone')->form();
        $form['file']->upload($photo);
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
    }

    public function testDeletePhoto()
    {
        $client = static::createClient([], self::USER);

        $property = $client->getContainer()->get('doctrine')
            ->getRepository(Property::class)
            ->findOneBy(['slug' => 'furnished-renovated-2-bedroom-2-bathroom-flat']);

        $crawler = $client->request('GET', sprintf('/en/user/photo/%d/edit', $property->getId()));

        $form = $crawler->selectButton('Delete')->first()->form();
        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }
}
