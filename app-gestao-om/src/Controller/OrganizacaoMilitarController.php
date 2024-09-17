<?php

namespace App\Controller;

use App\Entity\OrganizacaoMilitar;
use App\Form\OrganizacaoMilitarType;
use App\Repository\OrganizacaoMilitarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/organizacao-militar')]
final class OrganizacaoMilitarController extends AbstractController
{
    #[Route(name: 'app_organizacao_militar_index', methods: ['GET'])]
    public function index(OrganizacaoMilitarRepository $organizacaoMilitarRepository): Response
    {
        return $this->render('organizacao_militar/index.html.twig', [
            'organizacao_militars' => $organizacaoMilitarRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_organizacao_militar_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $organizacaoMilitar = new OrganizacaoMilitar();
        $form = $this->createForm(OrganizacaoMilitarType::class, $organizacaoMilitar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($organizacaoMilitar);
            $entityManager->flush();

            return $this->redirectToRoute('app_organizacao_militar_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('organizacao_militar/new.html.twig', [
            'organizacao_militar' => $organizacaoMilitar,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_organizacao_militar_show', methods: ['GET'])]
    public function show(OrganizacaoMilitar $organizacaoMilitar): Response
    {
        return $this->render('organizacao_militar/show.html.twig', [
            'organizacao_militar' => $organizacaoMilitar,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_organizacao_militar_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, OrganizacaoMilitar $organizacaoMilitar, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OrganizacaoMilitarType::class, $organizacaoMilitar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_organizacao_militar_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('organizacao_militar/edit.html.twig', [
            'organizacao_militar' => $organizacaoMilitar,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_organizacao_militar_delete', methods: ['POST'])]
    public function delete(Request $request, OrganizacaoMilitar $organizacaoMilitar, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$organizacaoMilitar->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($organizacaoMilitar);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_organizacao_militar_index', [], Response::HTTP_SEE_OTHER);
    }
}
