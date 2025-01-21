<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Cubes>
     */
    #[ORM\OneToMany(targetEntity: Cubes::class, mappedBy: 'category')]
    private Collection $cubes;

    public function __construct()
    {
        $this->cubes = new ArrayCollection();
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

    /**
     * @return Collection<int, Cubes>
     */
    public function getCubes(): Collection
    {
        return $this->cubes;
    }

    public function addCube(Cubes $cube): static
    {
        if (!$this->cubes->contains($cube)) {
            $this->cubes->add($cube);
            $cube->setCategory($this);
        }

        return $this;
    }

    public function removeCube(Cubes $cube): static
    {
        if ($this->cubes->removeElement($cube)) {
            // set the owning side to null (unless already changed)
            if ($cube->getCategory() === $this) {
                $cube->setCategory(null);
            }
        }

        return $this;
    }
}
