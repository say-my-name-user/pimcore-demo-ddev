<?php

namespace App\DataFixtures;

use App\Entity\Color;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ColorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // generate test color values
        foreach (range(1, 10) as $i) {
            $color = new Color();
            $color->setValue($faker->colorName());
            $manager->persist($color);
        }

        $manager->flush();
    }
}
