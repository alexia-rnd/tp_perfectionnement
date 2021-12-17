<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{

    /**
     * @Route("admin/category_list", name="admin_category_list")
     */
        public function AdminCategorylist(CategoryRepository $categoryRepository)
        {
            $category = $categoryRepository->findAll();
    
            return $this->render('admin/categorylist.html.twig', [
                'categorys' => $category,
            ]);
        }
    

        /**
        * @Route("admin/category_{id<\d+>}", name="admin_category_id")
        */
    
        public function AdminCategoryId(CategoryRepository $categoryRepository, $id)
        {
            $category = $categoryRepository->find($id);
    
            return $this->render('admin/showcategory.html.twig', [
                "category" => $category,
    
            ]);
        } 
        
    /**
     * @Route("/admin/category_create", name="admin_category_create")
     */
    public function AdminCategoryCreate(Request $request, EntityManagerInterface $manager)
    {
        $category = new Category();
        $categoryForm = $this->createForm(CategoryType::class, $category);

        $categoryForm->handleRequest($request);

        if( $categoryForm->isSubmitted() && $categoryForm->isValid())
        {
            $manager->persist($category);
            $manager->flush();

            return $this->redirectToRoute('admin_category_list');
        }

        return $this->render("admin/form_category.html.twig", ["categoryForm" => $categoryForm->createView()]);
    }

    /**
     * @Route("admin/category_update_{id<\d+>}", name="admin_category_update")
     */
    public function AdminCategoryUpdate($id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManagerInterface, Request $request
    ) {
        $category = $categoryRepository->find($id);
        $categoryForm = $this->createForm(CategoryType::class, $category);

        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $entityManagerInterface->persist($category);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('admin_category_list');
        }

        return $this->render("admin/form_category.html.twig", ["categoryForm" => $categoryForm->createView()]);
    }

    //DELETE 

    //! PB : Foreign Key - ne peux pas être supprimer 
    /**
     * @Route("admin/category_delete_{id<\d+>}", name="admin_category_delete")
     */
    public function AdminMediaDelete($id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManagerInterface)
    {
        $category = $categoryRepository->find($id);
        $entityManagerInterface->remove($category); 
        $entityManagerInterface->flush();
        $this->addFlash(
            'notice',
            'La category a été supprimé'
        );

        return $this->redirectToRoute("admin_category_list");
    }
}
