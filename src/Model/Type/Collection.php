<?php

namespace ObjectivePHP\Gateway\Model\Type;

/**
 * Class Collection
 *
 * @package ObjectivePHP\Gateway\Model\Type
 */
class Collection
{
    /**
     * @var string
     */
    protected $entity;

    /**
     * @var string
     */
    protected $referencedBy;

    /**
     * Collection constructor.
     *
     * @param string $entity
     * @param string $referencedBy
     */
    public function __construct(string $entity, string $referencedBy)
    {
        $this->setEntity($entity);
        $this->setReferencedBy($referencedBy);
    }

    /**
     * Get Entity
     *
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * Set Entity
     *
     * @param string $entity
     *
     * @return $this
     */
    public function setEntity(string $entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get ReferencedBy
     *
     * @return string
     */
    public function getReferencedBy(): string
    {
        return $this->referencedBy;
    }

    /**
     * Set ReferencedBy
     *
     * @param string $referencedBy
     *
     * @return $this
     */
    public function setReferencedBy(string $referencedBy)
    {
        $this->referencedBy = $referencedBy;

        return $this;
    }
}
