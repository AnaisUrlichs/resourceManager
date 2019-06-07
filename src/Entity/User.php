<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true, nullable=false)
     * @Groups("main")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ApiToken", mappedBy="user")
     */
    private $apiTokens;

    /**
     * @ORM\Column(type="datetime")
     */
    private $agreedTermsAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="user")
     */
    private $articleList;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Author", mappedBy="user")
     */
    private $authorList;

    public function __construct()
    {
        $this->articleList = new ArrayCollection();
        $this->authorList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;// not needed for apps that do not check user passwords
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function serialize() {
        return serialize([
            $this->id,
            $this->email,
            $this->password
        ]);
    }

    public function unserialize($string)
    {
        list (
            $this->id,
            $this->email,
            $this->password
            ) = unserialize($string, ['allowed_classes' => false]);
    }

    /**
     * @return Collection|ApiToken[]
     */
    public function getApiTokens(): Collection
    {
        return $this->apiTokens;
    }

    public function addApiToken(ApiToken $apiToken): self
    {
        if (!$this->apiTokens->contains($apiToken)) {
            $this->apiTokens[] = $apiToken;
            $apiToken->setUser($this);
        }

        return $this;
    }

    public function removeApiToken(ApiToken $apiToken): self
    {
        if ($this->apiTokens->contains($apiToken)) {
            $this->apiTokens->removeElement($apiToken);
            // set the owning side to null (unless already changed)
            if ($apiToken->getUser() === $this) {
                $apiToken->setUser(null);
            }
        }

        return $this;
    }

    public function getAgreedTermsAt(): ?\DateTimeInterface
    {
        return $this->agreedTermsAt;
    }

    public function agreeToTerms()
    {
        $this->agreedTermsAt = new \DateTime();
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticleList(): Collection
    {
        return $this->articleList;
    }

    public function addArticleList(Article $articleList): self
    {
        if (!$this->articleList->contains($articleList)) {
            $this->articleList[] = $articleList;
            $articleList->setUser($this);
        }

        return $this;
    }

    public function removeArticleList(Article $article): self
    {
        if ($this->articleList->contains($article)) {
            $this->articleList->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Author[]
     */
    public function getAuthorList(): Collection
    {
        return $this->authorList;
    }

    public function addAuthorList(Author $authorList): self
    {
        if (!$this->authorList->contains($authorList)) {
            $this->authorList[] = $authorList;
            $authorList->setUser($this);
        }

        return $this;
    }

    public function removeAuthorList(Author $authorList): self
    {
        if ($this->authorList->contains($authorList)) {
            $this->authorList->removeElement($authorList);
            // set the owning side to null (unless already changed)
            if ($authorList->getUser() === $this) {
                $authorList->setUser(null);
            }
        }

        return $this;
    }
}
