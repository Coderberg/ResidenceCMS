<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Valery Maslov
 * Date: 10.08.2018
 * Time: 14:40.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Locality;
use App\Entity\Menu;
use App\Entity\Operation;
use App\Entity\Page;
use App\Entity\Photo;
use App\Entity\Property;
use App\Entity\Setting;
use App\Entity\User;
use App\Utils\Slugger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadSettings($manager);
        $this->loadPages($manager);
        $this->loadLocalities($manager);
        $this->loadCategories($manager);
        $this->loadOperations($manager);
        $this->loadUsers($manager);
        $this->loadProperties($manager);
        $this->loadPhotos($manager);
        $this->loadMenu($manager);
    }

    private function loadSettings(ObjectManager $manager): void
    {
        $setting = new Setting();
        $setting->setName('Site name');
        $setting->setTitle('Site Title');
        $setting->setDescription('Site Description');
        $setting->setItemsPerPage(6);
        $manager->persist($setting);
        $manager->flush();
    }

    private function loadPages(ObjectManager $manager): void
    {
        $page = new Page();
        $page->setTitle('About Us');
        $page->setDescription('About Us Page');
        $page->setSlug(Slugger::slugify('About Us'));
        $page->setContent($this->getContent());
        $page->setShowInMenu(true);
        $manager->persist($page);
        $manager->flush();
    }

    private function loadLocalities(ObjectManager $manager): void
    {
        foreach ($this->getLocalityData() as [$slug, $name]) {
            $locality = new Locality();
            $locality->setName($name);
            $locality->setSlug($slug);
            $manager->persist($locality);
            $this->addReference($name, $locality);
        }
        $manager->flush();
    }

    private function loadCategories(ObjectManager $manager): void
    {
        foreach ($this->getCategoryData() as [$slug, $name]) {
            $category = new Category();
            $category->setName($name);
            $category->setSlug($slug);
            $manager->persist($category);
            $this->addReference($name, $category);
        }
        $manager->flush();
    }

    private function loadOperations(ObjectManager $manager): void
    {
        foreach ($this->getOperationData() as [$slug, $name]) {
            $operation = new Operation();
            $operation->setName($name);
            $operation->setSlug($slug);
            $manager->persist($operation);
            $this->addReference($name, $operation);
        }
        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager): void
    {
        $user = new User();
        $user->setFullName('John Smith');
        $user->setUsername('admin');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user, 'admin'
        ));
        $user->setPhone('0(0)99766899');
        $user->setEmail('admin@admin');
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);
        $this->addReference('admin', $user);
        $manager->flush();
    }

    private function loadProperties(ObjectManager $manager): void
    {
        foreach ($this->getPropertyData() as [$operation_id, $category_id, $locality_id, $title,
                 $address, $latitude, $longitude, $price, $price_type, ]) {
            $property = new Property();
            $property->setAuthor($this->getReference('admin'));
            $property->setOperation($operation_id);
            $property->setCategory($category_id);
            $property->setLocality($locality_id);
            $property->setTitle($title);
            $property->setDescription($title);
            $property->setSlug(Slugger::slugify($title));
            $property->setContent($this->getPropertyContent());
            $property->setAddress($address);
            $property->setLatitude($latitude);
            $property->setLongitude($longitude);
            $property->setShowMap(true);
            $property->setPrice($price);
            $property->setPriceType($price_type);
            $property->setPublished(true);
            $property->setPublishedAt(new \DateTime('now'));
            $manager->persist($property);
            $this->addReference(Slugger::slugify($title), $property);
        }
        $manager->flush();
    }

    private function loadPhotos(ObjectManager $manager): void
    {
        foreach ($this->getPhotoData() as [$property, $priority, $file]) {
            $photo = new Photo();
            $photo->setProperty($property);
            $photo->setPriority($priority);
            $photo->setPhoto($file);
            $manager->persist($photo);
        }
        $manager->flush();
    }

    private function loadMenu(ObjectManager $manager): void
    {
        foreach ($this->getMenuData() as [$title, $url]) {
            $menu = new Menu();
            $menu->setTitle($title);
            $menu->setUrl($url);
            $manager->persist($menu);
            $this->addReference($title, $menu);
        }
        $manager->flush();
    }

    private function getCategoryData(): array
    {
        return [
            // $categoryData = [$slug, $name];
            ['apartment', 'Apartment'],
            ['duplex', 'Duplex'],
            ['penthouse', 'Penthouse'],
            ['villa', 'Villa'],
        ];
    }

    private function getOperationData(): array
    {
        return [
            // $operationData = [$slug, $name];
            ['rent', 'Rent'],
            ['sale', 'Sale'],
        ];
    }

    private function getLocalityData(): array
    {
        return [
            // $localityData = [$slug, $name];
            ['miami', 'Miami'],
            ['palm-beach', 'Palm Beach'],
            ['tampa', 'Tampa'],
        ];
    }

    private function getPhotoData(): array
    {
        return [
            // $photoData = [$property, $priority, $file];
            [$this->getReference('bright-and-cheerful-alcove-studio'), 2, 'demo/1.jpeg'],
            [$this->getReference('bright-and-cheerful-alcove-studio'), 1, 'demo/2.jpeg'],
            [$this->getReference('modern-one-bedroom-apartment-in-miami'), 2, 'demo/3.jpeg'],
            [$this->getReference('modern-one-bedroom-apartment-in-miami'), 1, 'demo/4.jpeg'],
            [$this->getReference('stylish-two-level-penthouse-in-palm-beach'), 2, 'demo/5.jpeg'],
            [$this->getReference('stylish-two-level-penthouse-in-palm-beach'), 1, 'demo/6.jpeg'],
            [$this->getReference('bright-fully-furnished-1-bedroom-flat-on-the-2nd-floor'), 2, 'demo/7.jpeg'],
            [$this->getReference('bright-fully-furnished-1-bedroom-flat-on-the-2nd-floor'), 1, 'demo/2.jpeg'],
            [$this->getReference('beautiful-villa-for-sale-in-tampa'), 3, 'demo/8.jpeg'],
            [$this->getReference('beautiful-villa-for-sale-in-tampa'), 2, 'demo/9.jpeg'],
            [$this->getReference('beautiful-villa-for-sale-in-tampa'), 1, 'demo/4.jpeg'],
            [$this->getReference('furnished-renovated-2-bedroom-2-bathroom-flat'), 2, 'demo/10.jpeg'],
            [$this->getReference('furnished-renovated-2-bedroom-2-bathroom-flat'), 1, 'demo/6.jpeg'],
            [$this->getReference('interesting-two-bedroom-apartment-for-sale'), 3, 'demo/11.jpeg'],
            [$this->getReference('interesting-two-bedroom-apartment-for-sale'), 2, 'demo/12.jpeg'],
            [$this->getReference('interesting-two-bedroom-apartment-for-sale'), 1, 'demo/13.jpeg'],
        ];
    }

    private function getPropertyData(): array
    {
        return [
            /*
                $propertyData = [$operation_id, $category_id, $locality_id,
                                $title, $address, $latitude, $longitude,
                                $price, $price_type];
            */
            [
                $this->getReference('Rent'),
                $this->getReference('Apartment'),
                $this->getReference('Miami'),
                'Modern one-bedroom apartment in Miami',
                '1451 Ocean Dr, Miami Beach, FL 33139',
                '25.785107', '-80.129460', 250, 'day',
            ],
            [
                $this->getReference('Rent'),
                $this->getReference('Apartment'),
                $this->getReference('Miami'),
                'Bright and Cheerful alcove studio',
                '1451 Ocean Dr, Miami Beach, FL 33139',
                '25.785107', '-80.129460', 200, 'day',
            ],
            [
                $this->getReference('Rent'),
                $this->getReference('Penthouse'),
                $this->getReference('Palm Beach'),
                'Stylish two-level penthouse in Palm Beach',
                '101 Worth Ave, Palm Beach, FL 33480',
                '26.701320', '-80.033688', 2000, 'mo',
            ],
            [
                $this->getReference('Rent'),
                $this->getReference('Apartment'),
                $this->getReference('Palm Beach'),
                'Bright fully furnished 1-bedroom flat on the 2nd floor',
                '300 S Ocean Blvd, Palm Beach, FL',
                '26.705007', '-80.033574', 180, 'day',
            ],
            [
                $this->getReference('Sale'),
                $this->getReference('Villa'),
                $this->getReference('Tampa'),
                'Beautiful villa for sale in Tampa',
                '4935 New Providence Ave, Tampa, FL',
                '27.932255', '-82.533187', 1600, 'sq. foot',
            ],
            [
                $this->getReference('Rent'),
                $this->getReference('Apartment'),
                $this->getReference('Tampa'),
                'Furnished renovated 2-bedroom 2-bathroom flat',
                '5411 Bayshore Blvd, Tampa, FL 33611',
                '27.885095', '-82.486153', 2200, 'mo',
            ],
            [
                $this->getReference('Sale'),
                $this->getReference('Apartment'),
                $this->getReference('Miami'),
                'Interesting two-bedroom apartment for sale',
                '111 NE 2nd Ave, Miami, FL 33132',
                '25.775565', '-80.190125', 190000, '',
            ],
        ];
    }

    private function getMenuData(): array
    {
        return [
            // $menuData = [$title, $url];
            ['Homepage', '/'],
            ['About Us', '/info/about-us'],
        ];
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

    private function getPropertyContent(): string
    {
        return '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
                sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
                Ut enim ad minim veniam, quis nostrud exercitation ullamco 
                laboris nisi ut aliquip ex ea commodo consequat. 
                Duis aute irure dolor in reprehenderit in voluptate 
                velit esse cillum dolore eu fugiat nulla pariatur. 
                Excepteur sint occaecat cupidatat non proident, sunt in culpa qui 
                officia deserunt mollit anim id est laborum.</p>
                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem 
                accusantium doloremque laudantium, totam rem aperiam, 
                eaque ipsa quae ab illo inventore veritatis et quasi 
                architecto beatae vitae dicta sunt explicabo. 
                Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut 
                odit aut fugit, sed quia consequuntur magni dolores eos qui 
                ratione voluptatem sequi nesciunt. Neque porro quisquam est, 
                qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, 
                sed quia non numquam eius modi tempora incidunt ut labore et dolore 
                magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, 
                quis nostrum exercitationem ullam corporis suscipit laboriosam, 
                nisi ut aliquid ex ea commodi consequatur.</p>';
    }
}
