<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /** 
     * @Route("/login/register", name="register", methods={"POST"})
    */
    public function register(Request $request)
    {
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
        $user->setCreatedDate(new \DateTime());
        $user->setModifiedDate(new \DateTime());

        $em = $this->getDoctrine()->getManager(); 
        $em->persist($user);
        $em->flush();

        return $this->json($user);
    }
}