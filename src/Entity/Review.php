<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReviewRepository")
 * @ORM\Table(indexes={@ORM\Index(name="hotel_idx", columns={"hotel_id"})})
 */
class Review
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $hotelId;

    /**
     * @ORM\Column(type="string")
     */
    private $text;

    /**
     * @ORM\Column(type="integer")
     */
    private $score;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHotelId(): ?int
    {
        return $this->hotelId;
    }

    /**
     * @param int $hotelId
     * @return $this
     */
    public function setHotelId(int $hotelId): self
    {
        $this->hotelId = $hotelId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return Review
     */
    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getScore(): ?int
    {
        return $this->score;
    }

    /**
     * @param int $score
     * @return $this
     */
    public function setScore(int $score): self
    {
        $this->score = $score;
        return $this;
    }
}
