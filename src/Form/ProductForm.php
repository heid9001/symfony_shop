<?php


namespace App\Form;



use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', ChoiceType::class, [
                'choices' => $options['choices'],
                'choice_label' => function($category, $key, $value) {
                    return isset($value)? $category->getName() : $value;
                }
            ])
            ->add('name', TextType::class)
            ->add('submit', SubmitType::class, ['label'=>'Add Product']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // привязываем форму к классу
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);

        $resolver->setDefined(['choices']);
    }
}
