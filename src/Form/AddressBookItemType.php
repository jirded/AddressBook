<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\AddressBookItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

class AddressBookItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setAttribute('class', 'form-control')
            ->add(
                'name',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => new Length(['max' => 255]),
                ]
            )
            ->add(
                'surname',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => new Length(['max' => 255]),
                ]
            )
            ->add(
                'phone',
                TextType::class,
                [
                    'required' => false,
                    'constraints' => new Length(['max' => 255]),
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'required' => true,
                    'constraints' => new Email(),
                ]
            )
            ->add(
                'note',
                TextType::class,
                [
                    'required' => false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AddressBookItem::class,
        ]);
    }
}
