<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Colors;
use App\Entity\Cubes;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CubeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('format')
            ->add('description')
            ->add('price')
            ->add('imageFile', FileType::class, [
                'required' => false,
                'mapped' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Formats valides : JPEG JPG PNG GIF',
                    ])
                ],
                // 'download_label' => true,
            ])
            // ->add('updatedAt', null, [
            //     'widget' => 'single_text',
            // ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('colors', EntityType::class, [
                'class' => Colors::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cubes::class,
        ]);
    }
}
