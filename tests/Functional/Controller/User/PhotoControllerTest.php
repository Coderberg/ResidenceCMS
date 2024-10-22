<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\User;

use App\Entity\Property;
use App\Tests\Helper\WebTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class PhotoControllerTest extends WebTestCase
{
    use WebTestHelper;

    public function testEditingForbidden(): void
    {
        $client = $this->authAsUser($this);

        $user = $this->getUser($client, 'admin');

        $property = $this->getRepository($client, Property::class)
            ->findOneBy(['author' => $user]);

        $client->request(Request::METHOD_GET, \sprintf('/en/user/photo/%d/edit', $property->getId()));
        $this->assertResponseStatusCodeSame(403);
    }

    public function testUploadPhoto(): void
    {
        $client = $this->authAsUser($this);

        $property = $this->getRepository($client, Property::class)
            ->findOneBy(['slug' => 'furnished-renovated-2-bedroom-2-bathroom-flat']);

        $crawler = $client->request(Request::METHOD_GET, \sprintf('/en/user/photo/%d/edit', $property->getId()));
        $this->assertResponseIsSuccessful();

        $this->assertStringContainsString('Upload photos', $crawler->filter('h3')->text());

        $photo = __DIR__.'/../../../../public/images/bg.jpg';

        $form = $crawler->filter('.js-photo-dropzone')->form();
        $form['file']->upload($photo);
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
    }

    public function testSorting(): void
    {
        $client = $this->authAsUser($this);
        $property = $this->getRepository($client, Property::class)
            ->findOneBy(['slug' => 'interesting-two-bedroom-apartment-for-sale']);

        $crawler = $client->request(Request::METHOD_GET, '/en/user/photo/'.$property->getId().'/edit');
        $token = $this->getCsrfToken($crawler);

        $itemsArray = $property->getPhotos()->map(fn ($item) => $item->getId())->getValues();

        $uri = '/en/user/photo/'.$property->getId().'/sort';
        $client->request(Request::METHOD_POST, $uri, [
            'ids' => array_reverse($itemsArray),
        ]);
        $this->assertResponseStatusCodeSame(419);

        $client->request(Request::METHOD_POST, $uri, [
            'csrf_token' => $token,
            'ids' => array_reverse($itemsArray),
        ]);

        $client->request(Request::METHOD_POST, $uri, [
            'csrf_token' => $token,
            'ids' => $itemsArray,
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"'
        );
    }

    public function testDeletePhoto(): void
    {
        $client = $this->authAsUser($this);

        $property = $this->getRepository($client, Property::class)
            ->findOneBy(['slug' => 'furnished-renovated-2-bedroom-2-bathroom-flat']);

        $crawler = $client->request(Request::METHOD_GET, \sprintf('/en/user/photo/%d/edit', $property->getId()));

        $form = $crawler->selectButton('Delete')->first()->form();
        $client->submit($form);
        $this->assertSame(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );
    }
}
