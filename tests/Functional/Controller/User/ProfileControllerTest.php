<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\User;

use App\Entity\Profile;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ProfileControllerTest extends WebTestCase
{
    private const USER = [
        'PHP_AUTH_USER' => 'user',
        'PHP_AUTH_PW' => 'user',
    ];

    private const TEST_DATA = [
        'name' => 'Kristina R Maxwell',
        'phone' => '404-518-8373',
    ];

    public function testEditProfile(): void
    {
        $client = self::createClient([], self::USER);

        $crawler = $client->request('GET', '/en/user/profile');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h3', 'My profile');

        $form = $crawler->selectButton('Save changes')->form([
            'profile[full_name]' => self::TEST_DATA['name'],
            'profile[phone]' => self::TEST_DATA['phone'],
        ]);
        $client->submit($form);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.alert-success', 'Updated successfully');

        /** @var Profile $profile */
        $profile = $client->getContainer()->get('doctrine')
            ->getRepository(User::class)
            ->findOneBy(['username' => 'user'])
            ->getProfile();

        $this->assertSame($profile->getFullName(), self::TEST_DATA['name']);
        $this->assertSame($profile->getPhone(), self::TEST_DATA['phone']);

        $form = $crawler->selectButton('Save changes')->form([
            'profile[full_name]' => 'Rhonda Jordan',
            'profile[phone]' => '0(0)99766899',
        ]);
        $client->submit($form);
        $this->assertResponseIsSuccessful();
    }
}
