<?php

namespace App\Services;

use App\Repository\NotificationRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Mercure\HubInterface;

class Notificator
{
    // utilisation de l'interface de Mercure comme dépendance à injecter à chaque de nouvel objet de notificateur
    public ?HubInterface $hub = null;
    public ?Security $security = null;
    public ?NotificationRepository $notificationRepository = null;

    public function __construct(HubInterface $hub, Security $security, NotificationRepository $notificationRepository)
    {
        $this->hub = $hub;
        $this->security = $security;
        $this->notificationRepository = $notificationRepository;
    }

    // public function send(string $message, string $type, User $author)
    public function send(string $message, string $type, int $idObject): void
    {
        $authorFullName = $this->security->getUser()->getFullName();
        $numberOfNotifcations = count($this->notificationRepository->getUnreadNotifications());

        $update = new Update(
            'notifications',
            json_encode([
                'message' => $message . " par " . $authorFullName,
                'type' => $type,
                'author' => $authorFullName, // Attribuer l'auteur d'une notification comme étant l'utilisateur connecté faisant l'action à l'origin de la notification instantannée
                'idObject' => $idObject,
                'numberOfNotifcations' => $numberOfNotifcations,
            ])
        );
        $this->hub->publish($update);
    }
}
