<?php

namespace App\Form;

use App\Entity\Fichier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class FichierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', FileType::class,[
                'label' => 'Sélectionner le fichier',
                'attr'=>['class'=>'form-control'],
                'label_attr'=>['class'=>'form-label'],

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/javascript',
                            'application/json',
                            'application/ld+json',
                            'application/msword',
                            'application/pdf',
                            'application/sql',
                            'application/vnd.api+json',
                            'application/vnd.ms-excel',
                            'application/vnd.ms-powerpoint',
                            'application/vnd.oasis.opendocument.text',
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/x-www-form-urlencoded',
                            'application/xml',
                            'application/zip',
                            'application/zstd',
                            'audio/mpeg',
                            'audio/ogg',
                            'image/avif',
                            'image/jpeg',
                            'image/png',
                            'image/svg+xml',
                            'multipart/form-data',
                            'text/plain',
                            'text/css',
                            'text/csv',
                            'text/html',
                            'text/xml',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier valide',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fichier::class,
        ]);
    }
}
