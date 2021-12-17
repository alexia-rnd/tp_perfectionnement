<?php 

namespace App\Globals;

use App\Repository\CategoryRepository;



class Category
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }


    public function getAll()
    {
        $gcategory = $this->categoryRepository->findAll();

        return $gcategory;
    }
}