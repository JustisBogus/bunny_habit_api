<?php

namespace App\DataFixtures;

use App\Entity\Habit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $habit = new Habit();
        $habit->setHabit('meditate');
        $habit->setCreatedDate(new \DateTime('2020-03-19 12:00:00'));
        $habit->setModifiedDate(new \DateTime('2020-03-19 12:00:00'));
        $habit->setDayly('1');
        $habit->setType(1);
        $habit->setCompleted(false);
        $habit->setComment('');

        $manager->persist($habit);

        $habit = new Habit();
        $habit->setHabit('read');
        $habit->setCreatedDate(new \DateTime('2020-03-19 12:00:00'));
        $habit->setModifiedDate(new \DateTime('2020-03-19 12:00:00'));
        $habit->setDayly('7');
        $habit->setType(1);
        $habit->setCompleted(false);
        $habit->setComment('');

        $manager->persist($habit);
        $manager->flush();
    }
}
