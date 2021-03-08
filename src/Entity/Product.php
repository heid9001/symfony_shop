<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation as Conv;
use App\Validator\MySimple;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table(name="products")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", options={"autoincrement"=true, "unsigned"=true})
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="products_id_seq")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="products", fetch="EAGER")
     * @ORM\JoinColumn(name="cid", columnDefinition="INT", onDelete="SET NULL")
     */
    protected $category;

    /**
     * @ORM\Column(name="cid", type="integer", nullable=true)
     */
    protected $cid;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", options={"nullable"=false})
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $imageFile;

    protected $image;

    /**
     * @param string $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @param Category $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLinkToCategory()
    {
        if ($this->category !== null) {
            $name = $this->category->getName();
            $id = $this->category->getId();
            return sprintf("<a href='/admin/category/list?id=%s'>%s</a>", $id, $name);
        }
        return '-';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return mixed
     */
    public function getCid()
    {
        return $this->cid;
    }

    public function __toString()
    {
        return $this->name;
    }
}
