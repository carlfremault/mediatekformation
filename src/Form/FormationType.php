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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire qui permet d'ajouter ou modifier une formation. Prérempli en cas de modification.
 */
class FormationType extends AbstractType {

    private const HTTP = "http://";

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('publishedAt', DateType::class, [
                    'widget' => 'single_text',
                    'data' => isset($options['data']) && $options['data']->getPublishedAt() != null ? $options['data']->getPublishedAt() : new \DateTime('now'),
                    'label' => 'Parution'
                ])
                ->add('title', TextType::class, [
                    'required' => true,
                    'label' => 'Titre'
                ])
                ->add('description', TextareaType::class, [
                    'attr' => array('style' => 'height: 20vh'),
                    'required' => false
                ])
                ->add('miniature', TextType::class, [
                    'label' => 'Miniature (URL, taille 120x90 pixels)',
                    'required' => false
                ])
                ->add('picture', TextType::class, [
                    'label' => 'Image (URL, taille maximale 640x480 pixels)',
                    'required' => false
                ])
                ->add('videoId', TextType::class, [
                    'required' => false
                ])
                ->add('niveau', EntityType::class, [
                    'class' => Niveau::class,
                    'choice_label' => 'libelle',
                    'required' => true
                ])
                ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                    $infos = $event->getData();
                    $picture = $infos->getPicture();
                    if (strlen($picture) > 0 && strpos($picture, "//") === false) {
                        $infos->setPicture(self::HTTP . $picture);
                    }
                    $miniature = $infos->getMiniature();
                    if (strlen($miniature) > 0 && strpos($miniature, "//") === false) {
                        $infos->setMiniature(self::HTTP . $miniature);
                    }
                    $event->setData($infos);
                })
                ->add('Enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }

}
