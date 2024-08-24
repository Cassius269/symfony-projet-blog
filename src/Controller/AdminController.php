<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Demand;
use App\Entity\Notification;
use App\Form\DemandType;
use App\Repository\DemandRepository;
use App\Services\AwsManager;
use App\Services\Notificator;
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
    public function index(Request $request, EntityManagerInterface $entityManager, DemandRepository $demandRepository, Notificator $notificator): Response
    {
        if ($this->getUser()) { // Si un utilisateur est connecté, il ne peut pas envoyer demande et sera redirigé à la page d'accueil
            $this->addFlash('error', 'Vous ne pouvez pas soumettre une nouvelle demande de devenir membre');
            return $this->redirectToRoute('home');
        }

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
            // Vérifier si le demandeur a déjà fait une demande
            $lastdemand = $demandRepository->findOneBy([
                'email' => $demand->getEmail()
            ]);

            if ($lastdemand) { // Si une demande a déjà été effectuée par cet utilisateur, afficher un message d'erreur
                $this->addFlash('error', "Une demande vous concernant a déjà été enregistrée");
                return $this->redirectToRoute('admin_get_demand_members');
            }

            // Traitement des données recuillies
            $demand->setCreatedAt(new \DateTimeImmutable());
            // Persister et envoyer en base de données la demande
            $entityManager->persist($demand);

            $this->addFlash('success', 'Votre demande a été envoyée avec succès'); // Afficher un message de succès

            // Créer un objet notification à stocker en base de données pour rappel
            $notification = new Notification();
            $notification->setCreatedAt(new \DateTimeImmutable())
                ->setType('demand')
                ->setRead(false)
                ->setContent('Une nouvelle demande a été créé')
                ->setDemand($demand);
            // ->setAuthor(null);
            $entityManager->persist($notification);
            $entityManager->flush();

            // Envoyer une notification instantannée à l'Admin
            $notificator->send("Une nouvelle demande", "demand", $demand->getId(), $notification->getId());

            // Rediriger le demandeur à la page d'accueil
            return $this->redirectToRoute('home');
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

    // Action pour étudier une demande de devenir collaborateur en particulier
    #[Route(path: '/list-demands/{id}', name: 'detail_demand')]
    #[IsGranted('ROLE_ADMIN')] // Seul un utilisateur ayant le rôle "ROLE_ADMIN" à cette route
    public function giveResponseToDemand(Demand $demand, Request $request, EntityManagerInterface $entityManager, AwsManager $awsStorage): Response
    {
        dump($demand);
        $user = new User();

        $cvFile = $awsStorage->readCVFiles($demand);
        return $this->render("admin/demand_detail.html.twig", [
            'demand' => $demand,
            'cvFile' => $cvFile,
            // 'form' => $form
        ]);
    }
}
