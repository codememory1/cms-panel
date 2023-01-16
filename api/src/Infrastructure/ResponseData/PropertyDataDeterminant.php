<?php

namespace App\Infrastructure\ResponseData;

use App\Infrastructure\ResponseData\Builder\PropertyMethodBuilder;
use App\Infrastructure\ResponseData\Interfaces\PropertyDataDeterminantInterface;

final class PropertyDataDeterminant implements PropertyDataDeterminantInterface
{
    private ?PropertyMethodBuilder $propertyMethodBuilder = null;
    private ?string $propertyName = null;
    private ?string $propertyNameInResponse = null;
    private mixed $propertyValue = null;
    private mixed $defaultPropertyValue = null;

    public function getPropertyMethodRepository(): ?PropertyMethodBuilder
    {
        return $this->propertyMethodBuilder;
    }

    public function setPropertyMethodRepository(PropertyMethodBuilder $propertyMethodBuilder): self
    {
        $this->propertyMethodBuilder = $propertyMethodBuilder;

        return $this;
    }

    public function getPropertyName(): ?string
    {
        return $this->propertyName;
    }

    public function setPropertyName(string $name): self
    {
        $this->propertyName = $name;

        return $this;
    }

    public function getPropertyNameInResponse(): ?string
    {
        return $this->propertyNameInResponse;
    }

    public function setPropertyNameInResponse(string $name): self
    {
        $this->propertyNameInResponse = $name;

        return $this;
    }

    public function getPropertyValue(): mixed
    {
        return $this->propertyValue;
    }

    public function setPropertyValue(mixed $value): self
    {
        $this->propertyValue = $value;

        return $this;
    }

    public function getDefaultPropertyValue(): mixed
    {
        return $this->defaultPropertyValue;
    }

    public function setDefaultPropertyValue(mixed $value): self
    {
        $this->defaultPropertyValue = $value;

        return $this;
    }
}