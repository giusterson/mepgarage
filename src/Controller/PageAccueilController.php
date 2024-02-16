<?php

namespace App\Controller;

use App\Repository\ReparationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EtatOuvertureGarageRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Avis;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use App\Entity\User;

class PageAccueilController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(AvisRepository $avisRepository, ReparationRepository $reparationRepository, Request $request, ManagerRegistry $doctrine): Response
    {
        // Get reparations list to show it to user
         $reparation = $reparationRepository->findAll();
         // Get approved comments from avis Table.
         $avisList = $avisRepository->findBy(
            ['approved' => true]
        );
        // Show to user a create form for Avis.
        $manager = $doctrine->getManager();
        $avis = new Avis();
        $form = $this->createForm(AvisType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $avis = $form->getData();
            $avis->setApproved(false);
            $avis->setUser($this->getUser());
            $manager->persist($avis);
            $manager->flush();
            return $this->render('avis/show.html.twig',     
            ['avis' => $avis]);
        }
       return $this->render('page_accueil/index.html.twig', [
            'controller_name' => 'PageAccueilController',
            'aviss' => $avisList,
            'reparations' => $reparation,
			'formAvis' => $form->createView(),
            'isOnMainPage' => true
        ]);
    }

     /* #[Route('/', name: 'avis_index/create')]
    public function addAvis(Request $request, ManagerRegistry $doctrine)
    {
        $manager = $doctrine->getManager();
        $avis = new Avis();
        $form = $this->createForm(AvisType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $avis = $form->getData();
            $manager->persist($avis);
            $manager->flush();
            return $this->render('avis/show.html.twig', 
            ['avis' => $avis]);
        }
        return $this->render('avis/create.html.twig' , [
            'formAvis' => $form->createView(),
            'isEditMode' => false

        ]);
       
    }  */


}
