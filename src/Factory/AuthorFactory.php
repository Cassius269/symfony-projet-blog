<?php

namespace App\Factory;

use App\Entity\Author;
use App\Services\PasswordUtilityService;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;


/**
 * @extends PersistentObjectFactory<Author>
 */
final class AuthorFactory extends PersistentObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct(private PasswordUtilityService $passwordUtilityService) {}

    #[\Override]
    public static function class(): string
    {
        return Author::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'email' => self::faker()->unique()->email(),
            'firstname' => self::faker()->firstName(),
            'lastname' => self::faker()->lastName(),
            'password' => self::faker()->password(6, 12),
            'roles' => ['ROLE_AUTHOR'],
            'accepted' => self::faker()->boolean()
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function (Author $author): void {
                $author->setPassword($this->passwordUtilityService->getAhashedPassword($author));
            });
    }
}
