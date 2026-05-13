<?php

namespace App\Modules\Hotel\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Model;
use DomainException;
use Illuminate\Support\Facades\Log;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct()
    {
        $this->model = $this->resolveModel();
    }

    abstract protected function getModelClass(): string;

    protected function resolveModel(): Model
    {
        $modelClass = $this->getModelClass();

        return new $modelClass();
    }

    public function all()
    {
        return $this->model->newQuery()->get();
    }

    public function findById(string $id): ?Model
    {
        return $this->model->find($id);
    }

    public function create(array $data): Model
    {
        $repoId = uniqid('repo_', true);

        Log::info('Iniciando creación en repositorio base', [
            'repo_id' => $repoId,
            'function' => 'create',
            'model_class' => $this->getModelClass(),
            'data_keys' => array_keys($data)
        ]);

        $model = $this->model->create($data);

        Log::info('Modelo creado exitosamente en repositorio', [
            'repo_id' => $repoId,
            'function' => 'create',
            'model_id' => $model->getKey(),
            'model_class' => get_class($model)
        ]);

        return $model;
    }

    public function update(string $id, array $data): Model
    {
        $repoId = uniqid('repo_', true);

        Log::info('Iniciando actualizaci\u00f3n en repositorio base', [
            'repo_id' => $repoId,
            'function' => 'update',
            'model_id' => $id,
            'model_class' => $this->getModelClass(),
            'data_keys' => array_keys($data)
        ]);

        $model = $this->findById($id);

        if (!$model) {
            Log::error('Modelo no encontrado para actualizaci\u00f3n', [
                'repo_id' => $repoId,
                'function' => 'update',
                'model_id' => $id
            ]);

            throw new DomainException('Resource not found.');
        }

        $model->update($data);

        Log::info('Modelo actualizado exitosamente en repositorio', [
            'repo_id' => $repoId,
            'function' => 'update',
            'model_id' => $model->getKey(),
            'model_class' => get_class($model)
        ]);

        return $model;
    }

    public function delete(string $id): bool
    {
        $repoId = uniqid('repo_', true);

        Log::info('Iniciando eliminaci\u00f3n en repositorio base', [
            'repo_id' => $repoId,
            'function' => 'delete',
            'model_id' => $id,
            'model_class' => $this->getModelClass()
        ]);

        $model = $this->findById($id);

        if (!$model) {
            Log::warning('Modelo no encontrado para eliminaci\u00f3n', [
                'repo_id' => $repoId,
                'function' => 'delete',
                'model_id' => $id
            ]);

            return false;
        }

        $deleted = (bool) $model->delete();

        Log::info('Modelo eliminado de repositorio base', [
            'repo_id' => $repoId,
            'function' => 'delete',
            'model_id' => $id,
            'deleted' => $deleted
        ]);

        return $deleted;
    }
}
