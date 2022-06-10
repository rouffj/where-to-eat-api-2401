<?php

namespace App\Controller;

use App\Repository\InMemoryRestaurantRepository;
use App\Repository\RestaurantRepository;
use App\Repository\RestaurantRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * restaurants:
 * GET /restaurants/{id} // show
 * GET /restaurants // listing
 * POST /restaurants
 * PATCH /restaurants/{id}/like|dislike 
 * 
 * users:
 * POST /users // add a new user
 */
class RestaurantController extends AbstractController
{
    private RestaurantRepositoryInterface $restaurantRepository;

    public function __construct(RestaurantRepositoryInterface $restaurantRepository)
    {
        //$this->restaurantRepository = new InMemoryRestaurantRepository();
        $this->restaurantRepository = $restaurantRepository;
    }


    /**
     * @Route("/restaurants", name="restaurants")
     * option 1
     * @IsGranted("ROLE_ADMIN", message="To view the restaurant list, you should have the ADMIN role.")
     */
    public function list(): Response
    {
        // option 2
        /*if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Message d\'acces');
        }*/

        $restaurants = $this->restaurantRepository->findAll();
        //$restaurants = $this->getRestaurants();

        return $this->json($restaurants, 200, [], ['action' => ['list']]);
    }

    /**
     * @Route("/restaurants/{id}", name="restaurant", requirements={"id": "\d+"}, methods="GET")
     */
    public function show(int $id): Response
    {
        $restaurant = $this->restaurantRepository->findOneById($id);

        return $this->json($restaurant, 200, []);
        //return new Response(json_encode($restaurant), 200, ['content-type' => 'application/json']);
    }

    // private function getRestaurants()
    // {
    //     return [
    //         1 => ['id' => 1, 'name' => 'Mon super restaurant indien', 'address' => '...'],
    //         2 => ['id' => 2, 'name' => 'Mon super restaurant chinois', 'address' => '...'],
    //     ];
    // }
}
