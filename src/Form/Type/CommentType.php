<?php

/**
 * Comment type.
 */

namespace App\Form\Type;

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type for managing Comment entity.
 */
class CommentType extends AbstractType
{
    /**
     * Builds the form for Comment entity.
     *
     * @param FormBuilderInterface $builder The form builder.
     * @param array                $options The options for configuring the form.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'label.comment',
                'attr' => ['rows' => 5],
            ]);
    }

    /**
     * Configures options for the Comment form type.
     *
     * @param OptionsResolver $resolver The resolver for configuring form options.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
