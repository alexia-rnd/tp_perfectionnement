<?php

namespace App\Controller\Front;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{

        public function Categorylist(CategoryRepository $categoryRepository)
        {
            $category = $categoryRepository->findAll();
    
            return $this->render('front/categorylist.html.twig', [
                'categorys' => $category,
            ]);
        }
    
    
        public function CategoryId(CategoryRepository $categoryRepository, $id)
        {
            $category = $categoryRepository->find($id);
    
            return $this->render('front/showcategory.html.twig', [
                "category" => $category,
    
            ]);
        }
}
