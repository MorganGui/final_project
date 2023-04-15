<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Board;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class BoardController extends AbstractController
{
    #[Route('/board', name: 'app_board')]
    public function index(): Response
    {
        return $this->render('board/board.html.twig', [
            'controller_name' => 'BoardController',
        ]);
    }

    #[Route('/board/create', name: 'app_board_create')]
    public function board(Request $request, EntityManagerInterface $entityManager): Response
    {
        $board = new Board();

        $form = $this->createForm(BoardType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $board->setName($data->getName());
            $board->setUser($data->getUser());
            $board->setCreatedAt(new DateTimeImmutable('now'));

            $entityManager->persist($board);
            $entityManager->flush();

            return $this->redirectToRoute('app_board');
        }

        return $this->render('board/board_create.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
