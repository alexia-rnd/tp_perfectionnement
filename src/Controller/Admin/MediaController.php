<?php


namespace App\Controller\Admin;

use App\Entity\Media;
use App\Entity\Category;
use App\Form\MediaType;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MediaController extends AbstractController
{
    /**
    * @Route("admin/media_list", name="admin_media_list")
    */

    public function AdminMediaslist(MediaRepository $mediaRepository)
    {
        $media = $mediaRepository->findAll();

        return $this->render('admin/medialist.html.twig', [
            'medias' => $media,
        ]);
    }


    /**
    * @Route("admin/media_{id<\d+>}", name="admin_media_id")
    */
    public function AdminMediaId(MediaRepository $mediaRepository, $id)
    {
        $media = $mediaRepository->find($id);

        return $this->render('admin/showmedia.html.twig', [
            "media" => $media,

        ]);
    }

    /**
     * @Route("/admin/media_create", name="admin_media_create")
     */
    public function AdminMediaCreate(Request $request, EntityManagerInterface $manager)
    {
        $media = new Media();
        $mediaForm = $this->createForm(MediaType::class, $media);

        $mediaForm->handleRequest($request);

        if( $mediaForm->isSubmitted() && $mediaForm->isValid())
        {
            $manager->persist($media);
            $manager->flush();

            return $this->redirectToRoute('admin_media_list');
        }

        return $this->render("admin/form_media.html.twig", ["mediaForm" => $mediaForm->createView()]);
    }

    
    // ADMIN - UPDATE

    /**
     * @Route("admin/media_update_{id<\d+>}", name="admin_media_update")
     */
    public function AdminMediaUpdate($id, MediaRepository $mediaRepository, EntityManagerInterface $entityManagerInterface, Request $request
    ) {
        $media = $mediaRepository->find($id);
        $mediaForm = $this->createForm(MediaType::class, $media);

        $mediaForm->handleRequest($request);

        if ($mediaForm->isSubmitted() && $mediaForm->isValid()) {
            $entityManagerInterface->persist($media);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('admin_media_list');
        }

        return $this->render("admin/form_media.html.twig", ["mediaForm" => $mediaForm->createView()]);
    }

    //DELETE 
    /**
     * @Route("admin/media_delete_{id<\d+>}", name="admin_media_delete")
     */
    public function AdminMediaDelete($id, MediaRepository $mediaRepository, EntityManagerInterface $entityManagerInterface)
    {
        $media = $mediaRepository->find($id);
        $entityManagerInterface->remove($media); 
        $entityManagerInterface->flush();
        $this->addFlash(
            'notice',
            'L\'image a été supprimé'
        );

        return $this->redirectToRoute("admin_media_list");
    }
}
