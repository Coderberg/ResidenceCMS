<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\User;

use App\Entity\Profile;
use App\Tests\Helper\WebTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

final class ProfileControllerTest extends WebTestCase
{
    use WebTestHelper;

    private const TEST_DATA = [
        'name' => 'Kristina R Maxwell',
        'phone' => '404-518-8373',
    ];

    public function testEditProfile(): void
    {
        $client = $this->authAsUser($this);

        $crawler = $client->request(Request::METHOD_GET, '/en/user/profile');
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
        $profile = $this->getUser($client, 'user')
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
