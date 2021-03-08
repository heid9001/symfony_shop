<?php


namespace App\Controller;


use App\Repository\CrudRepositoryInterface;
use App\Service\Pagination;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Класс упрощающий создания простых CRUD обработчиков
 * Class CrudController
 * @package App\Controller
 */
abstract class CrudController extends AbstractController
{
    /**
     * путь до шаблонов create|list
     * @var string $templateDir
     */
    protected $templateDir;

    /**
     * имя класса сущности
     * @var string $entityCls
     */
    protected $entityCls;

    /**
     * имя класса формы
     * @var string $formCls
     */
    protected $formCls;

    /**
     * роут до списка сущностей
     * @var string $viewListRoute
     */
    protected $viewListRoute;

    /**
     * роут до создания сущности
     * @var string $createRoute
     */
    protected $createRoute;

    /**
     * Сервис создания пагинации
     * @var Pagination $pagination
     */
    protected $pagination;

    /**
     * Сервис преобразования объектов
     * @var SerializerInterface $serializer
     */
    protected $serializer;

    public function __construct(Pagination $pagination, SerializerInterface $serializer)
    {
        $this->pagination = $pagination;
        $this->serializer = $serializer;
    }

    public function handleDelete(Request $request, $id)
    {
        $this->getRepository()->delete($id);
        return $this->redirectToPrevious($request);
    }

    public function handleDeleteAll(Request $request, $ids)
    {
        $this->getRepository()->bulkDelete($ids);
        return $this->redirectToPrevious($request);
    }

    /**
     * инициализация формы для Создание|Обновление(по id) объекта
     *
     * @param Request $request
     * @param $formOptions
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function handleCreate(Request $request, $formOptions)
    {
        $repository = $this->getRepository();
        $manager = $this->getManager();
        $object = null;
        $id = $request->query->get('id', null);
        if (isset($id)) {
            $object = $repository->findOneBy(['id'=>$id]);
            if ($object === null) {
                throw new NotFoundHttpException();
            }
        }
        if (! isset($object)) {
            $object = new $this->entityCls();
        }
        $form = $this->createForm($this->formCls, $object, $formOptions);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $object = $form->getData();
            $manager->persist($object);
            $manager->flush();
            return $this->redirectToRoute($this->viewListRoute);
        }
        return $this->render($this->templateDir.'/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Сортируемая таблица объектов с пагинацией (см. templates/macro/admin.twig.html)
     *
     * @param Request $request
     * @param int $page
     * @param array $allowedAttributes, fix для doctrine-proxy
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function handleViewlist(Request $request, int $page, array $allowedAttributes)
    {
        $opts = $request->query->all();
        if ($request->query->has("order"))
        {
            $opts['order'] = [$request->query->get("col") => $request->query->get("order")];
        }
        list($slice, $objects) = $this->pagination->paginate($this->viewListRoute, $this->entityCls,
            $page, $opts);
        // doctrine proxy fix
        $data = $this->serializer->normalize($objects, null,
            [AbstractNormalizer::ATTRIBUTES => $allowedAttributes]);
        $pages = $this->serializer->normalize($slice, null);

        $request->getSession()->set('prev_url', $this->viewListRoute);
        $request->getSession()->set('prev_url_params', $request->query->all());

        return $this->render($this->templateDir.'/view.html.twig', [
            'data' => $data,
            'pages' => $pages
        ]);
    }

    /**
     * Получение класса репозитория связанного с объектом CRUD'а
     * @return ObjectRepository
     */
    public function getRepository()
    {
        $repository = $this->getDoctrine()->getRepository($this->entityCls);
        if (! $repository instanceof CrudRepositoryInterface)
        {
            throw new \InvalidArgumentException(get_class($repository) . " должен определять интерфейс CrudRepositoryInterface");
        }
        return $repository;
    }

    /**
     * Получение менеджера контейнера `UnitOfWork`
     * @return ObjectManager
     */
    public function getManager()
    {
        return $this->getDoctrine()->getManagerForClass($this->entityCls);
    }

    public function redirectToPrevious(Request $request)
    {
        $session = $request->getSession();
        if (! $session->has('prev_url'))
        {
            throw new \Exception('prev_url isn\'t set' );
        }
        $url = $session->get('prev_url');
        $params = $session->get('prev_url_params');
        $session->remove('prev_url');
        $session->remove('prev_url_params');
        return $this->redirectToRoute($url, $params);
    }
}
