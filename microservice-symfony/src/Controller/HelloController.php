<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HelloController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/hello', name: 'app_hello')]
    public function index(Security $security): JsonResponse
    {
        $user = $security->getUser();
        if (!$user instanceof User) {throw new \DomainException('Usuário logado não encontrado!');}
        
        return $this->json([
            'message' => 'Welcome to your new controller ' . $user->getUsername() . '!',
            'path' => 'src/Controller/HelloController.php',
        ]);
    }
}
