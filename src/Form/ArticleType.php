<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\MainImageIllustration;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'article',
                'attr' => [
                    'placeholder' => 'Le titre de l\'article',
                ]
            ])
            ->add(
                'mainImageIllustration',
                MainImageIllustrationType::class,
                [
                    'label' => 'Informations sur l\'image principale',
                    'label_attr' => [
                        'class' => 'labelOfImageIllustrionFormType'
                    ]
                ]
            )
            // ->add('imageIllustrationFile', VichFileType::class, [

            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Catégorie de l\'article'
            ])
            ->add('content', HiddenType::class)
            ->add(
                'abstract',
                TextareaType::class,
                [
                    'label' => 'Résumé de l\'article en quelques lignes',
                    'attr' => [
                        'placeholder' => 'Ecrire un bref résumé de l\'article'
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
