<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Page;
use App\Utils\Slugger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

final class PageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $page = new Page();
        $page->setTitle('About Us');
        $page->setDescription('About Us Page');
        $page->setSlug(Slugger::slugify('About Us'));
        $page->setContent($this->getContent());
        $page->setShowInMenu(true);
        $page->setAddContactForm(false);
        $manager->persist($page);

        $page = new Page();
        $page->setTitle('Contact');
        $page->setDescription('Contact Us');
        $page->setSlug(Slugger::slugify('Contact'));
        $page->setShowInMenu(true);
        $page->setAddContactForm(true);
        $page->setContactEmailAddress('example@domain.com');
        $manager->persist($page);

        $manager->flush();
    }

    private function getContent(): string
    {
        return '<h3>Why Choose Us</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
                sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris 
                nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in 
                reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                <h3>Our Properties</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
                sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris 
                nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in 
                reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                <h3>legal notice</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
                sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris 
                nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in 
                reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>';
    }
}
