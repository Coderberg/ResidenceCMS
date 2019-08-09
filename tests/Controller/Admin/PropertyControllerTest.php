<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin;

use App\Entity\Category;
use App\Entity\Locality;
use App\Entity\Operation;
use App\Entity\Property;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

final class PropertyControllerTest extends WebTestCase
{
    private const PHP_AUTH_USER = 'admin';
    private const PHP_AUTH_PW = 'admin';

    /**
     * This test changes the database contents by creating a new Property.
     */
    public function testAdminNewProperty()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);

        $crawler = $client->request('GET', '/admin/property/new');

        $locality = $client->getContainer()->get('doctrine')
            ->getRepository(Locality::class)->findOneBy([])->getId();

        $operation = $client->getContainer()->get('doctrine')
            ->getRepository(Operation::class)->findOneBy([])->getId();

        $category = $client->getContainer()->get('doctrine')
            ->getRepository(Category::class)->findOneBy([])->getId();

        $form = $crawler->selectButton('Create property')->form([
            'property[locality]' => $locality,
            'property[operation]' => $operation,
            'property[category]' => $category,
            'property[title]' => 'test',
            'property[description]' => 'test',
            'property[address]' => 'test',
            'property[content]' => 'test',
        ]);

        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testAdminEditPhoto()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);

        $property = $client->getContainer()->get('doctrine')
            ->getRepository(Property::class)
            ->findOneBy(['title' => 'test'])->getId();

        $crawler = $client->request('GET', '/admin/photo/'.$property.'/edit');

        $photo = new UploadedFile(
            __DIR__.'/../../../public/images/bg.jpg',
            'bg.jpg',
            'image/jpeg',
            null
        );

        $form = $crawler->selectButton('Upload photo')->form([
            'photo[priority]' => '0',
        ]);

        $form['photo[photo]']->upload($photo);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testAdminDeletePhoto()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);

        $property = $client->getContainer()->get('doctrine')
            ->getRepository(Property::class)
            ->findOneBy(['title' => 'test'])->getId();

        $crawler = $client->request('GET', '/admin/photo/'.$property.'/edit');

        $form = $crawler->selectButton('Delete')->form();
        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }

    /**
     * This test changes the database contents by deleting a test Property.
     */
    public function testAdminDeleteProperty()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);

        $property = $client->getContainer()->get('doctrine')
            ->getRepository(Property::class)
            ->findOneBy(['title' => 'test'])->getId();

        $crawler = $client->request('GET', '/admin/property');
        $client->submit($crawler->filter('#delete-form-'.$property)->form());

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $this->assertNull($client->getContainer()->get('doctrine')
            ->getRepository(Property::class)->findOneBy([
                'title' => 'test',
            ]));
    }
}
