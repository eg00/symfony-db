<?php

namespace App\Form;

use App\Entity\Reviews;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('book_id', HiddenType::class,
                [
                'data' => $options['attr']['bookId'],
            ]
            )
            ->add('reviever_name', TextType::class)
            ->add('content', TextType::class)
            ->add('rating', ChoiceType::class, [
                'choices' => array_combine(range(1,10), range(1,10)),
            ])
            ->add('published_date', DateTimeType::class, [
                'data' => new \DateTime(),
                'attr' => array(
                    'readonly' => true,
                ),
            ])
            ->add('save', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reviews::class,
        ]);
    }
}
