<?php

namespace Tests\ObjectivePHP\Gateway\Model {

    use ObjectivePHP\Gateway\Model\Model;
    use ObjectivePHP\Gateway\Model\Property\PropertyInterface;
    use PHPUnit\Framework\TestCase;
    use Tests\ObjectivePHP\Gateway\Model\Entity\CompositeKeyEntity;
    use Tests\ObjectivePHP\Gateway\Model\Entity\Entity;
    use Tests\ObjectivePHP\Gateway\Model\Entity\VoidEntity;
    use Zend\Hydrator\ClassMethods;
    use Zend\Hydrator\HydratorInterface;

    class ModelTest extends TestCase
    {
        public function testRootEntityAccessors()
        {
            $model = new Model('test');

            $this->assertEquals('test', $model->getRootEntity());
            $this->assertAttributeEquals($model->getRootEntity(), 'rootEntity', $model);
        }

        public function testCollectionAccessors()
        {
            $model = new Model('test', 'collection');

            $this->assertEquals('collection', $model->getCollection());
            $this->assertAttributeEquals($model->getCollection(), 'collection', $model);
        }

        public function testIdentifierAccessors()
        {
            $model = new Model();

            $this->assertEquals(['id'], $model->getIdentifier());

            $model->setIdentifier('new_id');

            $this->assertEquals(['new_id'], $model->getIdentifier());

            $model->setIdentifier(['id', 'another_id']);

            $this->assertEquals(['id', 'another_id'], $model->getIdentifier());

            $this->assertAttributeEquals($model->getIdentifier(), 'identifier', $model);
        }

        public function testHydratorAccessors()
        {
            $model = new Model();

            $hydrator = $this->getMockBuilder(HydratorInterface::class)->getMock();

            $this->assertInstanceOf(ClassMethods::class, $model->getHydrator());

            $model->setHydrator($hydrator);

            $this->assertEquals($hydrator, $model->getHydrator());
            $this->assertAttributeEquals($model->getHydrator(), 'hydrator', $model);
        }

        public function testDataIsExtract()
        {
            $model = new Model();

            $entity = new class
            {
            };

            $hydrator = $this->getMockBuilder(HydratorInterface::class)->getMock();
            $hydrator->expects($this->once())->method('extract')->with($entity);

            $model->setHydrator($hydrator);

            $model->extractData($entity);
        }

        public function testEntityIsNew()
        {
            $model = new Model();

            $entity = new Entity();

            $this->assertFalse($model->isNew($entity));

            $entity->setId(1);

            $this->assertTrue($model->isNew($entity));

            $this->assertFalse($model->isNew(new VoidEntity()));
        }

        public function testEntityIsNewWithCompositeKey()
        {
            $model = new Model();
            $model->setIdentifier(['keyOne', 'keyTwo', 'keyThree']);

            $entity = new CompositeKeyEntity();

            $this->assertFalse($model->isNew($entity));

            $entity->setKeyOne(1);

            $this->assertFalse($model->isNew($entity));

            $entity->setKeyOne(1);
            $entity->setKeyTwo(2);
            $entity->setKeyThree(3);

            $this->assertTrue($model->isNew($entity));

            $entity->setKeyOne(null);
            $entity->setKeyTwo(null);
            $entity->setKeyThree(null);

            $this->assertFalse($model->isNew($entity));
        }

        public function testDelegateConstructorIsExecuted()
        {
            $model = new class extends Model
            {
                public function init()
                {
                    $this->properties[] = 'field';
                }
            };

            $this->assertAttributeEquals(['field'], 'properties', $model);
        }

        public function testPropertyIsRegistered()
        {
            $model = new Model();

            $property  = $this->getMockBuilder(PropertyInterface::class)->getMock();
            $property->expects($this->once())->method('getName')->willReturn('propertyName');

            $model->registerProperty($property);

            $this->assertAttributeEquals(['propertyName' => $property], 'properties', $model);
        }
    }
}

namespace Tests\ObjectivePHP\Gateway\Model\Entity {

    class Entity
    {
        protected $id;

        public function getId()
        {
            return $this->id;
        }

        public function setId(int $id)
        {
            $this->id = $id;

            return $this;
        }
    }

    class VoidEntity
    {
    }

    class CompositeKeyEntity
    {
        protected $keyOne;

        protected $keyTwo;

        protected $keyThree;

        /**
         * Get KeyOne
         *
         * @return mixed
         */
        public function getKeyOne()
        {
            return $this->keyOne;
        }

        /**
         * Set KeyOne
         *
         * @param mixed $keyOne
         *
         * @return $this
         */
        public function setKeyOne($keyOne)
        {
            $this->keyOne = $keyOne;

            return $this;
        }

        /**
         * Get KeyTwo
         *
         * @return mixed
         */
        public function getKeyTwo()
        {
            return $this->keyTwo;
        }

        /**
         * Set KeyTwo
         *
         * @param mixed $keyTwo
         *
         * @return $this
         */
        public function setKeyTwo($keyTwo)
        {
            $this->keyTwo = $keyTwo;

            return $this;
        }

        /**
         * Get KeyThree
         *
         * @return mixed
         */
        public function getKeyThree()
        {
            return $this->keyThree;
        }

        /**
         * Set KeyThree
         *
         * @param mixed $keyThree
         *
         * @return $this
         */
        public function setKeyThree($keyThree)
        {
            $this->keyThree = $keyThree;

            return $this;
        }
    }
}
