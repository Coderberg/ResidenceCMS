<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin;

use App\Entity\Operation;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class OperationControllerTest extends WebTestCase
{
    private const PHP_AUTH_USER = 'admin';
    private const PHP_AUTH_PW = 'admin';

    private const NAME = 'Test';
    private const SLUG = 'test';
    private const EDITED_NAME = 'Edited';

    /**
     * This test changes the database contents by creating a new Operation.
     */
    public function testAdminNewOperation()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);

        $crawler = $client->request('GET', '/admin/operation/new');

        $form = $crawler->selectButton('Create operation')->form([
            'operation[name]' => self::NAME,
            'operation[slug]' => self::SLUG,
        ]);
        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $operation = $client->getContainer()->get('doctrine')
            ->getRepository(Operation::class)->findOneBy([
                'slug' => self::SLUG,
            ]);

        $this->assertNotNull($operation);
        $this->assertSame(self::NAME, $operation->getName());
        $this->assertSame(self::SLUG, $operation->getSlug());
    }

    /**
     * This test changes the database contents by editing an Operation.
     */
    public function testAdminEditOperation()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);

        $operation = $client->getContainer()->get('doctrine')
            ->getRepository(Operation::class)
            ->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $client->request('GET', '/admin/operation/'.$operation.'/edit');

        $form = $crawler->selectButton('Save changes')->form([
            'operation[name]' => self::EDITED_NAME,
        ]);

        $client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $editedOperation = $client->getContainer()->get('doctrine')
            ->getRepository(Operation::class)->findOneBy([
                'id' => $operation,
            ]);

        $this->assertSame(self::EDITED_NAME, $editedOperation->getName());
    }

    /**
     * This test changes the database contents by deleting a test Operation.
     */
    public function testAdminDeleteOperation()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => self::PHP_AUTH_USER,
            'PHP_AUTH_PW' => self::PHP_AUTH_PW,
        ]);

        $operation = $client->getContainer()->get('doctrine')
            ->getRepository(Operation::class)->findOneBy([
                'slug' => self::SLUG,
            ])->getId();

        $crawler = $client->request('GET', '/admin/operation');
        $client->submit($crawler->filter('#delete-form-'.$operation)->form());
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $this->assertNull($client->getContainer()->get('doctrine')
            ->getRepository(Operation::class)->findOneBy([
                'slug' => self::SLUG,
            ]));
    }
}
