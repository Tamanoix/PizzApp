<?php

declare(strict_types=1);

namespace App\Controller\Api\User;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

final class RegisterController extends AbstractController
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('/register',  name:'register')]
    public function __invoke(Request $request): User|JsonResponse
    {

        $data = $request->getContent();
        $data = json_decode($data, true);

       if (preg_match('/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*\d)\S*$/', $data['password'])) {
           $user = new User();
           $user->setEmail($data['email']);
           $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));

           $user->setRoles(["ROLE_CUSTOMER"]);

           $this->em->persist($user);
           $this->em->flush();

           //return $user;
           return $this->json(['success' => 'success', $user]);
       }else {
            return $this->json(['error' => 'bad request']);
        }
    }
}