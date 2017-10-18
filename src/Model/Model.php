<?php

namespace ObjectivePHP\Gateway\Model;

use ObjectivePHP\Gateway\Model\Property\PropertyInterface;
use ObjectivePHP\Primitives\String\Camel;
use Zend\Hydrator\ClassMethods;
use Zend\Hydrator\HydratorAwareInterface;
use Zend\Hydrator\HydratorAwareTrait;
use Zend\Hydrator\HydratorInterface;

/**
 * Class Model
 *
 * @package ObjectivePHP\Gateway\Model
 */
class Model implements HydratorAwareInterface
{
    use HydratorAwareTrait;

    /**
     * @var string The class name's root entity of the model
     */
    protected $rootEntity;

    /**
     * @var string The name of the collection where entities are collected
     */
    protected $collection;

    /**
     * @var string|array The entity identifier
     */
    protected $identifier = ['id'];

    /**
     * @var string[]
     */
    protected $properties = [];

    /**
     * Model constructor.
     *
     * @param string $rootEntity
     * @param string $collection
     */
    public function __construct(string $rootEntity = null, string $collection = null)
    {
        if ($rootEntity) {
            $this->rootEntity = $rootEntity;
        }

        if ($collection) {
            $this->collection = $collection;
        }

        $this->init();
    }

    /**
     * Delegate constructor
     */
    public function init()
    {
    }

    /**
     * Get RootEntity
     *
     * @return string
     */
    public function getRootEntity()
    {
        return $this->rootEntity;
    }

    /**
     * Get Collection
     *
     * @return string
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Get Identifier
     *
     * @return array
     */
    public function getIdentifier(): array
    {
        return $this->identifier;
    }

    /**
     * Set Identifier
     *
     * @param array|string $identifier
     *
     * @return $this
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = (array) $identifier;

        return $this;
    }

    /**
     * Get Hydrator
     *
     * @return HydratorInterface
     */
    public function getHydrator(): HydratorInterface
    {
        return $this->hydrator ?: new ClassMethods();
    }

    /**
     * Extract an array representation of an entity
     *
     * @param object $entity
     *
     * @return array
     */
    public function extractData($entity)
    {
        return $this->getHydrator()->extract($entity);
    }

    /**
     * Tells if an entity is new
     *
     * @param object $entity
     *
     * @return bool
     */
    public function isNew($entity)
    {
        $identifier = $this->getIdentifier();

        $filtered = array_filter($this->extractData($entity), function ($value, $key) use ($identifier) {
            return (in_array(Camel::case($key, Camel::LOWER), $identifier)) && !is_null($value);
        }, ARRAY_FILTER_USE_BOTH);

        return count($filtered) == count($this->getIdentifier());
    }

    /**
     * Register a property
     *
     * @param PropertyInterface[] $properties
     *
     * @return $this
     */
    public function registerProperty(PropertyInterface ...$properties)
    {
        foreach ($properties as $property) {
            $this->properties[$property->getName()] = $property;
        }

        return $this;
    }
}
