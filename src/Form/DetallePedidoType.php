<?php

namespace App\Form;

use App\Entity\DetallePedido;
use App\Entity\Producto;
use Doctrine\DBAL\Types\TextType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class DetallePedidoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('producto_id',EntityType::class,[
                'label'=>'Productos',
                'class'=>Producto::class,
                'multiple'=>false,
                'mapped' => true
            ])
            ->add('cantidad')


        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DetallePedido::class,
        ]);
    }
}
