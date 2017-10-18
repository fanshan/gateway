<?php

namespace ObjectivePHP\Gateway\Model\Property;

use ObjectivePHP\Gateway\Model\Type\TypeInterface;

/**
 * Interface PropertyInterface
 *
 * @package ObjectivePHP\Gateway\Model\Property
 */
interface PropertyInterface
{
    /**
     * Get the property name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get the property type
     *
     * @return TypeInterface
     */
    public function getType(): TypeInterface;
}
