<?php

namespace App\EventSubscriber;

use App\Repository\NotificationRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class NotificationSubscriber implements EventSubscriberInterface
{
    // Injection de dépendance du répertoire des notifications à déclenchement d'une action présente dans la classe
    public ?NotificationRepository $notificationRepository;
    public Security $security;

    public function __construct(NotificationRepository $notificationRepository, Security $security)
    {
        $this->notificationRepository = $notificationRepository;
        $this->security = $security;
    }

    // Action de récupération des notifications non  à chaque requête du navigateur et stockage dans la session en cours
    public function onRequestEvent(RequestEvent $event): void
    {
        // Si l'utilisateur est un Admin, mettre à jour la liste de notifications instantannées
        $username = $this->security->getUser();
        if ($username) {
            $rolesUser = $username->getRoles();
            if (! in_array('ROLE_ADMIN', $rolesUser)) { // si l'utilisateur n'a pas le rôle Admin, mettre fin à l'execution du code
                return;
            }

            // Récupérer la session
            $session = $event->getRequest()->getSession();
            // Rechercher les notifications non lues
            $unReadNotifications = $this->notificationRepository->getUnreadNotifications();
            // Stocker dans la session les notifications non lues
            $session->set("unReadNotifications", $unReadNotifications);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onRequestEvent', // A chaque requête dans l'application, executer l'action onRequestEvent()
        ];
    }
}
