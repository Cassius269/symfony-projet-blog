<?php

namespace App\Story;

use App\Factory\MainImageIllustrationFactory;
use Zenstruck\Foundry\Story;

final class DefaultMainImageIllustrationStory extends Story
{
    public function build(): void
    {
        MainImageIllustrationFactory::createMany(10);

        dump('Story Images principales des articles executée');
    }
}
