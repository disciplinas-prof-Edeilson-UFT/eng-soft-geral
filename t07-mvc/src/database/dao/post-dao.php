<?php
namespace src\database\dao;
use core\mvc\IModelRepository;
use core\mvc\traits\ModelRepositoryTrait;

require_once __DIR__ . '/../../../core/mvc/model-repository.php';
require_once __DIR__ . '/../../../core/mvc/traits/model-repository-trait.php';

interface IPostDAO extends IModelRepository{

}

class postDAO implements IPostDAO{
    use ModelRepositoryTrait;

    public function __construct(IModelRepository $modelRepository)
    {
        $this->modelRepository = $modelRepository;
    }

}