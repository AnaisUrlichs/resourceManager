<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\AuthorRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="article_search")
     * @IsGranted("ROLE_USER")
     */
    public function index(AuthorRepository $authorRepository, ArticleRepository $articleRepository, Request $request, PaginatorInterface $paginator)
    {
        $q = $request->query->get('q');

        $p = $request -> query -> get ('q');

        $author = $authorRepository->getWithSearchQueryBuilder($q);

        $article = $articleRepository->getWithSearchQueryBuilder($p);

        $pagination= $paginator->paginate(
            $author,
            $request->query->getInt('page', 1), 10
        );

        $pagination_1 = $paginator->paginate(
            $article,
            $request->query->getInt('page', 1), 10
        );

        return $this->render('search/index.html.twig', [
            'pagination' => $pagination,
            'pagination_1' => $pagination_1
        ]);
    }
}
