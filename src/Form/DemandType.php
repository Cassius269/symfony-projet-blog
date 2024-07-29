<?php

namespace App\Form;

use App\Entity\Demand;
use App\Entity\Status;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DemandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Jean'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom de famille',
                'attr' => [
                    'placeholder' => 'DUPONT'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'jeandupont@email.com'
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Parlez-nous de vous',
                'attr' => [
                    'placeholder' => 'Votre parcours par exemple...',
                ]
            ])
            ->add(
                'cvFile',
                VichFileType::class,
                [
                    'label' => 'Uploader votre CV',
                    'required' => true, // Uplod de CV obligatoire
                    'label_attr' => [
                        'id' => 'labelCvFile'
                    ]
                    //     'constraints' => ([
                    //         new Assert\NotNull() => [ // Ajout d'une contrainte sur le champs du CV pour que le CV soit chargé obligatoirement
                    //             'message' => 'Le CV est obligatoire'
                    //         ]
                    //     ]),
                    //     new Assert\File() => [
                    //         'mimeTypes' => [
                    //             'application/pdf'
                    //         ],
                    //         'mimeTypesMessage' => ['Votre fichier n\'est pas au bon format']
                    //     ]
                ]
            )
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'attr' => [
                    'placeholder' => '06********'
                ]
            ])
            ->add('decision')
            ->add('status', EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'id',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Demand::class,
        ]);
    }
}
