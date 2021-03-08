<?php


namespace App\Service;


use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Pagination
{
    // число объектов отображаемых на каждой странице
    public const OBJECT_LIST_SIZE = 5;

    // число видимых страниц для перехода
    public const PAGINATION_SIZE = 2;

    /** @var ManagerRegistry $doctrine */
    protected $doctrine;

    /** @var UrlGeneratorInterface $router */
    protected $router;

    public function __construct(ManagerRegistry $doctrine, UrlGeneratorInterface $router)
    {
        $this->doctrine = $doctrine;
        $this->router = $router;
    }

    /**
     * @param $routeName
     * @param $entityCls
     * @param $entityPerPage
     * @param $visiblePages
     * @param $curPage
     * @param $opts, ['order' => [$columnName => 'ASC|DESC']]
     * @throws NotFoundHttpException
     * @return array
     */
    public function paginate($routeName, $entityCls, $curPage, $opts = [])
    {
        $repository = $this->doctrine->getRepository($entityCls);
        $order = [];
        if (key_exists('order', $opts)) {
            $order = $opts['order'];
            unset($opts['order']);
        }


        /** @var array $data */
        $data = $repository->findBy($opts, $order, self::OBJECT_LIST_SIZE, ($curPage - 1) * self::OBJECT_LIST_SIZE);
        $lastPage = (int) ceil(count($repository->findBy($opts)) / self::OBJECT_LIST_SIZE);

        if ($lastPage === 0) {
            return [[], []];
        }

        if ($curPage > $lastPage) {
            throw new NotFoundHttpException();
        }

        $pages = $this->paginationGroup($curPage, self::PAGINATION_SIZE, $lastPage);
        foreach ($pages as $key => $pageNum)
        {
            $routeParams = ['page'=>$pageNum];
            $routeParams = array_merge($routeParams, $opts);
            if (! empty($order)) {
                $routeParams = array_merge($routeParams, ['col'=>array_key_first($order), 'order'=>$order[array_key_first($order)]]);
            }
            $pages[$key] = [
                'name' => $pageNum,
                'url'  => $this->router->generate($routeName, $routeParams, UrlGeneratorInterface::ABSOLUTE_PATH)
            ];
        }
        return [$pages, $data];
    }

    /**
     * Опредление группы по номеру страницы
     * @param int $page, номер текущей страницы
     * @param int $groupSize, число видимых страниц
     * @param int $lastPage, номер последней страницы
     * @return array|false, массив видимых страниц
     */
    private function paginationGroup($page, $groupSize, $lastPage)
    {
        if ($page > $lastPage) return false;
        $groupIdx = (int) ceil($page / $groupSize);
        $beginIdx = $groupIdx;
        $endIdx   = $beginIdx + $groupSize - 1;
        if ($endIdx > $lastPage) {
            $endIdx = $endIdx - ($endIdx - $lastPage);
        }
        $slice = range($beginIdx, $endIdx);
        if ($beginIdx !== 1) {
            array_unshift($slice, 1);
        }
        if ($endIdx < $lastPage)
        {
            array_push($slice, 1);
        }
        return $slice;
    }
}
