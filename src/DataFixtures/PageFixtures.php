<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Page;
use App\Utils\Slugger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class PageFixtures extends Fixture
{
    private const ABOUT_US_SLUG = 'About Us';

    private const CONTACT_SLUG = 'Contact';

    public function load(ObjectManager $manager): void
    {
        $page = new Page();
        $page->setTitle('About Us');
        $page->setDescription('About Us Page');
        $page->setSlug(Slugger::slugify(self::ABOUT_US_SLUG));
        $page->setLocale('en');
        $page->setContent($this->getContent());
        $page->setShowInMenu(true);
        $page->setAddContactForm(false);
        $manager->persist($page);

        $page = new Page();
        $page->setTitle('Contact');
        $page->setDescription('Contact Us');
        $page->setSlug(Slugger::slugify(self::CONTACT_SLUG));
        $page->setLocale('en');
        $page->setShowInMenu(true);
        $page->setAddContactForm(true);
        $page->setContactEmailAddress('example@domain.com');
        $manager->persist($page);

        $page = new Page();
        $page->setTitle('За нас');
        $page->setDescription('Страница за нас');
        $page->setSlug(Slugger::slugify(self::ABOUT_US_SLUG));
        $page->setLocale('bg');
        $page->setContent($this->getBGContent());
        $page->setShowInMenu(true);
        $page->setAddContactForm(false);
        $manager->persist($page);

        $page = new Page();
        $page->setTitle('Контакти');
        $page->setDescription('Страница контакти');
        $page->setSlug(Slugger::slugify(self::CONTACT_SLUG));
        $page->setLocale('bg');
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

    private function getBGContent(): string
    {
        return '<h3>Защо да изберете нас?</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in
                reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                <h3>Нашите имоти</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in
                reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                <h3>Правно съгласие</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in
                reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>';
    }
}
