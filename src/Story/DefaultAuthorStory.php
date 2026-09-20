<?php

namespace App\Story;

use App\Factory\AuthorFactory;
use Zenstruck\Foundry\Story;

final class DefaultAuthorStory extends Story
{
    public function build(): void
    {
        // Créer 100 auteurs par défaut
        AuthorFactory::createMany(100);
        dump('Story Auteurs executée');
    }
}
