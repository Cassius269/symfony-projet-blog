<?php

namespace App\Form;

use App\Entity\Photographer;
use App\Entity\MainImageIllustration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Validator\Constraints as Assert;


class MainImageIllustrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
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
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MainImageIllustration::class,
        ]);
    }
}
