<?php

namespace Kibatic\UX\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SwitchType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label_attr' => ['class' => 'checkbox-switch'],
//            'row_attr' => [
//                'class' => 'form-check form-switch',
//            ],
//            'attr' => [
//                'class' => 'form-check-input',
//                'role' => 'switch',
//            ],
//            'label_attr' => [
//                'class' => 'form-check-label',
//            ],
            'required' => false,
        ]);
    }

    public function getParent(): string
    {
        return CheckboxType::class;
    }
}