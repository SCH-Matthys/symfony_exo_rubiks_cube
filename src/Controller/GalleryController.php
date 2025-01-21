<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Cubes;
use App\Form\CommentType;
use App\Form\CubeType;
use App\Repository\CategoryRepository;
use App\Repository\ColorsRepository;
use App\Repository\CubesRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security as SecurityBundleSecurity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Date;

final class GalleryController extends AbstractController
{
    #[Route('/gallery', name: 'app_gallery')]
    public function index(CubesRepository $cubesRepository, CategoryRepository $categoryRepository, ColorsRepository $colorsRepository): Response
    {
        $cubes = $cubesRepository->findAll();
        $colors = $colorsRepository->findAll();
        $category = $categoryRepository->findAll();
        return $this->render('gallery/gallery.html.twig', [
            "cubes"=>$cubes,
            "categorys"=>$category,
            "colors"=>$colors,
        ]);
    }

    #[Route('/gallery/add', name: 'app_galleryAdd')]
    public function add( Request $request, EntityManagerInterface $entityManagerInterface, Security $security): Response
    {
        $cube = new Cubes();

        $form = $this->createForm(CubeType::class, $cube);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user = $security->getUser();

            $cube->setUser($user);

            $entityManagerInterface->persist($cube);

            $entityManagerInterface->flush();

            $this->addFlash("success", "Votre cube à bien été ajouté à la collection.");

            return $this->redirectToRoute("app_gallery");
        }
        return $this->render('gallery/galleryAdd.html.twig', [
            "formCube" => $form->createView(),
        ]);
    }

    #[Route('/gallery/edit/{id}', name: 'app_galleryEdit')]
    public function edit( Cubes $cube, Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $form = $this->createForm(CubeType::class, $cube);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $entityManagerInterface->persist($cube);

            $entityManagerInterface->flush();

            $this->addFlash("success", "Votre cube à bien été modifié.");

            return $this->redirectToRoute("app_gallery");
        }
        return $this->render('gallery/galleryAdd.html.twig', [
            "formCube" => $form->createView(),
        ]);
    }

    #[Route('/gallery/delete/{id}', name: 'app_galleryDelete')]
    public function delete( Cubes $cube, Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        if($this->isCsrfTokenValid("SUP". $cube->getId(), $request->get("_token"))){
            $entityManagerInterface->remove($cube);
            $entityManagerInterface->flush();
            $this->addFlash("success", "Le cube a bien été supprimé de la collection.");
            return $this->redirectToRoute("app_gallery");
        }
    }

    #[Route('/gallery/cube/{id}', name: 'app_galleryCube')]
    public function cube( Cubes $cubes, Request $request, SecurityBundleSecurity $security, EntityManagerInterface $entityManagerInterface): Response
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user = $security->getUser();
            $cubeId = $cubes->getId();
            $date = new DateTime();
            // $cube = $cubes->getId();

            $comment->setUser($user)->setDate($date)->setCubes($cubeId);



            $entityManagerInterface->persist($comment);

            $entityManagerInterface->flush();

            $this->addFlash("success", "Votre commentaire à bien été posté.");

            return $this->redirectToRoute("app_galleryCube");
        }

        return $this->render('gallery/galleryCube.html.twig', [
            "cubes" => $cubes,
            "formComment" => $form,
        ]);
    }
}
