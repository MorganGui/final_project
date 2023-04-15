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
            $category = $form->getData();
            
            $category->setUser($this->getUser());
            
            $category->setCreatedAt(new DateTimeImmutable('now'));

            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/create.html.twig', [
            'CategoryForm' => $form->createView(),
        ]);
    }
    #[Route('/showcategory/{id}', name: 'category_show')]
    public function showcategory(Category $category): Response{
            return $this->render('category/categoryshow.html.twig',[
                'category' => $category
            ]);
        
    }
}
