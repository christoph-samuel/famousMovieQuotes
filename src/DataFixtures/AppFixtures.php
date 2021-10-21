<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use App\Entity\Quote;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $someMovies = new Movie();
            $someMovies->setName('Film '.($i+1));
            $someMovies->setYear(1998+($i+1));
            $manager->persist($someMovies);

            for ($j = 0; $j < 3; $j++) {
                $someQuotes = new Quote();
                $someQuotes->setMovie($someMovies);
                $someQuotes->setCharacter('Dragan Bond '.($i+1).($j+1));
                $someQuotes->setQuote('Nicht wenn ich schneller bin!, '.($i+1).' - '.($j+1));
                $manager->persist($someQuotes);
            }
        }

        $manager->flush();
    }
}
