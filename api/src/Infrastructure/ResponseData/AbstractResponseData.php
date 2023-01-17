<?php

namespace App\Infrastructure\ResponseData;

use App\Entity\Interfaces\EntityInterface;
use App\Infrastructure\ResponseData\Builder\AllowedPropertyBuilder;
use App\Infrastructure\ResponseData\Builder\PropertyInterceptorBuilder;
use App\Infrastructure\ResponseData\Builder\PropertyMethodBuilder;
use App\Infrastructure\ResponseData\Interfaces\ConstraintAvailabilityHandlerInterface;
use App\Infrastructure\ResponseData\Interfaces\ConstraintHandlerInterface;
use App\Infrastructure\ResponseData\Interfaces\ConstraintInterface;
use App\Infrastructure\ResponseData\Interfaces\ConstraintSystemHandlerInterface;
use App\Infrastructure\ResponseData\Interfaces\ConstraintValueHandlerInterface;
use App\Infrastructure\ResponseData\Interfaces\ResponseDataInterface;
use App\Package\Reflection\Reflection;
use Doctrine\Common\Collections\Collection;
use ReflectionProperty;
use Symfony\Component\DependencyInjection\ReverseContainer;

abstract class AbstractResponseData implements ResponseDataInterface
{
    protected array $entities = [];
    protected bool $asFirstResponse = false;
    protected Reflection $reflection;
    protected array $ignoredProperties = [];
    protected array $onlyProperties = [];
    protected array $response = [];

    public function __construct(
        protected readonly ReverseContainer $container
    ) {
        $this->reflection = new Reflection(static::class);
    }

    public function setEntities(EntityInterface|Collection|array $entities): self
    {
        if ($entities instanceof EntityInterface) {
            $this->entities = [$entities];
            $this->asFirstResponse = true;
        } else {
            $this->entities = $entities instanceof Collection ? $entities->toArray() : $entities;
        }

        return $this;
    }

    public function setIgnoredProperties(array $propertyNames): self
    {
        $this->ignoredProperties = $propertyNames;

        return $this;
    }

    public function addIgnoreProperty(string $propertyName): self
    {
        $this->ignoredProperties[] = $propertyName;

        return $this;
    }

    public function setOnlyProperties(array $propertyNames): self
    {
        $this->onlyProperties = $propertyNames;

        return $this;
    }

    public function addOnlyProperty(string $propertyName): self
    {
        $this->onlyProperties[] = $propertyName;

        return $this;
    }

    public function getResponse(): array
    {
        $this->handle();

        if ($this->asFirstResponse) {
            return $this->response[0] ?? [];
        }

        return $this->response;
    }

    private function handle(): void
    {
        $ignoredProperties = $this->ignoredProperties;
        $onlyProperties = $this->onlyProperties;

        foreach ($this->entities as $entity) {
            $response = [];

            $properties = $this->reflection->getStrictlyClassProperties(static function(ReflectionProperty $property) use ($ignoredProperties, $onlyProperties) {
                if (in_array($property->getName(), $ignoredProperties, true)) {
                    return false;
                }

                return [] === $onlyProperties || in_array($property->getName(), $onlyProperties, true);
            });

            foreach ($properties as $property) {
                $propertyDataDeterminant = $this->propertyHandler($property, $entity);

                if (null !== $propertyDataDeterminant) {
                    $propertyValue = $propertyDataDeterminant->getPropertyValue() ?: $propertyDataDeterminant->getDefaultPropertyValue();

                    $response[$propertyDataDeterminant->getPropertyNameInResponse()] = $propertyValue;
                }
            }

            $this->response[] = $response;
        }
    }

    private function propertyHandler(ReflectionProperty $property, EntityInterface $entity): ?PropertyDataDeterminant
    {
        $propertyIsPassed = true;
        $allowedPropertyBuilder = new AllowedPropertyBuilder($property);
        $propertyMethodBuilder = new PropertyMethodBuilder('get', $property->getName());
        $propertyDataDeterminant = new PropertyDataDeterminant();

        $propertyDataDeterminant->setPropertyMethodRepository($propertyMethodBuilder);
        $propertyDataDeterminant->setPropertyName($allowedPropertyBuilder->getPropertyName());
        $propertyDataDeterminant->setPropertyNameInResponse($allowedPropertyBuilder->getPropertyNameInResponse());
        $propertyDataDeterminant->setPropertyValue($this->getPropertyValue($entity, $propertyMethodBuilder));
        $propertyDataDeterminant->setDefaultPropertyValue($property->getValue($this));

        foreach ($property->getAttributes() as $attribute) {
            $attributeInstance = $attribute->newInstance();

            if ($attributeInstance instanceof ConstraintInterface) {
                $propertyInterceptorBuilder = new PropertyInterceptorBuilder($attributeInstance, $attributeInstance->getHandler());
                $constraintHandler = $this->getConstraintHandler($propertyInterceptorBuilder);

                $constraintHandler->setEntityIteration($entity);
                $constraintHandler->setResponseData($this);

                if ($constraintHandler instanceof ConstraintSystemHandlerInterface) {
                    $constraintHandler->setPropertyDataDeterminant($propertyDataDeterminant);
                    $constraintHandler->handle($attributeInstance);
                }

                if ($constraintHandler instanceof ConstraintAvailabilityHandlerInterface) {
                    if (false === $constraintHandler->handle($attributeInstance)) {
                        $propertyIsPassed = false;

                        break;
                    }
                }

                if ($constraintHandler instanceof ConstraintValueHandlerInterface) {
                    $propertyDataDeterminant->setPropertyValue(
                        $constraintHandler->handle($attributeInstance, $this, $propertyDataDeterminant->getPropertyValue())
                    );
                }
            }
        }

        return $propertyIsPassed ? $propertyDataDeterminant : null;
    }

    /**
     * @return ConstraintHandlerInterface
     */
    private function getConstraintHandler(PropertyInterceptorBuilder $propertyInterceptorBuilder): object
    {
        return $this->container->getService($propertyInterceptorBuilder->handler);
    }

    private function getPropertyValue(EntityInterface $entity, PropertyMethodBuilder $propertyMethodBuilder): mixed
    {
        return method_exists($entity, $propertyMethodBuilder->getMethodName()) ? $entity->{$propertyMethodBuilder->getMethodName()}() : null;
    }
}