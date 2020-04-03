<?php

namespace App\Controller;

use App\Entity\CompletedHabit;
use App\Entity\Habit;
use App\Repository\CompletedHabitRepository;
use App\Repository\HabitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/")
 */
class HabitsController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    public function __construct(SerializerInterface $serializer, TokenStorageInterface $tokenStorage)
    {
        $this->serializer = $serializer;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @Route("/", name="habits", methods={"GET"})
     */
    public function habits(HabitRepository $habitRepository)
    {
        //$this->denyAccessUnlessGranted('get', $habit);
        $dateNow = new \DateTime();
        $date = $dateNow->format('Y-m-d');
        $serializer = $this->serializer;
        $tokenStorage = $this->tokenStorage; 
        $userId = $tokenStorage->getToken()->getUser()->getId();
        $habits = $habitRepository->findAllHabitsByUserId($userId);

        for ($i=0; $i<count($habits); $i++ )
        {
            if ($habits[$i]->getModifiedDate()->format('Y-m-d') < $date) {
                $habits[$i]->setCompleted(false);
                $habits[$i]->setModifiedDate(new \DateTime());
                $id = $habits[$i]->getId();
                $this->habitReset($id);
            }       
        }
        
        //$repository = $this->getDoctrine()->getRepository(Habit::class);
        //$items = $repository->findAll();
        $json = $serializer->serialize(
            $habits,
            'json', ['groups' => ['user', 'habit']]
        );
        return new Response($json);
    }

    /**
     * @Route("/habit/reset/{id}", name="habit_reset", requirements={"id"="\d+"}, methods={"PUT"})
     * @ParamConverter("habit", class="App:Habit")
     * @Security("is_granted('reset', habit)", message="Access denied")
     */
    public function habitReset($id)
    {
        $serializer=$this->serializer;
        $habit = $this->getDoctrine()->getRepository(Habit::class)->find($id);
        $habit->setCompleted(false);
        $habit->setModifiedDate(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($habit);
        $em->flush();

        $json = $serializer->serialize(
            $habit,
            'json', ['groups' => ['user', 'habit']]
        );

        return new Response($json);
    }

    /**
     * @Route("/habit/update/{id}", name="habit_update", requirements={"id"="\d+"}, methods={"PUT"})
     * @ParamConverter("habit", class="App:Habit")
     * @Security("is_granted('update', habit)", message="Access denied")
     */
    public function habitUpdate(Request $request, $id)
    {
        $serializer=$this->serializer;
        $habit = $this->getDoctrine()->getRepository(Habit::class)->find($id);
        $data = json_decode($request->getContent(), true);
        $habit->setCompleted($data['completed']);
        $habit->setModifiedDate(new \DateTime($data['modified_date']));

        $em = $this->getDoctrine()->getManager();
        $em->persist($habit);
        $em->flush();

        $json = $serializer->serialize(
            $habit,
            'json', ['groups' => ['user', 'habit']]
        );

        return new Response($json);
    }

    /**
     * @Route("/habit/add", name="habit_add", methods={"POST"})
    */
    public function habitAdd(Request $request)
    {
        $tokenStorage = $this->tokenStorage;
        $serializer = $this->serializer;
        $user = $tokenStorage->getToken()->getUser();
        $habit = $serializer->deserialize($request->getContent(), Habit::class, 'json');
        $habit->setUser($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($habit);
        $em->flush();

        $json = $serializer->serialize(
            $habit,
            'json', ['groups' => ['user', 'habit']]
        );

        return new Response($json);
    }

     /**
     * @Route("/completed/{page}", name="completed_habits", defaults={"page": 1}, requirements={"id"="\d+"}, methods={"GET"})
     */
    public function completed($page, Request $request, CompletedHabitRepository $completedHabitRepository)
    {
        $serializer = $this->serializer;
        $tokenStorage = $this->tokenStorage; 
        $userId = $tokenStorage->getToken()->getUser()->getId();
        $completedHabits = $completedHabitRepository->findAllCompletedHabitsByUserId($userId);
        $limit = $request->get('limit', 42);
        $items = [
            'page' => $page,
            'limit' => $limit,
            'data' => $completedHabits            
        ];

        $json = $serializer->serialize(
            $items,
            'json', [
                'groups' => ['user', 'completedHabit']
            ]   
        );

        return new Response($json);
    }

    /**
     * @Route("/completed/add", name="completed_add", methods={"POST"})
     */
    public function completedAdd(Request $request)
    {
        $serializer = $this->serializer;
        $tokenStorage = $this->tokenStorage; 
        $user = $tokenStorage->getToken()->getUser();
        $completedHabit = $serializer->deserialize($request->getContent(), CompletedHabit::class, 'json');
        $completedHabit->setUser($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($completedHabit);
        $em->flush();

        $json = $serializer->serialize(
            $completedHabit,
            'json', ['groups' => ['user', 'completedHabit']]
        );

        return new Response($json);
    }

    /*
     * @Route("/completed/{page}", name="completed_habits_page", defaults={"page": 1}, requirements={"id"="\d+"})
  
    public function completedPage($page)
    {

        $repository = $this->getDoctrine()->getRepository(Habit::class);
        $items = $repository->findAll();
        return $this->json(
            [
                'page' => $page,
                'data' => array_map(function ($item) {
                    return $this->generateUrl('completed_habits', ['id' => $item['id']]);
                }, $items)
            ]
        );
    }
    */

    /**
     * @Route("/habit/delete/{id}", name="habit_delete", methods={"DELETE"})
     * @Security("is_granted('delete', habit)", message="Access denied")
     */
    public function delete(Habit $habit)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($habit);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}