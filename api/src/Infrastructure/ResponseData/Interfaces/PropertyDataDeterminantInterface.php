<?php

namespace App\Infrastructure\ResponseData\Interfaces;

use App\Infrastructure\ResponseData\Builder\PropertyMethodBuilder;

interface PropertyDataDeterminantInterface
{
    public function getPropertyMethodRepository(): ?PropertyMethodBuilder;

    public function setPropertyMethodRepository(PropertyMethodBuilder $propertyMethodBuilder): self;

    public function getPropertyName(): ?string;

    public function setPropertyName(string $name): self;

    public function getPropertyNameInResponse(): ?string;

    public function setPropertyNameInResponse(string $name): self;

    public function getPropertyValue(): mixed;

    public function setPropertyValue(mixed $value): self;

    public function getDefaultPropertyValue(): mixed;

    public function setDefaultPropertyValue(mixed $value): self;
}