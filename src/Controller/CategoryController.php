<?php


namespace App\Controller;


use App\Entity\Category;
use App\Form\CategoryForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(name="admin_category_", path="/admin/category") */
class CategoryController extends CrudController
{
    /** @inheritdoc  */
    protected $templateDir = "admin/category";

    /** @inheritdoc */
    protected $entityCls = Category::class;

    /** @inheritdoc */
    protected $formCls = CategoryForm::class;

    /** @inheritdoc */
    protected $viewListRoute = 'admin_category_viewList';

    /** @inheritdoc */
    protected $createRoute = 'admin_category_create';

    /** @Route(name="create", path="/create") */
    public function create(Request $request)
    {
        $parents = $this->getRepository()->findAll();
        return parent::handleCreate($request, ['choices' => $parents]);
    }

    /** @Route(name="viewList", path="/list/{page}", defaults={"page"=1}) */
    public function viewList(Request $request, int $page = null)
    {
        return parent::handleViewlist($request, $page, ['id', 'name', 'linkToProducts']);
    }

    /** @Route(name="delete", path="/delete/{id}", methods={"GET"}) */
    public function delete(Request $request, int $id)
    {
        return $this->handleDelete($request, $id);
    }

    /** @Route(name="deleteAll", path="/deleteAll") */
    public function deleteAll(Request $request)
    {
        $ids = $request->query->get("ids");
        return $this->handleDeleteAll($request, $ids);
    }
}
