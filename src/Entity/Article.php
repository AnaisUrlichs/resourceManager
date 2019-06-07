<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", length=100)
     */
    private $articleTitle;

    /**
     * @ORM\Column(type="text")
     */

    private $articleOutline;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Author", inversedBy="articles")
     */
    private $author;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublic = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="articleList")
     */
    private $user;

    public function __construct()
    {
        $this->author = new ArrayCollection();
    }

    // Getters and Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticleTitle () {
        return $this->articleTitle;
    }

    public function setArticleTitle ($articleTitle) {
        $this->articleTitle = $articleTitle;
    }

    public function getArticleOutline () {
        return $this-> articleOutline;
    }

    public function setArticleOutline ($articleOutline) {
        $this->articleOutline = $articleOutline;
    }

    /**
     * @return Collection|Author[]
     */
    public function getAuthor(): Collection
    {
        return $this->author;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->author->contains($author)) {
            $this->author[] = $author;
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        if ($this->author->contains($author)) {
            $this->author->remove($author);
        }

        return $this;
    }

    public function getIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
