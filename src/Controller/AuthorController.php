<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AuthorController extends AbstractController
{

    /**
     * @Route("/", name="author_list")
     * @Method({"Get"})
     * @IsGranted("ROLE_USER")
     */
    public function index()
    {
        $authors = $this -> getDoctrine() -> getRepository(Author::class)->findAll();
        return $this->render('authors/index.html.twig', array('authors' => $authors));
    }

    /**
     * @Route("/author/{id}", name="author_show", requirements={"id":"\d+"})
     */
    public function show($id, ArticleRepository $articleRepository) {
        $author = $this->getDoctrine()->getRepository(Author::class)->find($id);

        return $this->render('authors/show.html.twig', array('author' => $author));
    }


    /**
     * @Route("/author/new", name="new_author")
     * Method(("GET", "POSt"))
     */
    public function new(EntityManagerInterface $em, Request $request) {

        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Article $article */
            $author = $form->getData();
            $author->setUser($this->getUser());

            $em->persist($author);
            $em->flush();

            $this->addFlash('success', 'Author Created!');
            return $this->redirectToRoute('author_list');
        }

        return $this -> render('authors/new.html.twig', array(
            'author_form' => $form->createView()
        ));
    }



    /**
     * @Route ("/author/delete/{id}")
     * @Method ({"GET"})
     */
    public function delete(Request $request, $id) {
        $author = $this->getDoctrine()->getRepository(Author::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($author);
        $entityManager->flush();

        return $this->redirectToRoute('author_list');
    }



    /**
     * @Route("/author/edit/{id}", name="edit_author")
     * Method(("GET", "POSt"))
     */

    public function edit(EntityManagerInterface $em, Request $request, $id) {

        $author = $this->getDoctrine()->getRepository(Author::class)->find($id);
        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'Author edited!');
            return $this->redirectToRoute('author_list');
        }

        return $this -> render('authors/edit.html.twig', array(
            'author_edit_form' => $form->createView()
        ));
    }

    /**
     *@Route("/article/save")
     */

    /* public function save()
     {
         $entityManager = $this->getDoctrine()->getManager();

         $article = new Article();
         $article->setArticleTitle("Article 2");
         $article->setArticleOutline("dfdfder2rsdkbf jfbkjfb fjbsdkjfbwe");

         $entityManager->persist($article);
         $entityManager->flush();

         return new Response('Saved an article with the id of '.$article->getId());
     } */


}