<?php

namespace App\Form;

use App\Entity\Mission;
use App\Entity\User;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File as FileConstraint;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\FileType;
class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class'=>'form-control'
                ]
            ])
            ->add('content', CKEditorType::class, [
                'label' => 'false',
                'config' => [
                    'uiColor' => '#ffffff',
                    'editorplaceholder' => 'Petite description sur la mission...',
                    'defaultLanguage' => 'fr',
                    //...
                ],
                'constraints'=>[
                    new NotBlank([
                        'message' => 'Le contenu ne peut pas être vide',
                    ]),
                    new Length([
                        'min' => 25,
                        'max' => 1000,
                        'minMessage' =>  'La description doit comporter au moins {{ limit }} caractères !',
                        'maxMessage' => 'La description doit comporter au plus {{ limit }} caractères !',
                    ]),
                ]
            ])
            ->add('price', IntegerType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ prix est réquis',
                    ]),
                    new GreaterThan([
                        'value' => 5,
                        'message' => 'Le prix doit être supérieur strictement à "5 €"'
                    ])
                ],
                'attr' => ['class' => 'border-0']
            ])
            ->add('image', FileType::class, [
                'required' => true,
                'label' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new Image(),
                    new FileConstraint([
                        'maxSize' => '2M',
                        'maxSizeMessage' => 'Le fichier est trop volumineux ({{ taille }} {{ suffixe }}). La taille maximale autorisée est de {{ limite }}. {{ suffixe }}'
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Mission::class,
        ]);
    }
}
