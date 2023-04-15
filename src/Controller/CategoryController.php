<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Category;
use App\Form\CategoryType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'category_list')]
public function categoryList(EntityManagerInterface $entityManager): Response
{
    $categories = $entityManager->getRepository(Category::class)->findAll();

    return $this->render('category/index.html.twig', [
        'categories' => $categories,
    ]);
}

    #[Route('/category/create', name: 'app_category_create')]
    public function category(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $category->setName($data->getName());
            
            $category->setUser($data->getUser());
            
            $category->setCreatedAt(new DateTimeImmutable('now'));

            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_category');
        }

        return $this->render('category/category.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
