<?php


namespace App\Repository;


use Doctrine\ORM\EntityRepository;
use App\Entity\Category;

class CategoryRepository extends EntityRepository implements CrudRepositoryInterface
{
    public function bulkDelete($ids)
    {
        $dql = sprintf("DELETE %s o WHERE o.id in (%s)", Category::class, join(',', $ids));
        return $this->getEntityManager()->createQuery($dql)
            ->execute();
    }


    public function delete($id)
    {
        $dql = sprintf("DELETE %s o WHERE o.id = %s", Category::class, $id);
        return $this->getEntityManager()->createQuery($dql)
            ->execute();
    }
}
