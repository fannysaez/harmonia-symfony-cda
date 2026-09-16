<?php

namespace App\Entity;

use App\Repository\TrackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrackRepository::class)]
class Track
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $duration = null;

    #[ORM\Column]
    private ?int $trackNumber = null;

    #[ORM\Column]
    private ?int $listenCount = null;

    #[ORM\Column]
    private ?bool $isExplicit = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'tracks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Album $album = null;

    /**
     * @var Collection<int, Genre>
     */
    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'tracks')]
    private Collection $genres;

    /**
     * @var Collection<int, Playlist>
     */
    #[ORM\ManyToMany(targetEntity: Playlist::class, mappedBy: 'tracks')]
    private Collection $playlists;

    /**
     * @var Collection<int, ListeningHistory>
     */
    #[ORM\OneToMany(targetEntity: ListeningHistory::class, mappedBy: 'track')]
    private Collection $listeningHistories;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'favoriteTracks')]
    private Collection $favoriteByUsers;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
        $this->playlists = new ArrayCollection();
        $this->listeningHistories = new ArrayCollection();
        $this->favoriteByUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getTrackNumber(): ?int
    {
        return $this->trackNumber;
    }

    public function setTrackNumber(int $trackNumber): static
    {
        $this->trackNumber = $trackNumber;

        return $this;
    }

    public function getListenCount(): ?int
    {
        return $this->listenCount;
    }

    public function setListenCount(int $listenCount): static
    {
        $this->listenCount = $listenCount;

        return $this;
    }

    public function isExplicit(): ?bool
    {
        return $this->isExplicit;
    }

    public function setIsExplicit(bool $isExplicit): static
    {
        $this->isExplicit = $isExplicit;

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

    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    public function setAlbum(?Album $album): static
    {
        $this->album = $album;

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): static
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): static
    {
        $this->genres->removeElement($genre);

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
            $playlist->addTrack($this);
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): static
    {
        if ($this->playlists->removeElement($playlist)) {
            $playlist->removeTrack($this);
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
            $listeningHistory->setTrack($this);
        }

        return $this;
    }

    public function removeListeningHistory(ListeningHistory $listeningHistory): static
    {
        if ($this->listeningHistories->removeElement($listeningHistory)) {
            // set the owning side to null (unless already changed)
            if ($listeningHistory->getTrack() === $this) {
                $listeningHistory->setTrack(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getFavoriteByUsers(): Collection
    {
        return $this->favoriteByUsers;
    }

    public function addFavoriteByUser(User $favoriteByUser): static
    {
        if (!$this->favoriteByUsers->contains($favoriteByUser)) {
            $this->favoriteByUsers->add($favoriteByUser);
            $favoriteByUser->addFavoriteTrack($this);
        }

        return $this;
    }

    public function removeFavoriteByUser(User $favoriteByUser): static
    {
        if ($this->favoriteByUsers->removeElement($favoriteByUser)) {
            $favoriteByUser->removeFavoriteTrack($this);
        }

        return $this;
    }
}
