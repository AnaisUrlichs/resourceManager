<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Author;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/admin/edit", name="edit_admin")
     */

    public function index()
    {
        $articles = $this -> getDoctrine() -> getRepository(Article::class)->findAll();
        $authors = $this -> getDoctrine() -> getRepository(Author::class)->findAll();
        return $this->render('admin/index.html.twig', array('articles' => $articles, 'authors' => $authors));
    }
}