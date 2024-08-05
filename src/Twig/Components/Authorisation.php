<?php

namespace App\Twig\Components;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsLiveComponent]
final class Authorisation extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    protected function instantiateForm(): FormInterface
    {
        $user = new User();
        // Création du formulaire dynamique pour ajout des champs dynamique dans la page de traitement de la demande
        return $this->createForm(UserType::class, $user);
    }


    // Methode test pour traiter la requête du formulaire
    #[LiveAction]
    public function save(Request $request, EntityManagerInterface $entityManager)
    {
        $this->form->handleRequest($request);
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            dd("hello");

            $user = $this->form->getData();

            // Persist the user
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'La demande a été traitée');
            return $this->redirectToRoute('home');
        }

        return $this->redirectToRoute('admin_detail_demand');
    }
}
