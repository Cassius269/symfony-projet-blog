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
            'title' => "Je suis un titre specifique d'un article spécifique",
            'nbreOfViews' => 100
        ]);

        ArticleFactory::createOne([
            'title' => "Je suis un titre d'article de 400 vues",
            'nbreOfViews' => 400
        ]);

        dump('Story Articles executée');
    }
}
