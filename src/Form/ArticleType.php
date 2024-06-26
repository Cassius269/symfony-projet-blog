<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('imageIllustration', FileType::class, [
                'label' => 'Image d’illustration  principale',
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
                                'image/jpg'
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
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Catégorie'
            ])
            ->add('content', HiddenType::class)
            ->add('Submit', SubmitType::class, [
                'label' => 'Publier',
                'attr' => [
                    'class' => 'primaryButton'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
