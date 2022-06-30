<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Ecritures
 *
 * @ORM\Table(name="ecritures", indexes={@ORM\Index(name="fk compte_uuid", columns={"compte_uuid"})})
 * @ORM\Entity(repositoryClass="App\Repository\EcrituresRepository")
 */
class Ecritures
{
	/**
	 * @var UuidInterface
	 *
	 * @ORM\Id
	 * @ORM\Column(type="uuid", unique=true)
	 * @ORM\GeneratedValue(strategy="CUSTOM")
	 * @ORM\CustomIdGenerator(class=UuidGenerator::class)
	 * @Groups("post:read")
	 */
    private $uuid;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255, nullable=false)
	 * @Groups("post:read")

	 */
    private $label;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="date", type="date", nullable=true)
	 * @Groups("post:read")
	 */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=0, nullable=false)
	 * @Groups("post:read")
	 */
    private $type;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float", precision=10, scale=0, nullable=false)
	 * @Groups("post:read")
	 */
    private $amount;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt = 'CURRENT_TIMESTAMP';

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $updatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \Comptes
     *
     * @ORM\ManyToOne(targetEntity="Comptes")
     * @ORM\JoinColumn(name="compte_uuid", referencedColumnName="uuid")
     *
     */
    private $compteUuid;

	/**
	 * @param Uuid $uuid
	 * @param string $label
	 * @param DateTime|null $date
	 * @param string $type
	 * @param float $amount
	 * @param DateTime|string|null $createdAt
	 * @param DateTime|string|null $updatedAt
	 * @param \Comptes $compteUuid
	 */
	public function __construct(Uuid $uuid, string $label, ?DateTime $date, string $type, float $amount, $createdAt, $updatedAt, \Comptes $compteUuid)
                                             	{
                                             		$this->uuid = Uuid::uuid4();
                                             		$this->label = $label;
                                             		$this->date = $date;
                                             		$this->type = $type;
                                             		$this->amount = $amount;
                                             		$this->createdAt = $createdAt;
                                             		$this->updatedAt = $updatedAt;
                                             		$this->compteUuid = $compteUuid;
                                             	}

    public function getUuid() : UuidInterface
	{
        return $this->uuid;
    }

	/**
	 * @param UuidInterface $uuid
	 */
	public function setUuid(UuidInterface $uuid): void
	{
		$this->uuid = $uuid;
	}

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCompteUuid(): ?Comptes
    {
        return $this->compteUuid;
    }

    public function setCompteUuid(?Comptes $compteUuid): self
    {
        $this->compteUuid = $compteUuid;

        return $this;
    }


}
