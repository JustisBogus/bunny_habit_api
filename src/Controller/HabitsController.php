<?php

namespace App\Controller;

use App\Entity\CompletedHabit;
use App\Entity\Habit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/")
 */
class HabitsController extends AbstractController
{

    /**
     * @Route("/", name="habits", methods={"GET"})
     */
    public function habits(SerializerInterface $serializer)
    {
        $repository = $this->getDoctrine()->getRepository(Habit::class);
        $items = $repository->findAll();

        $json = $serializer->serialize(
            $items,
            'json', ['groups' => ['user', 'habit']]
        );
        return new Response($json);
    }

    /**
     * @Route("/habit/update/{id}", name="habit_update", requirements={"id"="\d+"}, methods={"PUT"})
     * @ParamConverter("habit", class="App:Habit")
     */
    public function habit(Request $request, $id)
    {
        $habit = $this->getDoctrine()->getRepository(Habit::class)->find($id);
        $data = json_decode($request->getContent(), true);
        $habit->setCompleted($data['completed']);
        $habit->setModifiedDate(new \DateTime($data['modified_date']));

        $em = $this->getDoctrine()->getManager();
        $em->persist($habit);
        $em->flush();

        return $this->json($habit);
    }

    /**
     * @Route("/habit/add", name="habit_add", methods={"POST"})
    */
    public function habitAdd(Request $request)
    {
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        $habit = $serializer->deserialize($request->getContent(), Habit::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($habit);
        $em->flush();

        return $this->json($habit);
    }

     /**
     * @Route("/completed/{page}", name="completed_habits", defaults={"page": 1}, requirements={"id"="\d+"}, methods={"GET"})
     */
    public function completed($page, Request $request)
    {
        $limit = $request->get('limit', 42);

        $repository = $this->getDoctrine()->getRepository(CompletedHabit::class);

        $items = $repository->findAll();
        return $this->json(
            [
                'page' => $page,
                'limit' => $limit,
                'data' => $items
            ]
        );
    }

    /**
     * @Route("/completed/add", name="completed_add", methods={"POST"})
     */
    public function completedAdd(Request $request)
    {
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        $completedHabit = $serializer->deserialize($request->getContent(), CompletedHabit::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($completedHabit);
        $em->flush();

        return $this->json($completedHabit);
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
     * @Route("/delete/{id}", name="habit_delete", methods={"DELETE"})
     */
    public function delete(Habit $habit)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($habit);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}