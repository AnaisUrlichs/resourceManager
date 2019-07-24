<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Author;
use App\Repository\ArticleRepository;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorType extends AbstractType
{
    private $authorRepository;

    private $articleRepository;

    public function __construct(AuthorRepository $authorRepository, ArticleRepository $articleRepository)
    {
        $this->authorRepository = $authorRepository;
        $this->articleRepository = $articleRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'attr' => array('class' => 'form-control') ) )

            ->add('description', TextareaType::class, array(
                'required' => false,
                'attr' => array('class' => 'form-control') ) )

            ->add('articles', EntityType::class, [
                'class' => Article::class,
                'choice_label' => function(Article $articles){
                    return $articles->getArticleTitle() . " - " . $articles->getRelease();
                },
                'query_builder' => function (ArticleRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.articleTitle', 'ASC');
                },
                'required' => false,
                'placeholder' => 'Choose an author',
                'multiple' => true,
            ])

            ->add('save', SubmitType::class, array(
                'label' =>'Save',
                'attr' => array('class' => 'btn btn-primary mt-3')));
    }
    public function editForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'attr' => array('class' => 'form-control')))
            ->add('description', TextareaType::class, array(
                'required' => false,
                'attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array(
                'label' => 'Save',
                'attr' => array('class' => 'btn btn-primary mt-3')
            ));
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Author::class,
        ]);
    }
}





