<?php

namespace App\Normalizer;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;



// Création d'un normalizer personnalisé: transformer des données paginées en array 

class PaginatorNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private readonly NormalizerInterface $normalizer

    ) {}

    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        // Cette méthode montre comment normaliser les données 
        if (!($object instanceof PaginationInterface)) {
            throw new \RuntimeException();
        }

        return [
            'items' => array_map(
                fn($item) => $this->normalizer->normalize($item, $format, $context),
                $object->getItems()
            ),
            'total' => $object->getTotalItemCount(),
            'page' => $object->getCurrentPageNumber(),
            'lastpage' => ceil($object->getTotalItemCount() / $object->getItemNumberPerPage())
        ];
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool

    {
        return $data instanceof PaginationInterface && $format === 'json'; // La normalisation surportera toutes les instances de Pagination et dont le format de sortie est le JSON 
    }

    // Cette méthode reçoit un type format et le transformat en tableau 
    public function getSupportedTypes(?string $format): array
    {
        return [
            PaginationInterface::class => true
        ];
    }
}
