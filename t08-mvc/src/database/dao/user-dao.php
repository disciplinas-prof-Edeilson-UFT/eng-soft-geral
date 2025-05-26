<?php
namespace src\database\dao;

use core\mvc\IModelRepository;

use core\mvc\traits\ModelRepositoryTrait;

require_once __DIR__ . '/../../../core/mvc/model-repository.php';
require_once __DIR__ . '/../../../core/mvc/traits/model-repository-trait.php';

interface IUserDAO extends IModelRepository{
    // adiciona ao model repository metodos especificos para a entidade User
}

class userDAO implements IUserDAO{
    use ModelRepositoryTrait;

    public function __construct(IModelRepository $modelRepository)
    {
        $this->modelRepository = $modelRepository;
    }

}