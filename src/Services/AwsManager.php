<?php

namespace App\Services;

use App\Entity\Article;
use App\Entity\Demand;
use League\Flysystem\FilesystemOperator;

// Classe personnalisée pour gérer la lecture des fichiers se trouvant dans AWS S3
// L'objectif: récupérer le fichier objet et l'encoder en base 64 lisible par le navigateur
class AwsManager
{
    public ?FilesystemOperator $awsStorage = null;

    public function __construct(FilesystemOperator $awsStorage)
    {
        $this->awsStorage = $awsStorage;
    }

    public function readFile(Article $article)
    {
        try {
            $imageFileName = $article->getMainImageIllustration()->getImageName(); // Récupérer le nom du fichier depuis la base de données
            $file = $this->awsStorage->read($imageFileName); // Récupérer le fichier depuis le service AWS S3
            $file = base64_encode($file); // convertir le fichier recupéré en base 64
            $mimeType = $this->awsStorage->mimeType($article->getMainImageIllustration()->getImageName()); // Récuperer l'extension du fichier
            $file = "data:" . $mimeType . ";base64," . $file; // On assigne l'extension du fichier binaire au fichier encodé en base 64

            return $file;
        } catch (\Exception $e) {
            $e->getMessage();
        }
    }

    public function readCVFiles(Demand $demand)
    {
        $cvFileName = $demand->getCv(); // Récupérer le nom du fichier depuis la base de données
        $file = $this->awsStorage->read($cvFileName); // Récupérer le fichier depuis le service AWS S3
        $file = base64_encode($file); // convertir le fichier binaire recupéré en base 64
        $mimeType = $this->awsStorage->mimeType($demand->getCv()); // Récuperer l'extension du fichier
        $file = "data:" . $mimeType . ";base64," . $file; // On assigne l'extension du fichier binaire au fichier encodé en base 64

        return $file;
    }
}
