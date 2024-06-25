<?php

/**
 * Account type.
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Length;
use App\Entity\User;

/**
 * Form type for managing user account information.
 */
class AccountType extends AbstractType
{
    /**
     * @var Security
     */
    private $security;

    /**
     * Constructor.
     *
     * @param Security $security The security component for user management.
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

  /**
 * Builds the form with fields and event listeners.
 *
 * @param FormBuilderInterface $builder The form builder.
 * @param array                $options The options for configuring the form.
 */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, [
                'label' => 'label.username',
            ])
            ->add('email', null, [
                'label' => 'label.email',
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'label.passwd',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('repeatPassword', PasswordType::class, [
                'label' => 'label.repeatpasswd',
                'mapped' => false,
                'required' => false,
            ]);

        if ($options['is_admin']) {
            $builder->add('roles', ChoiceType::class, [
                'label' => 'label.roles',
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true,
            ]);
        }

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                $plainPassword = $form->get('plainPassword')->getData();
                $repeatPassword = $form->get('repeatPassword')->getData();

                if ($plainPassword !== $repeatPassword) {
                    $form->get('repeatPassword')->addError(new FormError('The password fields must match.'));
                }
            }
        );
    }

    /**
     * Configures options for the form type.
     *
     * @param OptionsResolver $resolver The resolver for configuring form options.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_admin' => false,
        ]);
    }
}
