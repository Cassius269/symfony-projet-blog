<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Mercure\HubInterface;

class Notificator
{
    // utilisation de l'interface de Mercure comme dépendance à injecter à chaque de nouvel objet de notificateur
    public ?HubInterface $hub = null;

    public function __construct(HubInterface $hub)
    {
        $this->hub = $hub;
    }

    public function send(string $message, string $type, User $author)
    {
        $update = new Update(
            'notifications',
            json_encode([
                'status' => $message,
                'type' => $type,
                'author' => $author
            ])
        );

        $this->hub->publish($update);
    }
}
