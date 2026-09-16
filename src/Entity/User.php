<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, Playlist>
     */
    #[ORM\OneToMany(targetEntity: Playlist::class, mappedBy: 'user')]
    private Collection $playlists;

    /**
     * @var Collection<int, ListeningHistory>
     */
    #[ORM\OneToMany(targetEntity: ListeningHistory::class, mappedBy: 'user')]
    private Collection $listeningHistories;

    /**
     * @var Collection<int, Track>
     */
    #[ORM\ManyToMany(targetEntity: Track::class, inversedBy: 'favoriteByUsers')]
    private Collection $favoriteTracks;

    public function __construct()
    {
        $this->playlists = new ArrayCollection();
        $this->listeningHistories = new ArrayCollection();
        $this->favoriteTracks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
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

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0" . self::class . "\0password"] = hash('crc32c', $this->password);
        
        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Playlist>
     */
    public function getPlaylists(): Collection
    {
        return $this->playlists;
    }

    public function addPlaylist(Playlist $playlist): static
    {
        if (!$this->playlists->contains($playlist)) {
            $this->playlists->add($playlist);
            $playlist->setUser($this);
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): static
    {
        if ($this->playlists->removeElement($playlist)) {
            // set the owning side to null (unless already changed)
            if ($playlist->getUser() === $this) {
                $playlist->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ListeningHistory>
     */
    public function getListeningHistories(): Collection
    {
        return $this->listeningHistories;
    }

    public function addListeningHistory(ListeningHistory $listeningHistory): static
    {
        if (!$this->listeningHistories->contains($listeningHistory)) {
            $this->listeningHistories->add($listeningHistory);
            $listeningHistory->setUser($this);
        }

        return $this;
    }

    public function removeListeningHistory(ListeningHistory $listeningHistory): static
    {
        if ($this->listeningHistories->removeElement($listeningHistory)) {
            // set the owning side to null (unless already changed)
            if ($listeningHistory->getUser() === $this) {
                $listeningHistory->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Track>
     */
    public function getFavoriteTracks(): Collection
    {
        return $this->favoriteTracks;
    }

    public function addFavoriteTrack(Track $favoriteTrack): static
    {
        if (!$this->favoriteTracks->contains($favoriteTrack)) {
            $this->favoriteTracks->add($favoriteTrack);
        }

        return $this;
    }

    public function removeFavoriteTrack(Track $favoriteTrack): static
    {
        $this->favoriteTracks->removeElement($favoriteTrack);

        return $this;
    }
}
