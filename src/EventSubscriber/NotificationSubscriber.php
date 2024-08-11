<?php

namespace App\EventSubscriber;

use App\Repository\NotificationRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class NotificationSubscriber implements EventSubscriberInterface
{
    // Injection de dépendance du répertoire des notifications à déclenchement d'une action présente dans la classe
    public ?NotificationRepository $notificationRepository;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    // Action de récupération du nombre de notifications non et assignation dans la session en cours
    public function onRequestEvent(RequestEvent $event): void
    {
        $session = $event->getRequest()->getSession();
        $unReadNotifications = $this->notificationRepository->getUnreadNotifications();

        $session->set("unReadNotifications", $unReadNotifications);
        dump($session);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onRequestEvent', // A chaque requête dans l'application, executer l'action onRequestEvent()
        ];
    }
}