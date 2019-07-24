<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;


class ArticleService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * RegisterService constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param $article
     */
    public function createArticle(Article $article, User $user): void
    {
        $article->setUser($user);


        $this->entityManager->persist($article);
        $this->entityManager->flush();
    }
}