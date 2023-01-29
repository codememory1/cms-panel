<?php

namespace App\Resolver;

use App\Attribute\EntityNotFound;
use App\Entity\Interfaces\EntityInterface;
use App\Exception\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;
use LogicException;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use function Symfony\Component\String\u;

final class EntityFromRouteParameterResolver implements ValueResolverInterface
{
    private array $routeParameters = [];
    private ?ReflectionClass $reflection = null;

    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($this->supports($request, $argument)) {
            $entityRepository = $this->em->getRepository($argument->getType());
            $entityNameInCamel = (string) u($this->reflection->getShortName())->camel();
            $finedRouteParameter = $this->finedRouteParameter($entityNameInCamel);
            $entityNotFoundAttribute = $this->getAttribute($argument, EntityNotFound::class);

            $finedEntity = $entityRepository->findOneBy([
                $finedRouteParameter['property_name'] => $finedRouteParameter['value']
            ]);

            if (null === $finedEntity) {
                if (null !== $entityNotFoundAttribute) {
                    throw $entityNotFoundAttribute->class::{$entityNotFoundAttribute->method}();
                }

                throw EntityNotFoundException::page();
            }

            yield $finedEntity;
        }
    }

    /**
     * @throws ReflectionException
     */
    private function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if (class_exists($argument->getType())) {
            $this->reflection = new ReflectionClass($argument->getType());

            if ($this->reflection->implementsInterface(EntityInterface::class)) {
                $this->routeParameters = $request->attributes->get('_route_params');

                return true;
            }
        }

        return false;
    }

    #[ArrayShape(['property_name' => 'string', 'value' => 'mixed'])]
    private function finedRouteParameter(string $entityNameInCamel): ?array
    {
        foreach ($this->routeParameters as $parameterName => $value) {
            if (false === str_starts_with($parameterName, "${entityNameInCamel}_")) {
                continue;
            }

            return [
                'property_name' => explode('_', $parameterName)[1],
                'value' => $value
            ];
        }

        throw new LogicException("Failed to process {$entityNameInCamel} entity due to missing route parameter");
    }

    /**
     * @template T
     *
     * @param class-string<T> $class
     *
     * @return null|T
     */
    private function getAttribute(ArgumentMetadata $argument, string $class): ?object
    {
        $attribute = $argument->getAttributes($class);

        return count($attribute) >= 1 ? $attribute[0] : null;
    }
}