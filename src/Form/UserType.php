<?php

namespace App\Form;

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfonycasts\DynamicForms\DependentField;
use Symfony\Component\Form\FormBuilderInterface;
use Symfonycasts\DynamicForms\DynamicFormBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder = new DynamicFormBuilder($builder); // Rendre le builder dynamique et interactif

        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom de famille'
            ])
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe'
            ])
            // Rendre le formulaire dynamique par l'ajout de champs dynamiques
            ->add('authorisations', ChoiceType::class, [
                'label' => 'Les autorisations accordées:',
                'mapped' => false,
                'choices' => [
                    'Peut écrire un article' => 1,
                    'Peut publier une émission' => 2
                ],
                'label_attr' => [
                    'id' => 'labelAuthorisations',
                ],
                // Rendre le champs "authorisations" de type checkbox
                'expanded' => true,
                'multiple' => true
            ])
            // Rendre le champs "authorisations" dynamique et interactif
            ->addDependent('podcasts', 'authorisations', function (DependentField $field, ?array $authorisations) {
                // Mettre une condition pour l'affichage du champs dynamique
                // Si la valeur de la sélection checkbox inclut "Peut publier une émission" alors afficher le champ dynamique
                if ($authorisations !== null && in_array(2, $authorisations)) {                    // Création du champs "podcasts" non mappée en base de données
                    $field->add(TextType::class, [
                        'mapped' => false,
                        "label" => "Sous-categorie"
                    ]);
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
