<?php

namespace App\Controller;

use App\Entity\Demand;
use App\Form\DemandType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route(
        path: '/becoming-member',
        name: 'get_demand_members',
        // methods: ['POST']
    )]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Création du formulaire
        $demand = new Demand();
        $form = $this->createForm(DemandType::class, $demand)
            ->add('rgpd', CheckboxType::class, [
                'mapped' => false,
                'label' => 'J\'accepte que mes données soient utilisées pour la gestion de la demande, et que je serais amené à être appelé pour une étude approfondie de la demande'
            ])
            ->remove('decision')
            ->remove('status');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form);
            // Traitement des données recuillies
            $demand->setCreatedAt(new \DateTimeImmutable());
            // $demand->setUser(null);
            // Persister et envoyer en base de données la demande
            $entityManager->persist($demand);
            $entityManager->flush();

            $this->addFlash('success', 'Votre demandé a été envoyé avec succès'); // Afficher un message de succès

            return $this->redirectToRoute('home'); // Renvoyer le demandeur à la page d'accueil
        }

        return $this->render('admin/form_demand_becoming_member.twig', [
            'form' => $form->createView(),
        ]);
    }
}
