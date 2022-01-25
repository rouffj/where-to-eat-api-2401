<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Api;

/**
 * @ORM\Entity(repositoryClass=RestaurantRepository::class)
 */
class Restaurant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @ Api\Groups("restaurant_list")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80)
     * 
     * @ Api\Groups({"restaurant_list"})
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $likes;

    /**
     * @ORM\Column(type="integer")
     */
    private $dislikes;

    /**
     * @ORM\OneToOne(targetEntity=Address::class, mappedBy="restaurant", cascade={"persist", "remove"})
     * 
     * @ Api\Groups({"restaurant_list"})
     */
    private $address;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getDislikes(): ?int
    {
        return $this->dislikes;
    }

    public function setDislikes(int $dislikes): self
    {
        $this->dislikes = $dislikes;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): self
    {
        // set the owning side of the relation if necessary
        if ($address->getRestaurant() !== $this) {
            $address->setRestaurant($this);
        }

        $this->address = $address;

        return $this;
    }

    public function getMapUrl()
    {
        return 'https://maps.googleapis.com/maps/api/staticmap?zoom=14&size=250x150&markers='.$this->getAddress().'&key='.$_ENV['GMAPS_KEY'];
    }

    public function getDirectionUrl()
    {
        return 'https://www.google.com/maps/dir/'.urlencode($_ENV['WORK_ADDRESS']).'/'. $this->getAddress() .'/';
    }
}
