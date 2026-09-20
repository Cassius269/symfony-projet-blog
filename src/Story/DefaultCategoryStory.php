<?php

namespace App\Story;

use App\Factory\CategoryFactory;
use Zenstruck\Foundry\Story;

final class DefaultCategoryStory extends Story
{
    public function build(): void
    {
        CategoryFactory::createMany(3);
        dump('Story Catégories executée');
    }
}
