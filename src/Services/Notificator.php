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
    public function send(string $message, string $type, int $idObject, int $idNotif): void
    {
        // On récupère l'utilisateur connecté et son nom complet
        if ($this->security->getUser()) { // Dans le cas où l'utilisateur est connecté
            $author = $this->security->getUser();
            $authorFullName = $author->getFullName();
        } else { // Sinon si l'utilisateur n'est pas connecté
            $authorFullName = "utilisateur non connecté";
        }

        $unReadNotifications = count($this->notificationRepository->getUnreadNotifications());

        $update = new Update(
            'notifications',
            json_encode([
                'message' => $message . " par " . $authorFullName,
                'type' => $type,
                'author' => $authorFullName, // Attribuer l'auteur d'une notification comme étant l'utilisateur connecté faisant l'action à l'origine de la notification instantannée
                'idObject' => $idObject,
                'idNotif' => $idNotif,
                'unReadNotifications' => $unReadNotifications,
            ])
        );
        $this->hub->publish($update);
    }
}
