<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\LinksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class CardController extends AbstractController
{
    #[Route('api/card', name: 'send_card', methods: ['GET'])]
    public function getCardsList(UserRepository $UserRepository, SerializerInterface $serializer): JsonResponse
    {

        $CardsList = $UserRepository->findAll();
        $jsonCardsList = $serializer->serialize($CardsList, 'json', ['groups' => 'getUser']);

        return new JsonResponse($jsonCardsList, Response::HTTP_OK, [], true);
    }


//Affichage d'un seul element
    #[Route('/api/card/{id}', name: 'detailUser', methods: ['GET'])]
    public function getDetailUser(User $User, SerializerInterface $serializer): JsonResponse 
    {
        $jsonCard = $serializer->serialize($User, 'json', ['groups' => 'getUser']);
        return new JsonResponse($jsonCard, Response::HTTP_OK, ['accept' => 'json'], true);
    }


    //Suprimmer un utilisateur (une carte)
    #[Route('/api/card/{id}', name: 'deleteCard', methods: ['DELETE'])]
    public function deleteBook(User $User, EntityManagerInterface $em): JsonResponse 
    {
        $em->remove($User);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }


    //Creer un utilisateurs
    #[Route('/api/card', name:"app_card", methods: ['POST'])]
    public function createUser(Request $request, SerializerInterface $serializer, EntityManagerInterface $em,ValidatorInterface $validator): JsonResponse 
    {

        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        $errors = $validator->validate($user);

      if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
       }
        $em->persist($user);
        $em->flush();

       

        return new JsonResponse('OK!');
   }

    // Modifier  Utilisateur

    #[Route('/api/card/{id}', name:"update_User", methods:['PUT'])]

    public function updateUser(Request $request, SerializerInterface $serializer, User $currentUser, EntityManagerInterface $em): JsonResponse 
    {
        $updatedUser = $serializer->deserialize($request->getContent(),  User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentUser]);
        
        
        $em->persist($updatedUser);
        $em->flush();
        return new JsonResponse('!OK');
   } 

}