<?php

namespace App\Form;

use App\Entity\User;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre',
                'row_attr' => [
                    'class' => 'inputs'
                ],
                'attr' => [
                    'placeholder' => 'Ingrese Su Nombre'
                ]
            ])
        
        
            ->add('ApellidoP', TextType::class,['label' => 'Apellido Paterno'
            ,
                'row_attr' => [
                    'class' => 'inputs'
                ],
                'attr' => [
                    'placeholder' => 'Ingrese Su Apellido Paterno'
                ]
                ])
            ->add('ApellidoM', TextType::class,['label' => 'Apellido Materno',
                'row_attr' => [
                    'class' => 'inputs'
                ],
                'attr' => [
                    'placeholder' => 'Ingrese Su Apellido Materno'
                ]
            ])
            ->add('username', TextType::class,['label' => 'Nombre de Usuario',
                'row_attr' => [
                    'class' => 'inputs'
                ],
                'attr' => [
                    'placeholder' => 'Ingrese El Nombre de Usuario que Desea'
                ]
            ])
            ->add('email', TextType::class,[
                'label' => 'Correo',
                'row_attr' => [
                    'class' => 'inputs'
                ],
                'attr' => [
                    'placeholder' => 'Ingrese Su Correo'
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Debe Estar De Acuerdo con Los Terminos y Condiciones',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-check-input'
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Contraseña',
                'row_attr' => [
                    'class' => 'inputs'
                ],
                                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password',
                    'placeholder' => 'Ingrese Su Contraseña'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('Sexo', ChoiceType::class, [
                'label' => 'Género',
                'row_attr' => [
                    'class' => 'inputs'
                ],
                'choices' => [
                    'Hombre' => 'Hombre',
                    'Mujer' => 'Mujer',
                    'Otro' => 'Otro',
                ],
                'expanded' => false,
                'multiple' => false,
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
            ->add('Telefono', TextType::class, [
                'label' => 'Telefón',
                'row_attr' => [
                    'class' => 'inputs'
                ]
                , 'attr' => [
                    'placeholder' => 'Ingrese Su Telefón'
                ]
            ])
            ->add('Fecha_Na', DateType::class,[
                'label' => 'Fecha de Nacimiento',
                'row_attr' => [
                    'class' => 'inputs'
                ]
                , 'attr' => [
                    'class' => 'form-control',
                    'style' => 'margin: 10px 0 0 0;'
                ]
            ])
            // ->add('foto', FileType::class, [
            //     'label' => 'Imagen',
            //     'row_attr' => [
            //         'class' => 'inputs'

            //     ]
            //     ,
            //     'attr' => [
            //         'accept' => 'image/png,image/jpeg',
            //     ]                
            // ]
            // )

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
