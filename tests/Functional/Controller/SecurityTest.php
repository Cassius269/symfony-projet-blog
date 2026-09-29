<?php

namespace App\Tests\Functional\Controller;

use App\Entity\User;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class SecurityTest extends WebTestCase
{
    #[Test]
    public function it_logs_in_a_user(): void
    {
        // Instancier le navigateur
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        // Vérifier que la requête vers la page de login a réussi
        $this->assertResponseIsSuccessful();

        // Récupérer et remplir le formulaire le formulaire
        $form = $crawler->filter('form')->form([
            '_username' => 'jean-dupont@test.com',
            '_password' => '123456789',
        ]);

        // Soumettre le formulaire
        $client->submit($form);
        // dd($client->getRequest());

        // Assertions

        $this->assertResponseRedirects( // Vérifier si la réponse obtenue est une redirection vers la page d'accueil
            '/',
            302,
            'Connexion non réussie. Redirection vers la page d\'accueil échouée'
        );

        //Faire une deuxième requête automatique vers la page d'accueil en cas de connexion réussie
        $crawler = $client->followRedirect();

        $this->assertResponseIsSuccessful();
    }

    #[Test]
    public function it_logs_out_a_user(): void
    {
        // Créer le navigateur
        $client = static::createClient();

        // Connecter l'utilisateur
        $userRepository = $client->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository(User::class);

        $testUser = $userRepository->findOneByEmail('jean-dupont@test.com');
        $this->assertNotNull($testUser, 'Utilisateur de test introuvable en base');
        $client->loginUser($testUser);
        // dd($testUser);

        // Récupérer la requête
        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();

        // Cliquer sur le bouton déconnecter
        $crawler = $client->clickLink('Se déconnecter');
        $this->assertResponseRedirects(
            '/',
            message: 'Echec de la déconnexion et de la redirection'
        );

        $client->followRedirect();

        $this->assertResponseIsSuccessful();
    }
}
