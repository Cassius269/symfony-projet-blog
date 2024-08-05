<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Demand;
use App\Form\UserType;
use App\Form\DemandType;
use App\Repository\DemandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin', name: 'admin_')] // Préfixe des routes de l'Admin
class AdminController extends AbstractController
{
    // Action pour faire une demande de devenir contributeur
    #[Route(
        path: '/becoming-member',
        name: 'get_demand_members',
    )]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Construction du formulaire
        $demand = new Demand();
        $form = $this->createForm(DemandType::class, $demand)
            ->add('rgpd', CheckboxType::class, [
                'mapped' => false,
                'label' => 'J\'accepte que mes données soient utilisées pour la gestion de la demande, et que je serais amené à être appelé pour une étude approfondie de la demande'
            ])
            ->remove('decision')
            ->remove('status');

        $form->handleRequest($request); // Tenir la requête de soumission du formulaire

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form);
            // Traitement des données recuillies
            $demand->setCreatedAt(new \DateTimeImmutable());
            // $demand->setUser(null);
            // Persister et envoyer en base de données la demande
            $entityManager->persist($demand);
            $entityManager->flush();

            $this->addFlash('success', 'Votre demande a été envoyée avec succès'); // Afficher un message de succès

            return $this->redirectToRoute('home'); // Renvoyer le demandeur à la page d'accueil
        }

        return $this->render('admin/form_demand_becoming_member.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Action pour afficher la liste de toutes les demandes

    #[Route(path: '/list-demands', name: 'list_demands')]
    #[IsGranted('ROLE_ADMIN')] // Seul un utilisateur ayant le rôle "ROLE_ADMIN" à cette route
    public function showAllDemands(DemandRepository $demandRepository): Response
    {
        // Chercher toutes les demandes
        $demands = $demandRepository->findAll();

        if (!$demands) { // Si il n'y pas de demande, envoyer un message d'exception
            throw $this->createNotFoundException('Aucune demande enregistrée à ce jour');
        }

        return $this->render('admin/show_all_demands.html.twig', [
            'demands' => $demands,
        ]);
    }

    #[Route(path: '/list-demands/{id}', name: 'detail_demand')]
    #[IsGranted('ROLE_ADMIN')] // Seul un utilisateur ayant le rôle "ROLE_ADMIN" à cette route
    public function giveResponseToDemand(Demand $demand, Request $request, EntityManagerInterface $entityManager): Response
    {
        dump($demand);
        $user = new User();

        // Personnaliser l'affichage du formulaire lié à l'utilisateur
        // $form = $this->createForm(UserType::class, $user);

        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        // Traitement du formulaire
        // }
        return $this->render("admin/demand_detail.html.twig", [
            'demand' => $demand,
            // 'form' => $form
        ]);
    }
}
