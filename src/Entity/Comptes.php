<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * Comptes
 *
 * @ORM\Table(name="comptes")
 * @ORM\Entity(repositoryClass="App\Repository\ComptesRepository")
 */
class Comptes
{
	/**
	 * @var UuidInterface
	 *
	 * @ORM\Id
	 * @ORM\Column(type="uuid", unique=true)
	 * @ORM\GeneratedValue(strategy="CUSTOM")
	 * @ORM\CustomIdGenerator(class=UuidGenerator::class)
	 */
    private $uuid;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=255, nullable=false, options={"default"="root"})
     */
    private $login = 'root';

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false, options={"default"="root"})
     */
    private $password = 'root';

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $updatedAt = 'CURRENT_TIMESTAMP';

	/**
	 * @param Uuid $uuid
	 * @param string $login
	 * @param string $password
	 * @param string $name
	 * @param \DateTime|string|null $createdAt
	 * @param \DateTime|string|null $updatedAt
	 */
	public function __construct(Uuid $uuid, string $login, string $password, string $name, $createdAt, $updatedAt)
                                 	{
                                 		$this->uuid = Uuid::uuid4();
                                 		$this->login = $login;
                                 		$this->password = $password;
                                 		$this->name = $name;
                                 		$this->createdAt = $createdAt;
                                 		$this->updatedAt = $updatedAt;
                                 	}

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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


}
