<?php


namespace App\Controller\Front;

use App\Entity\Like;
use App\Repository\LikeRepository;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    /**
     * @Route("/like/media_{id<\d+>}", name="media_like")
     */
    public function likeMedia(
        $id,
        MediaRepository $mediaRepository,
        EntityManagerInterface $entityManagerInterface,
        LikeRepository $likeRepository
    ) {
        $media = $mediaRepository->find($id);
        $user = $this->getUser();

        if (!$user) {
            return $this->json([
                'code' => 403,
                'message' => "Vous devez être connecté"
            ], 403);
        }

        if ($media->isLikedByUser($user)) {
            $like = $likeRepository->findOneBy([
                'media' => $media,
                'user' => $user
            ]);

            $entityManagerInterface->remove($like);
            $entityManagerInterface->flush();

            return $this->json([
                'code' => 200,
                'message' => "Le like a été supprimé",
                'likes' => $likeRepository->count(['media' => $media])
            ], 200);
        }

        $like = new Like();
        $like->setMedia($media);
        $like->setUser($user);

        $entityManagerInterface->persist($like);
        $entityManagerInterface->flush();

        return $this->json([
            'code' => 200,
            'message' => "Le like a été enregistré",
            'likes' => $likeRepository->count(['media' => $media])
        ], 200);
    }

}
