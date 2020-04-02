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
        $this->loadUsers($manager);
        $this->loadHabits($manager);  
    }

    private function loadHabits(ObjectManager $manager)
    {
        $habit = new Habit();
        $habit->setHabit('meditate');
        $habit->setCreatedDate(new \DateTime());
        $habit->setModifiedDate(new \DateTime());
        $habit->setDayly('1');
        $habit->setType(1);
        $habit->setCompleted(false);
        $habit->setComment('');
        $habit->setUser($this->getReference('justis'));

        $manager->persist($habit);

        $habit = new Habit();
        $habit->setHabit('read');
        $habit->setCreatedDate(new \DateTime());
        $habit->setModifiedDate(new \DateTime());
        $habit->setDayly('7');
        $habit->setType(1);
        $habit->setCompleted(false);
        $habit->setComment('');
        $habit->setUser($this->getReference('justis'));

        $manager->persist($habit);

        $habit = new Habit();
        $habit->setHabit('jog');
        $habit->setCreatedDate(new \DateTime());
        $habit->setModifiedDate(new \DateTime());
        $habit->setDayly('1');
        $habit->setType(1);
        $habit->setCompleted(false);
        $habit->setComment('');
        $habit->setUser($this->getReference('newuser'));

        $manager->persist($habit);

        $habit = new Habit();
        $habit->setHabit('smoke');
        $habit->setCreatedDate(new \DateTime());
        $habit->setModifiedDate(new \DateTime());
        $habit->setDayly('7');
        $habit->setType(2);
        $habit->setCompleted(false);
        $habit->setComment('');
        $habit->setUser($this->getReference('newuser'));

        $manager->persist($habit);
        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('justis');
        $user->setEmail('ikick@mail.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'login1'));
        $user->setCreatedDate(new \DateTime());
        $user->setModifiedDate(new \DateTime());

        $this->addReference('justis', $user);

        $manager->persist($user);

        $user = new User();
        $user->setUsername('newuser');
        $user->setEmail('newuser@mail.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'login1'));
        $user->setCreatedDate(new \DateTime());
        $user->setModifiedDate(new \DateTime());

        $this->addReference('newuser', $user);

        $manager->persist($user);
        $manager->flush();
    }
}
