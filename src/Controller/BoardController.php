<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\Category;
use DateTimeImmutable;
use App\Form\BoardType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BoardController extends AbstractController
{
    #[Route('/board', name: 'app_board')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $boards = $entityManager->getRepository(Board::class)->findAll();
        return $this->render('board/board.html.twig', [
            'boards' => $boards
        ]);
    }

    #[Route('/board/create?category={category}', name: 'app_board_create')]
    public function board(Request $request, EntityManagerInterface $entityManager, Category $category): Response
    {
        $board = new Board();

        $form = $this->createForm(BoardType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $board = $form->getData();
            $board->setUser($this->getUser());
            $board->setCreatedAt(new DateTimeImmutable('now'));
            $board->setCategory($category);
            $entityManager->persist($board);
            $entityManager->flush();

            return $this->redirectToRoute('category_show', ['id' => $category->getId()]);
        }

        return $this->render('board/create.html.twig', [
            'BoardForm' => $form->createView(),
        ]);
    }
    #[Route('/showboard/{id}', name: 'board_show')]
    public function showboard(Board $board): Response{
            return $this->render('board/boardshow.html.twig',[
                'board' => $board
            ]);
        
    }
}
