<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Niveau;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('publishedAt', DateType::class, [
                    'widget'=>'choice',
                    'format' => 'dd-MMM-yyyy',
                    'label'=>'Parution'
                ])
                ->add('title', TextType::class, [
                    'required'=>true,
                    'label'=>'Titre'
                ])
                ->add('description', TextareaType::class, [
                    'attr' => array('style' => 'height: 20vh'),
                    'required'=>false
                ])
                ->add('miniature', TextType::class, [
                    'label' =>'Miniature (URL, taille 120x90 pixels)',
                    'required'=>false
                ])
                ->add('picture', TextType::class, [
                    'label'=>'Image (URL, taille maximale 640x480 pixels)',
                    'required'=>false
                ])
                ->add('videoId', TextType::class, [
                    'required'=>false
                ])
                ->add('niveau', EntityType::class, [
                    'class' => Niveau::class,
                    'choice_label' => 'libelle',
                    'required'=>true
                    ])
                ->add('Enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }

}
