<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Subject;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class SubjectController extends AbstractController
{
    #[Route('/subject', name: 'app_subject')]
    public function index(): Response
    {
        return $this->render('subject/index.html.twig', [
            'controller_name' => 'SubjectController',
        ]);
    }

    #[Route('/subject/create', name: 'app_subject')]
    public function subject(Request $request, EntityManagerInterface $entityManager): Response
    {
        $subject = new Subject();

        $form = $this->createForm(SubjectType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData();
            $subject->setUser($this->getUser());
            $subject->setCreatedAt(new DateTimeImmutable('now'));

            $entityManager->persist($subject);
            $entityManager->flush();

            return $this->redirectToRoute('app_subject');
        }

        return $this->render('registration/subject.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
