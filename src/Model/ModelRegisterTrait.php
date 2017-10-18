<?php

namespace ObjectivePHP\Gateway\Model;

use ObjectivePHP\Gateway\Exception\ModelException;

/**
 * Trait ModelMatcherTrait
 *
 * @package ObjectivePHP\Gateway\Model
 */
trait ModelRegisterTrait
{
    /**
     * @var Model[]
     */
    protected $models = [];

    /**
     * @var Model[]
     */
    protected $matchingModels = [];

    /**
     * Register a model
     *
     * @param Model[] $models
     */
    public function registerModel(Model ...$models)
    {
        array_push($this->models, ...$models);
    }

    /**
     * Find a model matching a provided entity
     *
     * @param object $entity
     *
     * @return Model
     *
     * @throws ModelException
     */
    public function findMatchingModel($entity): Model
    {
        if (isset($this->matchingModels[get_class($entity)])) {
            return $this->matchingModels[get_class($entity)];
        }

        /** @var Model $model */
        foreach ($this->models as $model) {
            if ($model->getRootEntity() == get_class($entity)) {
                $this->matchingModels[get_class($entity)] = $model;
                return $model;
            }
        }

        throw new ModelException('No model matches provided entity');
    }
}
