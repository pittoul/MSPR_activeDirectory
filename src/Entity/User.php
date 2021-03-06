<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticator;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * Dans la ligne suivante, je peux changer 'email' par ce que je veux comme attribut.
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, TwoFactorInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * Check du pwd chez have i been  pawned
     *
     * @var boolean
     */
    private $checkPassword;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $googleAuthenticatorSecret;

    /**
     * @ORM\Column(type="json")
     */
    private $infosNavigateur = [];

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $ipUser;

    /**
     * @ORM\Column(type="integer", options={"default": "1"})
     */
    private $nbreDeTentatives;

    /**
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private $estAutorise;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $dateDerniereConnexion;

    public function isGoogleAuthenticatorEnabled(): bool
    {
        return $this->googleAuthenticatorSecret ? true : false;
    }

    public function getGoogleAuthenticatorUsername(): string
    {
        return $this->getUsername();
    }

    public function getGoogleAuthenticatorSecret(): ?string
    {
        return $this->googleAuthenticatorSecret;
    }

    public function setGoogleAuthenticatorSecret(?string $googleAuthenticatorSecret): self
    {
        $this->googleAuthenticatorSecret = $googleAuthenticatorSecret;

        return $this;
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

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
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
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Get check du pwd chez have i been pawned
     *
     * @return  boolean
     */
    public function getCheckPassword()
    {
        return $this->checkPassword;
    }

    /**
     * Set check du pwd chez have i been pawned
     *
     * @param  boolean  $checkPassword  Check du pwd chez have i been pawned
     *
     * @return  self
     */
    public function setCheckPassword($checkPassword)
    {
        $this->checkPassword = $checkPassword;

        return $this;
    }

    public function getInfosNavigateur(): ?array
    {
        return $this->infosNavigateur;
    }

    public function setInfosNavigateur(array $infosNavigateur): self
    {
        $this->infosNavigateur = $infosNavigateur;

        return $this;
    }

    public function getIpUser(): ?string
    {
        return $this->ipUser;
    }

    public function setIpUser(string $ipUser): self
    {
        $this->ipUser = $ipUser;

        return $this;
    }

    public function getNbreDeTentatives(): ?int
    {
        return $this->nbreDeTentatives;
    }

    public function setNbreDeTentatives(int $nbreDeTentatives): self
    {
        $this->nbreDeTentatives = $nbreDeTentatives;

        return $this;
    }

    public function getEstAutorise(): ?bool
    {
        return $this->estAutorise;
    }

    public function setEstAutorise(bool $estAutorise): self
    {
        $this->estAutorise = $estAutorise;

        return $this;
    }

    public function getDateDerniereConnexion(): ?\DateTimeInterface
    {
        return $this->dateDerniereConnexion;
    }

    public function setDateDerniereConnexion(?\DateTimeInterface $dateDerniereConnexion): self
    {
        $this->dateDerniereConnexion = $dateDerniereConnexion;

        return $this;
    }  

}
