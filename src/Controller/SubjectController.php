<?php

namespace App\Controller;

use App\Entity\Board;
use DateTimeImmutable;
use App\Entity\Subject;
use App\Form\SubjectType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SubjectController extends AbstractController
{
    #[Route('/subject', name: 'app_subject')]
    public function index(): Response
    {
        return $this->render('subject/index.html.twig', [
            'controller_name' => 'SubjectController',
        ]);
    }

    #[Route('/subject/create?board={board}', name: 'app_subject_create')]
    public function subject(Request $request, EntityManagerInterface $entityManager, Board $board): Response
    {
        $subject = new Subject();

        $form = $this->createForm(SubjectType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subject = $form->getData();
            $subject->setUser($this->getUser());
            $subject->setCreatedAt(new DateTimeImmutable('now'));
            $subject->setBoard($board);

            $entityManager->persist($subject);
            $entityManager->flush();

            return $this->redirectToRoute('board_show', ['id' => $board->getId()]);
        }

        return $this->render('subject/create.html.twig', [
            'SubjectForm' => $form->createView(),
        ]);
    }

    #[Route('/showsubject/{id}', name: 'subject_show')]
    public function showsubject(Subject $subject): Response{
            return $this->render('subject/subjectshow.html.twig',[
                'subject' => $subject
            ]);
        
    }
}
