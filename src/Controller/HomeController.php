<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Fichier;
use App\Form\FichierType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_homepage')]
    public function add(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger): Response
    {
        $file = new Fichier();
        $form = $this->createForm(FichierType::class, $file);
        $form->handleRequest($request);

        $repository = $doctrine->getRepository(Fichier::class);
        $fichier = $repository->findAll();


        if ($form->isSubmitted() && $form->isValid()) {
            
            $fichierTelecharge = $form->get('name')->getData();

            if ($fichierTelecharge) {
                $originalFilename = pathinfo($fichierTelecharge->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                //$newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
                $newFilename = $safeFilename.'.'.$fichierTelecharge->guessExtension();

                try {
                    $fichierTelecharge->move(
                        $this->getParameter('file_telecharge_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $file->setName($newFilename);
            }

            $courrent = $this->getUser();
            $file->setUser($courrent);

            $em = $doctrine->getManager();
            $em->persist($file);
            $em->flush();
            $this->addFlash('success', 'Votre fichier a bien été telechargé.');
            return $this->redirectToRoute('app_homepage');
        }
        else{
            return $this->renderForm('home/index.html.twig', [
            'form' => $form,
            'fichier'=>$fichier,
            ]);
        }
        
    }
    
}
