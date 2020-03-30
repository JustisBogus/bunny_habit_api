<?php

namespace App\DataFixtures;

use App\Entity\Habit;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
  
    public function load(ObjectManager $manager)
    {
        $this->loadHabits($manager);
        $this->loadUsers($manager);
    }

    private function loadHabits(ObjectManager $manager)
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

    private function loadUsers(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('justis');
        $user->setEmail('ikick@mail.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'login1'));
        $user->setCreatedDate(new \DateTime('2020-03-26 12:00:00'));
        $user->setModifiedDate(new \DateTime('2020-03-26 12:00:00'));

        $manager->persist($user);
        $manager->flush();
    }
}
