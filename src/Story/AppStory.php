<?php

namespace App\Story;

use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;

#[AsFixture(name: 'main')]
final class AppStory extends Story
{
    public function build(): void
    {
        DefaultAuthorStory::load();
        DefaultCategoryStory::load();
        DefaultMainImageIllustrationStory::load();
        DefaultArticleStory::load();

        dump('Scénario de peuplement général executé');
    }
}
