<?php

namespace core\mvc\traits;

use core\mvc\IModelRepository;

//criei para poder extender a interface IModelRepository sem precisar implementar todos os métodos nas classses
trait ModelRepositoryTrait {
    public IModelRepository $modelRepository;
    
    public function create($table, $data): bool {
        return $this->modelRepository->create($table, $data);
    }
    
    public function find($table, $conditions, $data = ['*']): array {
        return $this->modelRepository->find($table, $conditions, $data);
    }
    
    public function update($table, $conditions, $data = []): bool{
        return $this->modelRepository->update($table, $conditions, $data);
    }

    public function delete($table, $conditions): bool{
        return $this->modelRepository->delete($table, $conditions);
    }
    
}