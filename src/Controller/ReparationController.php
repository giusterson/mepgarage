<?php

namespace App\Controller;

use App\Entity\Reparation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReparationRepository;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\ReparationType;

class ReparationController extends AbstractController
{
    #[Route('/reparation', name: 'app_reparation')]
    public function index(ReparationRepository $repo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $reparations = $repo->findAll();
        return $this->render('reparation/index.html.twig', [
            'controller_name' => 'ReparationController',
            'reparations' => $reparations
        ]);
    }
    
    #[Route('/reparation/new', name: 'reparation_create')]
    public function addReparation(Request $request, ManagerRegistry $doctrine)
    {
        $manager = $doctrine->getManager();
        $reparation = new Reparation();
        $form = $this->createForm(ReparationType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reparation = $form->getData();
            $manager->persist($reparation);
            $manager->flush();
            return $this->render('reparation/show.html.twig',
            ['reparation' => $reparation]);
        }
        return $this->render('reparation/create.html.twig' , [
            'formReparation' => $form->createView(),
            'isEditMode' => false

        ]);
       
    }

    #[Route('/reparation/edit/{id}', name: 'reparation_edit')]
    public function editReparation(Reparation $reparation, Request $request, ManagerRegistry $doctrine) {
        $manager = $doctrine->getManager();
        $form = $this->createForm(ReparationType::class, $reparation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérons les données du véhicule créé dans le create form.
            $reparationData = $form->getData();
            $manager->persist($reparationData);
            $manager->flush();
            return $this->render('reparation/show.html.twig', 
            ['reparation' => $reparation]);
        }
        return $this->render('reparation/create.html.twig' , [
            'formReparation' => $form->createView(),
            'isEditMode' => $reparation->getId() !== null
        ]);
        
    }

    #[Route('/reparation/delete/{id}', name: 'delete_reparation')]
    public function delete(EntityManagerInterface $entityManager,ReparationRepository $repo, int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $reparations = $repo->findAll();

        $reparation = $entityManager->getRepository(Reparation::class)->find($id);

        if (!$reparation) {
            throw $this->createNotFoundException(
                'No reparation found for id '.$id
            );
        }

        $entityManager->remove($reparation);
		$entityManager->flush();

        return $this->render('reparation/indexAdmin.html.twig', [
            'reparations'=> $reparations
        ]);
    }
    // Seul l'admin peut ajouter, modifier ou supprimer une réparation.
    #[Route('/reparation/admin', name: 'admin_reparation')]
    public function adminReparation(ReparationRepository $repo): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $reparations = $repo->findAll();
        return $this->render('reparation/indexAdmin.html.twig', [
            'reparations'=> $reparations
        ]);
    }
    
}

