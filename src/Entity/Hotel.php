<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HotelRepository")
 * @ORM\Table(indexes={@ORM\Index(name="chain_idx", columns={"chain_id"})})
 * @ORM\HasLifecycleCallbacks()
 */
class Hotel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var UuidInterface
     * @ORM\Column(type="uuid", unique=true)
     */
    private $uuid;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $address;

    /**
     * @ORM\Column(type="integer")
     */
    private $rooms;

    /**
     * @ORM\Column(type="integer")
     */
    private $chainId;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return self
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    /**
     * @param int $rooms
     * @return self
     */
    public function setRooms(int $rooms): self
    {
        $this->rooms = $rooms;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getChainId(): ?int
    {
        return $this->chainId;
    }

    /**
     * @param int $chainId
     * @return $this
     */
    public function setChainId(int $chainId): self
    {
        $this->chainId = $chainId;
        return $this;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return (string) $this->uuid;
    }

    /**
     * @ORM\PrePersist
     */
    public function setUuidValue()
    {
        $this->uuid = Uuid::getFactory()->uuid4();
    }
}
