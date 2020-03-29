<?php

declare(strict_types=1);

namespace App\Tests\Controller\User;

use App\Entity\Property;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class PropertyControllerTest extends WebTestCase
{
    private const USER = [
        'PHP_AUTH_USER' => 'user',
        'PHP_AUTH_PW' => 'user',
    ];

    public function testIndex()
    {
        $client = static::createClient([], self::USER);

        $crawler = $client->request('GET', '/user/property');
        $this->assertResponseIsSuccessful(sprintf('The %s public URL loads correctly.', '/user/account'));
        $this->assertCount(2, $crawler->filter('.property-box-img'));
        $this->assertSelectorTextContains('html', 'My properties (2)');

        $client->request('GET', '/admin');
        $this->assertResponseStatusCodeSame(403);
    }

    public function testUnpublish()
    {
        $client = static::createClient([], self::USER);

        $user = $client->getContainer()->get('doctrine')
            ->getRepository(User::class)
            ->findOneBy(['username' => 'user']);

        $property = $client->getContainer()->get('doctrine')
            ->getRepository(Property::class)
            ->findOneBy(['author' => $user]);

        $client->request('GET', sprintf('/user/property/%d/unpublish', $property->getId()));

        // asserts that the "Content-Type" header is "application/json"
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"'
        );

        $this->assertContains('ok', $client->getResponse()->getContent());
    }

    public function testEditingForbidden()
    {
        $client = static::createClient([], self::USER);

        $user = $client->getContainer()->get('doctrine')
            ->getRepository(User::class)
            ->findOneBy(['username' => 'admin']);

        $property = $client->getContainer()->get('doctrine')
            ->getRepository(Property::class)
            ->findOneBy(['author' => $user]);

        $client->request('GET', sprintf('/user/property/%d/unpublish', $property->getId()));
        $this->assertResponseStatusCodeSame(403);

        $client->request('GET', sprintf('/user/property/%d/edit', $property->getId()));
        $this->assertResponseStatusCodeSame(403);
    }

    public function testPublish()
    {
        $client = static::createClient([], self::USER);

        $property = $client->getContainer()->get('doctrine')
            ->getRepository(Property::class)
            ->findOneBy(['state' => 'private']);

        $client->request('GET', sprintf('/user/property/%d/publish', $property->getId()));

        // asserts that the "Content-Type" header is "application/json"
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"'
        );

        $this->assertContains('ok', $client->getResponse()->getContent());
    }

    public function testEditProperty()
    {
        $client = static::createClient([], self::USER);

        $property = $client->getContainer()->get('doctrine')
            ->getRepository(Property::class)
            ->findOneBy(['slug' => 'furnished-renovated-2-bedroom-2-bathroom-flat']);

        $crawler = $client->request('GET', sprintf('/user/property/%d/edit', $property->getId()));
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Save changes')->form([
            'property[meta_title]' => 'Custom Meta Title',
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $editedProperty = $client->getContainer()->get('doctrine')
            ->getRepository(Property::class)
            ->findOneBy(['meta_title' => 'Custom Meta Title']);

        $crawler = $client->request('GET', sprintf('/user/property/%d/edit', $editedProperty->getId()));
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Save changes')->form([
            'property[meta_title]' => '',
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }
}
