<?php


namespace App\Repository;


interface CrudRepositoryInterface
{
    /** @param array $ids */
    public function bulkDelete(array $ids);

    /** @param int $id */
    public function delete(int $id);
}
