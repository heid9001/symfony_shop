<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Conv;
use App\Repository\CategoryRepository;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @ORM\Table(name="categories")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(type="integer", options={"autoincrement"=true})
     * @ORM\SequenceGenerator(sequenceName="categories_id_seq")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="category")
     */
    protected $products;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="children")
     * @ORM\JoinColumn(name="pid", columnDefinition="INT", onDelete="SET NULL")
    */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Category", mappedBy="parent")
     */
    protected $children;

    /**
     * @ORM\Column(name="pid", type="integer", nullable=true)
     */
    protected $pid;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

    /**
     * @param Category $parent
     */
    public function setParent($parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @param ArrayCollection $products
     */
    public function setProducts(ArrayCollection $products): void
    {
        $this->products = $products;
    }

    public function addProduct(Product $product)
    {
        $this->products[] = $product;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getProductCount(): int
    {
        return $this->products->count();
    }

    public function getLinkToProducts()
    {
        return '<a href="/admin/product/list?cid='.$this->id.'">перейти ('.$this->getProductCount().')</a>';
    }

    /**
     * @return Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    public function __toString()
    {
        return $this->name;
    }

}
