<?php
namespace AppBundle\Form\Frontend;

use AppBundle\Entity\Country;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use AppBundle\Entity\BillingAccount;
use AppBundle\Entity\BillingAccountSetting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints;
use AppBundle\Entity\Phone;

/**
 * Class UserRegisterForm
 */
class UserRegisterForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'First name',
                'attr' => [
                    'placeholder' => 'First Name',
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'attr' => [
                    'placeholder' => 'Last Name',
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('email', TextType::class, [
                'label' => 'E-mail Address',
                'attr' => [
                    'placeholder' => 'E-mail',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Email([ 'strict' => true ]),
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Passwords must match.',
                'options' => ['attr' => ['autocomplete' => 'off', 'class' => 'password-field']],
                'required' => false,
                'first_options'  => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Password',
                    ],
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Confirm password',
                        ],
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Enter the desired password.']),
                    new Length(['min' => 6]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Sign up',
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\User',
        ]);
    }
}
