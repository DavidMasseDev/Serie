<?php

namespace App\Entity;

use App\Repository\SerieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SerieRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Serie
{
    // Les asserts permettent de valider les données du formulaires avant qu'elles ne soient envoyées en base.
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['serie_api'])] // Utiliser par l'API
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Please provide a name for the serie.")]
    #[Assert\Length(max: 255, maxMessage: "Max {{ limit }}")] // limit --> va chercher le max (ici 255)
    #[Groups(['serie_api'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['serie_api'])]
    private ?string $overview = null;

    #[ORM\Column(length: 50)]
    #[Assert\Choice(choices : ["Canceled","Returning", "Ending"])] // /!\ Attention, c'est sensible à la casse !
    #[Groups(['serie_api'])]
    private ?string $status = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 1)]
    #[Assert\Range(notInRangeMessage: "Vote must be between {{ min }} and {{ max }}", min: 0, max: 10)]
    private ?string $vote = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    private ?string $popularity = null;

    #[ORM\Column(length: 255)]
    private ?string $genre = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\LessThan(propertyPath: "lastAirDate")]
    private ?\DateTimeInterface $firstAirDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\GreaterThan(propertyPath: "firstAirDate")]
    private ?\DateTimeInterface $lastAirDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $backdrop = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $poster = null;

    #[ORM\Column]
    private ?int $tmdbId = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreated = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateModified = null;

    // Ici le cascade : remove, permet de supprimer les saisons associées. /!\ Ca ne fait pas un on delete cascade version SQL. Doctrine supprime les saisons, puis la série.
    #[ORM\OneToMany(mappedBy: 'Serie', targetEntity: Season::class, cascade: ['remove'])] // Correspond au nom de l'attribut dans Season. On ne fait pas référence à l'objet Serie, mais à l'attribut Serie dans Season
    //#[ORM\OneToMany(mappedBy: 'Serie', targetEntity: Season::class, fetch : "EAGER")] //fetch : "EAGER" --> Permet de charger directement toutes les saisons au chargement de la série. (Donc, ce qui siginifie que sur la liste des series, toutes les saisons seront chargées. Si non spécifié, on est en 'fetch: Lazy'
    #[Groups(['serie_api'])] // Puisqu'on défini qu'on veut récupérer les saisons dans l'api, il faut aller dans l'entity Season et annoter     #[Groups(['serie_api'])]
    // devant les éléments de Season qu'on veut récupérer
    private Collection $seasons;

    public function __construct()
    {
        $this->seasons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getOverview(): ?string
    {
        return $this->overview;
    }

    public function setOverview(?string $overview): static
    {
        $this->overview = $overview;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getVote(): ?string
    {
        return $this->vote;
    }

    public function setVote(string $vote): static
    {
        $this->vote = $vote;

        return $this;
    }

    public function getPopularity(): ?string
    {
        return $this->popularity;
    }

    public function setPopularity(string $popularity): static
    {
        $this->popularity = $popularity;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function getFirstAirDate(): ?\DateTimeInterface
    {
        return $this->firstAirDate;
    }

    public function setFirstAirDate(\DateTimeInterface $firstAirDate): static
    {
        $this->firstAirDate = $firstAirDate;

        return $this;
    }

    public function getLastAirDate(): ?\DateTimeInterface
    {
        return $this->lastAirDate;
    }

    public function setLastAirDate(?\DateTimeInterface $lastAirDate): static
    {
        $this->lastAirDate = $lastAirDate;

        return $this;
    }

    public function getBackdrop(): ?string
    {
        return $this->backdrop;
    }

    public function setBackdrop(?string $backdrop): static
    {
        $this->backdrop = $backdrop;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): static
    {
        $this->poster = $poster;

        return $this;
    }

    public function getTmdbId(): ?int
    {
        return $this->tmdbId;
    }

    public function setTmdbId(int $tmdbId): static
    {
        $this->tmdbId = $tmdbId;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): static
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return $this->dateModified;
    }

    public function setDateModified(?\DateTimeInterface $dateModified): static
    {
        $this->dateModified = $dateModified;

        return $this;
    }

    // Fonction permettant d'ajouter une date de création juste avant le persist (grâce à HasLifecycleCallbacks)
    #[ORM\PrePersist]
    public function setDateAtValue(): void
    {
        $this->setDateCreated(new \DateTime());
    }

    /**
     * @return Collection<int, Season>
     */
    public function getSeasons(): Collection
    {
        return $this->seasons;
    }

    public function addSeason(Season $season): static
    {
        if (!$this->seasons->contains($season)) {
            $this->seasons->add($season);
            $season->setSerie($this);
        }

        return $this;
    }

    public function removeSeason(Season $season): static
    {
        //supprime la saison de la collection de la série, du côté série.
        if ($this->seasons->removeElement($season)) {
            // set the owning side to null (unless already changed)
            if ($season->getSerie() === $this) {
                //Réintialise l'attribut Serie de la classe Season à null.
                $season->setSerie(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->getName();
    }
}
