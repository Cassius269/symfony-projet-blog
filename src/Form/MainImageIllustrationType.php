<?php

namespace App\Form;

use App\Entity\Photographer;
use App\Entity\MainImageIllustration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MainImageIllustrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'image'
            ])
            ->add('imageFilename')
            ->add('source', TextType::class)
            ->add('photographer', PhotographerType::class, [
                'label' => 'Photographe'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MainImageIllustration::class,
        ]);
    }
}
