<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ArticleService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ArticleController extends AbstractController
{
    /**
     * @Route("/articles/show", name="article_list")
     * @Method({"Get"})
     * @IsGranted("ROLE_USER")
     */
    public function open()
    {
        $articles = $this -> getDoctrine() -> getRepository(Article::class)->findAll();
        return $this->render('articles/list.html.twig', array('articles' => $articles));
    }

    /**
     * @Route("/article/new", name="new_article")
     * Method(("GET", "POSt"))
     */
    public function newArticle(Request $request, ArticleService $articleService, EntityManagerInterface $entityManager) {

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $articleService->createArticle($article, $this->getUser());

            $this->addFlash('success', 'Article Created!');
            return $this->redirectToRoute('author_list');
        }

        return $this -> render('articles/new.html.twig', array(
            'article_form' => $form->createView()
        ));
    }

    /**
     * @Route ("/article/delete/{id}")
     * @Method ({"GET"})
     */
    public function delete(Request $request, $id) {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('article_list');
    }

    /**
     * @Route("/article/edit/{id}", name="edit_article")
     * Method(("GET", "POSt"))
     */

    public function editArticle(EntityManagerInterface $em, Request $request, $id) {

        $articles = $this->getDoctrine()->getRepository(Article::class)->find($id);
        $form = $this->createForm(ArticleType::class, $articles);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('article_list');
        }

        return $this -> render('articles/edit.html.twig', array(
            'article_edit_form' => $form->createView()
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