<?php


namespace App\Repository;


use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Internal\Hydration\ObjectHydrator;
use Doctrine\ORM\Mapping;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class ProductRepository extends EntityRepository implements CrudRepositoryInterface
{

    public function __construct(EntityManagerInterface $em,  Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);

    }

    public function findAll()
    {
        $query = $this->createQueryBuilder('p')
            ->getQuery();
        return $query->getResult();
    }

    public function update($id, $name)
    {
        $products = $this->createQueryBuilder('p')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->getQuery()->getResult();
        /** @var Product $product */
        $product = null;
        if (\count($products) > 0) {
            $product = $products[0];
        }
        $product->setName($name);
        $this->getEntityManager()->flush();
        return $product;
    }

    public function create($name)
    {
        $product = new Product(); $product->setName($name);
        $this->getEntityManager()->persist($product);
        $this->getEntityManager()->flush();
        return $product;
    }

    public function removeById($id)
    {
        $products = $builder = $this->createQueryBuilder("p")
            ->where("p.id = :id")
            ->setParameter('id', $id)
            ->getQuery()->getResult();
        $product = null;
        if (count($products) > 0) {
            $product = $products[0];
            $em = $this->getEntityManager();
            $em->remove($product);
            $em->flush();
        }
        return $product;
    }

    public function bulkDelete($ids)
    {
        $dql = sprintf("DELETE %s o WHERE o.id in (%s)", Product::class, join(',', $ids));
        return $this->getEntityManager()->createQuery($dql)
            ->execute();
    }

    public function delete($id)
    {
        $dql = sprintf("DELETE %s o WHERE o.id = %s", Product::class, $id);
        return $this->getEntityManager()->createQuery($dql)
            ->execute();
    }
}
