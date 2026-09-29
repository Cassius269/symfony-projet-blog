<?php

namespace App\Tests\Functional\Controller;

use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class HomeTest extends WebTestCase
{
    #[Test]
    public function it_display_right_homepage_slogan(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
        $this->assertSelectorTextContains(
            'h1', // selector CSS
            'FAHAMI le site des tendances mode et bien +', // contenu de la balise H1
            'Titre non trouvé' // message en cas d'échec du test
        );
    }

    #[Test]
    public function it_clicks_on_main_article_from_homepage(): void
    {
        // Instancier le navigateur
        $client = static::createClient();
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);

        // Faire une requête vers la page d'accueil
        $crawler = $client->request('GET', $urlGenerator->generate('home'));

        $this->assertResponseIsSuccessful();

        // Obtenir le lien vers l'article principale
        $link = $crawler
            ->selectLink("Lire l'article maintenant !")
            ->link();

        $crawler = $client->click($link);


        $title = $crawler->filter('h1')->text();
        $title = trim(preg_replace('/\s\s+/', ' ', $title));

        // Vérifier que le titre de l'article principal est celui attendu avec le maximum de lectures
        $this->assertSame(
            "Je suis un titre d'article de 400 vues",
            $title
        );
    }
}
