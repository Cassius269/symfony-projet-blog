<?php

namespace App\Story;

use App\Factory\ArticleFactory;
use Zenstruck\Foundry\Story;

final class DefaultArticleStory extends Story
{
    public function build(): void
    {
        ArticleFactory::createMany(30);

        ArticleFactory::createOne([
            'title' => "Je suis un titre specifique d'un article spécifique"
        ]);

        dump('Story Articles executée');
    }
}
