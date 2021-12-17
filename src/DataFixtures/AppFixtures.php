<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Media;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');



        for($i = 1; $i <= 6; $i++) {
            $category = new Category();

            $category->setName("Categorie-00" . $i);
            $category->setDescription($faker->paragraph);

            $manager->persist($category);
        }

        for($i = 1; $i <= 6; $i++) {
            $media = new Media();

            $media->setSrc("https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQZptv62XuKUH_JFFbwvBtxd9JZoXxjHatdFg&usqp=CAU" . $i);
            $media->setAlt("Balise Alt de l'image-00" . $i);
            $media->setTitle("Ceci est mon titre-00" . $i);
            $media->setCategory($category);


            $manager->persist($media);
        }


        $manager->flush();
    }
}
