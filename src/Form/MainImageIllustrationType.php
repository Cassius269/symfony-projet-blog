<?php

namespace App\Form;

use App\Entity\MainImageIllustration;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class MainImageIllustrationType extends AbstractType
{
    // Déclaration de la requestStack pour manipuler, récuperer des données liés à la requête d'un auteur d'article
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    // Construction du formulaire de l'image d'illustration d'un article
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $request = $this->requestStack->getCurrentRequest(); // Obtenir l'objet de la requête actuelle
        $routeName = $request->get('_route'); // Obtenir le nom de la route actuelle dans la page contenant le formulaire

        $builder->add('title', TextType::class, [
            'label' => 'Titre de l\'image',
            'attr' => [
                'placeholder' => 'Titre de l\'image chargée'
            ]
        ])
            ->add('source', TextType::class, [
                'label' => 'Source de l\'image',
                'attr' => [
                    'placeholder' => 'Citez la source de l\'image si l\'image provient du web'
                ],
                'help' => 'Vous devez possèder les droits d\'exploitation pour publier une image'
            ])
            ->add('imageFile', VichFileType::class, [
                'label' => false,
                'constraints' => [
                    new Assert\Image(
                        [
                            'minWidth' => 400,
                            'minHeight' => 600,
                            'minWidthMessage' => 'Veuillez insérer une image plus large',
                            'minHeightMessage' => 'Veuillez insérer une image plus grande en hauteur',
                            'allowLandscape' => true,
                            'allowPortrait' => false,

                            'mimeTypes' =>  [
                                'image/png',
                                'image/jpeg',
                                'image/jpg',
                                'image/webp',
                            ],
                            'mimeTypesMessage' => 'Le fichier chargé n\'est pas au bon format'
                        ]
                    ),
                    new Assert\NotNull(
                        [
                            'message' => 'L\'image d\'illustration est obligatoire'
                        ]
                    )
                ]
            ])
            ->add('photographer', PhotographerType::class, [
                'label' => 'Photographe', // Label du formulaire imbriqué
                'label_attr' => [
                    'id' => 'labelOfphotographer'
                ]
            ]);

        // Si on est dans une page de mise à jour d'article, rendre non obligatoire l'ajout d'une image
        if ($routeName == 'articles_update') {
            $builder->remove('imageFile') // Supprimer l'input initial de l'image d'illustration quand un auteur est en mode mise à jour d'un article
                ->add('imageFile', VichFileType::class, [
                    'label' => false,
                    'required' => false, // Rendre l'input d'image d'illustration non obligatoire
                    'constraints' => [
                        new Assert\Image(
                            [
                                'minWidth' => 400,
                                'minHeight' => 600,
                                'minWidthMessage' => 'Veuillez insérer une image plus large',
                                'minHeightMessage' => 'Veuillez insérer une image plus grande en hauteur',
                                'allowLandscape' => true,
                                'allowPortrait' => false,

                                'mimeTypes' =>  [
                                    'image/png',
                                    'image/jpeg',
                                    'image/jpg',
                                    'image/webp',
                                ],
                                'mimeTypesMessage' => 'Le fichier chargé n\'est pas au bon format'
                            ]
                        )
                    ]
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MainImageIllustration::class,
        ]);
    }
}
