<?php

use App\models\Category;
use App\Core\BaseService;

class CategoryService extends BaseService
{
    private $repository;

    function __construct()
    {
        $this->repository = $this->repository('CategoryRepository');
    }

    public function create(Category $category)
    {
        try {
            $this->repository->create($category);
        } catch (PDOException $e) {
            error_log('Erro ao cadastrar categoria: ' . $e->getMessage());
            throw new Exception('Erro ao cadastrar categoria');
        }
    }

    public function update(Category $category)
    {
        $oldCategory = $this->get($category->getId());
        if (is_null($oldCategory)) :
            throw new Exception('Categoria não encontrada');
        endif;

        try {
            $this->repository->update($category);
        } catch (PDOException $e) {
            error_log('Erro ao atualizar categoria: ' . $e->getMessage());
            throw new Exception('Erro ao atualizar categoria');
        }
    }

    public function get(int $id)
    {
        try {
            return $this->repository->get($id);
        } catch (\PDOException $e) {
            error_log('Erro ao buscar categoria: ' . $e->getMessage());
            throw new Exception('Erro ao buscar categoria');
        }
    }

    public function list()
    {
        try {
            return $this->repository->list();
        } catch (\PDOException $e) {
            error_log('Erro ao listar categorias: ' . $e->getMessage());
            throw new Exception('Erro ao listar categoria');
        }
    }

    public function remove($id)
    {
        try {
            $this->repository->remove($id);
        } catch (\PDOException $e) {
            error_log('Erro ao remover categoria: ' . $e->getMessage());
            throw new Exception('Erro ao remover categoria');
        }
    }
}