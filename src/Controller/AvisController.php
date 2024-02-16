<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AvisRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Avis;
use App\Form\AvisType;

class AvisController extends AbstractController
{
    #[Route('/avis', name: 'app_avis')]
    public function index(AvisRepository $repo): Response
    {
        
        $aviss = $repo->findAll();
        return $this->render('avis/index.html.twig', [
            'controller_name' => 'AvisController',
            'avis' => $aviss
        ]);
    }
    /*  La méthode create de l'entité Avis est dans ce fichier src/Controller/PageAccueilController.php 
        Cette méthode devrait être ici-même en temps normal mais l'énoncé du devoir m'a contraint de la mettre dans PageAccueilController.php.
        De plus, on y retrouve seulement le contenu de la méthode dans la fonction "index" de ce fichier et non pas sa signature.
    */


    #[Route('/avis/edit/{id}', name: 'avis_edit')]
    public function editAvis(Avis $avis, Request $request, ManagerRegistry $doctrine) {
        $manager = $doctrine->getManager();
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérons les données du véhicule créé dans le create form.
            $avisData = $form->getData();
            $manager->persist($avisData);
            $manager->flush();
            return $this->render('avis/show.html.twig', 
            ['avis' => $avis]);
        }
        return $this->render('avis/edit.html.twig' , [
            'formAvis' => $form->createView(),
            'isEditMode' => $avis->getId() !== null
        ]);

    }
        

    #[Route('/avis/delete/{id}', name: 'avis_delete')]
    public function deleteAvis(EntityManagerInterface $entityManager, int $id)
    {
        //On vérifie si l'utilisateur peut supprimer avec le Voter
        $this->denyAccessUnlessGranted('ROLE_ADMIN');


        $avis = $entityManager->getRepository(Avis::class)->find($id);

        if (!$avis) {
            throw $this->createNotFoundException(
                'No avis found for id '.$id
            );
        }
        $entityManager->remove($avis);
        $entityManager->flush();
        return $this->render('avis/index.html.twig', [
            'controller_name' => 'AvisController'
        ]);
                    
    }

    #[Route('/avis/admin', name: 'admin_avis')]
    public function adminAvis(AvisRepository $repo): Response
    {
        
        $aviss = $repo->findAll();
        
        return $this->render('avis/indexAdmin.html.twig', [
            'controller_name' => 'AvisController',
            'aviss' => $aviss
        ]);
    }
    

    #[Route('/avis/{id}', name: 'avis_show')]
    public function show(AvisRepository $repo, $id): Response
    {
        if ($id) {
            $avis = $repo->find($id);
            if (!$avis) {
                return $this->redirectToRoute('app_avis');
            } else {
                       return $this->render('avis/show.html.twig', [
                        'controller_name' => 'AvisController',
                        'avis' => $avis
            ]);

        }
        } else {
            return $this->redirectToRoute('app_avis');
        }
        
    }

   


    
}
