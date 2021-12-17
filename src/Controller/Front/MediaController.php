<?php


namespace App\Controller\Front;

use App\Repository\MediaRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MediaController extends AbstractController
{
    
    // liste de toutes les images - + page acceuil
    public function Mediaslist(MediaRepository $mediaRepository)
    {
        $media = $mediaRepository->findAll();

        return $this->render('front/medialist.html.twig', [
            'medias' => $media,
        ]);
    }


    // Page image (unique)
    public function MediaId(MediaRepository $mediaRepository, $id)
    {
        $media = $mediaRepository->find($id);

        return $this->render('front/showmedia.html.twig', [
            "media" => $media,

        ]);
    }

    // bare de recherche

    /**
     * @Route("/front/search", name="front_search")
     */
    public function frontSearch(MediaRepository $mediaRepository, Request $request)
    {

        //Recuperer les données rentré dans le formulaire 
        $term = $request->query->get('term'); // get est le name du champs

        $medias = $mediaRepository->searchByTerm($term);

        return $this->render('front/search.html.twig', [
            "medias" => $medias
        ]);

    }
}
