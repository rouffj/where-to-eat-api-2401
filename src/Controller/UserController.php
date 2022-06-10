<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Event\UserCreatedEvent;

/**
 *  POST /users // add a new user
 * 
 * @Route("/users", name="users_", defaults={"_format": "json"})
 */
class UserController extends AbstractController
{
    private $validator;
    private $entityManager;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("", name="create", methods="POST")
     */
    public function create(Request $request, SerializerInterface $serializer, EventDispatcherInterface $eventDispatcher): Response
    {
        $newUserInfo = $request->getContent();

        /** var User */
        $user = $serializer->deserialize($newUserInfo, User::class, 'json');
        if ($errors = $this->runValidation($user)) {
            return $errors;
        }

        // @TODO Insert in database
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        dump($user);
        $eventDispatcher->dispatch(new UserCreatedEvent($user), 'user_created');

        return $this->json($user, 201);
    }

    /**
     * @Route("/{id}", methods="PUT")
     *
     * @return void
     */
    public function update($id, Request $request, SerializerInterface $serializer, UserRepository $userRepo)
    {
        $newUserInfo = $request->getContent();

        // User from DB
        $myUser = $userRepo->findOneById($id);

        $this->denyAccessUnlessGranted('USER_EDIT', $myUser);

        /** var User */
        $user = $serializer->deserialize($newUserInfo, User::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $myUser
        ]);
        if ($errors = $this->runValidation($user)) {
            return $errors;
        }

        // @TODO Insert in database
        $this->entityManager->flush();

        return $this->json($user, 200);
    }

    public function runValidation(object $object)
    {
        $errors = $this->validator->validate($object);

        if (count($errors) > 0) {
            return $this->json($errors, 422);
        }
    }
}
