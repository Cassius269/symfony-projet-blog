<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Article;
use App\Entity\Author;
use App\Entity\Category;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AuthorTest extends TestCase
{

    #[Test]
    public function it_creates_an_author_with_given_values(): void
    {
        // Arrange
        $firstname = 'Jean';
        $lastname = 'DUPONT';
        $email = "j-dp@test.com";
        $role = 'ROLE_AUTHOR';
        $hashedPassword = '$2y$13fake-hash-for-a-unit-test';
        $now = new DateTimeImmutable('2026-01-01 12:00:00');
        $isAccepted = false;

        // Act
        $author = new Author();
        $author->setFirstname($firstname)
            ->setLastname($lastname)
            ->setEmail($email)
            ->setRoles([$role])
            ->setPassword($hashedPassword)
            ->setCreatedAt($now)
            ->setAccepted($isAccepted);

        // Assert
        $this->assertNull($author->getId());
        $this->assertSame($firstname, $author->getFirstname());
        $this->assertSame($lastname, $author->getLastname());
        $this->assertSame($email, $author->getEmail());
        $this->assertSame($hashedPassword, $author->getPassword());
        $this->assertContains('ROLE_AUTHOR', $author->getRoles());
        $this->assertSame($now, $author->getCreatedAt());
        $this->assertSame($isAccepted, $author->isAccepted());
    }


    #[Test]
    public function it_adds_an_article_to_an_author(): void
    {
        // Arrange
        $categoryName = 'Roman';
        $title = 'Lorem ipsum';
        $now = new DateTimeImmutable('2026-01-01 12:00:00');
        $content = 'Lorem Lorem Lorem Lorem Lorem';

        // Act
        $author = new Author();
        $author->setFirstname('Jean')
            ->setLastname('DUPONT')
            ->setEmail("j-dp@test.com")
            ->setRoles(['ROLE_AUTHOR'])
            ->setPassword('$2y$13fake-hash-for-a-unit-test')
            ->setCreatedAt($now)
            ->setAccepted(false);

        $category = new Category();
        $category->setName($categoryName);

        $article = new Article();
        $article->setCategory($category)
            ->setTitle($title)
            ->setContent($content)
            ->setCreatedAt($now);
        $author->addArticle($article);

        // Assert
        $this->assertSame(
            $author, // valeur attendue
            $article->getAuthor(), // valeur réelle
            'Auteur introuvable ou non similaire'
        );

        $this->assertContains(
            $article,
            $author->getArticles(),
            'L\'article devrait être présent dans la collection de l\'auteur.'
        );

        $this->assertCount(
            1,
            $author->getArticles(),
            'L\'auteur devrait avoir uniqument un seul article'
        );
    }

    #[Test]
    public function it_removes_an_article_from_an_author(): void
    {
        // Arrange
        $categoryName = 'Roman';
        $title = 'Lorem ipsum';
        $now = new DateTimeImmutable('2026-01-01 12:00:00');
        $content = 'Lorem Lorem Lorem Lorem Lorem';

        // Act
        $author = new Author();
        $author->setFirstname('Jean')
            ->setLastname('DUPONT')
            ->setEmail("j-dp@test.com")
            ->setRoles(['ROLE_AUTHOR'])
            ->setPassword('$2y$13fake-hash-for-a-unit-test')
            ->setCreatedAt($now)
            ->setAccepted(false);

        $category = new Category();
        $category->setName($categoryName);

        $article = new Article();
        $article->setCategory($category)
            ->setTitle($title)
            ->setContent($content)
            ->setCreatedAt($now);
        $author->addArticle($article);

        $author->removeArticle($article);

        // Assert
        $this->assertNull(
            $article->getAuthor(), // valeur réelle null
            'Auteur supprimé de l\'article'
        );

        $this->assertFalse($author->getArticles()->contains($article), 'L\'article devrait être absent dans la collection de l\'auteur.');

        $this->assertCount(0, $author->getArticles(), 'L\'auteur ne devrait pas avoir un article');
    }

    #[Test]
    public function it_does_not_add_same_article_twice(): void
    {
        // Arrange
        $categoryName = 'Roman';
        $title = 'Lorem ipsum';
        $now = new DateTimeImmutable('2026-01-01 12:00:00');
        $content = 'Lorem Lorem Lorem Lorem Lorem';

        // Act
        $author = new Author();
        $author->setFirstname('Jean')
            ->setLastname('DUPONT')
            ->setEmail("j-dp@test.com")
            ->setRoles(['ROLE_AUTHOR'])
            ->setPassword('$2y$13fake-hash-for-a-unit-test')
            ->setCreatedAt($now)
            ->setAccepted(false);

        $category = new Category();
        $category->setName($categoryName);

        $article = new Article();
        $article->setCategory($category)
            ->setTitle($title)
            ->setContent($content)
            ->setCreatedAt($now);
        $author->addArticle($article);
        $author->addArticle($article);

        // Assert
        $this->assertCount(1, $author->getArticles(), 'Article en doublon présent');
    }
}
