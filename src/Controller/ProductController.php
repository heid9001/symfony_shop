<?php


namespace App\Controller;


use App\Entity\Category;
use App\Form\CategoryForm;
use App\Form\ProductForm;
use App\Service\Pagination;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;

/** @Route(name="admin_product_", path="/admin/product") */
class ProductController extends CrudController
{
    /** @inheritdoc  */
    protected $templateDir = "admin/product";

    /** @inheritdoc */
    protected $entityCls = Product::class;

    /** @inheritdoc */
    protected $formCls = ProductForm::class;

    /** @inheritdoc */
    protected $viewListRoute = 'admin_product_viewList';

    /** @inheritdoc */
    protected $createRoute = 'admin_product_create';


    /** @Route(name="create", path="/create") */
    public function create(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return parent::handleCreate($request, ['choices' => $categories]);
    }

    /** @Route(name="viewList", path="/list/{page}", defaults={"page"=1}) */
    public function viewlist(Request $request, $page)
    {
        return parent::handleViewlist($request, $page, ['id', 'linkToCategory', 'name']);
    }

    /** @Route(name="deleteAll", path="/deleteAll") */
    public function deleteAll(Request $request)
    {
        return parent::handleDeleteAll($request, $request->query->get('ids', []));
    }

    /** @Route(name="delete", path="/delete/{id}") */
    public function delete(Request $request,$id)
    {
        return parent::handleDelete($request,$id);
    }
}
