<?php

namespace Tests\ObjectivePHP\Gateway\Model;

use ObjectivePHP\Gateway\Exception\ModelException;
use ObjectivePHP\Gateway\Model\Model;
use ObjectivePHP\Gateway\Model\ModelRegisterTrait;
use PHPUnit\Framework\TestCase;

/**
 * Class ModelRegisterTraitTest
 *
 * @package Tests\ObjectivePHP\Gateway\Model
 */
class ModelRegisterTraitTest extends TestCase
{
    public function testModelIsRegistered()
    {
        $instance = new class {
            use ModelRegisterTrait;
        };

        $model1 = (new Model('entity1', 'entity1'));
        $model2 = (new Model('entity2', 'entity2'));

        $instance->registerModel($model1);

        $this->assertAttributeEquals([$model1], 'models', $instance);

        $instance->registerModel($model2);

        $this->assertAttributeEquals([$model1, $model2], 'models', $instance);
    }

    public function testModelMatchEntity()
    {
        $instance = new class {
            use ModelRegisterTrait;
        };

        $entity1 = new class {
        };
        $entity2 = new class {
        };
        $entity3 = new class {
        };

        $model1 = (new Model(get_class($entity1), 'collection1'));
        $model2 = (new Model(get_class($entity2), 'collection2'));
        $model3 = (new Model(get_class($entity3), 'collection3'));

        $instance->registerModel($model1, $model2, $model3);

        $this->assertEquals($model2, $instance->findMatchingModel($entity2));
        $this->assertAttributeEquals([get_class($entity2) => $model2], 'matchingModels', $instance);

        $this->assertEquals($model2, $instance->findMatchingModel($entity2));
    }

    public function testNoModelMatchEntity()
    {
        $instance = new class {
            use ModelRegisterTrait;
        };

        $entity1 = new class {
        };

        $instance->registerModel(new Model(get_class($entity1), 'collection1'));

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage('No model matches provided entity');

        $instance->findMatchingModel(new class {
        });
    }
}
