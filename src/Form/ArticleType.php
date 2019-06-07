<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Author;
use App\Entity\User;
use App\Repository\AuthorRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    private $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('articleTitle', TextType::class, array(
                'attr' => array('class' => 'form-control') ) )

            ->add('articleOutline', TextareaType::class, array(
                'required' => false,
                'attr' => array('class' => 'form-control') ) )

            ->add('author', EntityType::class, [
                'class' => Author::class,
                'choice_label' => function(Author $author) {
                    return sprintf('(%d) %s', $author->getId(), $author->getName());
                },
                'placeholder' => 'Choose an author',
                'choices' => $this->authorRepository->findAllNameAlphabetical(),
                'multiple' => true,
            ])

            ->add('isPublic', CheckboxType::class, [
                'label'    => 'Show this entry publicly?',
                'required' => false,
            ])

            ->add('save', SubmitType::class, array(
                'label' =>'Save',
                'attr' => array('class' => 'btn btn-primary mt-3')))
        ;
    }

    public function editArticle(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('articleTitle', TextType::class, array(
                'attr' => array('class' => 'form-control')))
            ->add('articleOutline', TextareaType::class, array(
                'required' => false,
                'attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array(
                'label' => 'Update',
                'attr' => array('class' => 'btn btn-primary mt-3')
            ));
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
