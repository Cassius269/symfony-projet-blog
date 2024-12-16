<?php

namespace App\Services;

use App\Entity\Article;
use Symfony\Component\String\Slugger\SluggerInterface;

class Sluggificator
{
    public function __construct(private SluggerInterface $slugger) {}


    public function getSluggifiedTitle(?Article $article): string
    {
        $title = $article->getTitle();
        $slugTitle = $this->slugger->slug($title)->lower(); // Slugfication du titre de l'article avant persistance en base de données

        return $slugTitle;
    }
}
