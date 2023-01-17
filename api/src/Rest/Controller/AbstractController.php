<?php

namespace App\Rest\Controller;

use App\Entity\Interfaces\EntityInterface;
use App\Infrastructure\ResponseData\Interfaces\ResponseDataInterface;
use App\Rest\Response\Interfaces\SuccessHttpResponseCollectorInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SfController;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController extends SfController
{
    public function __construct(
        protected readonly SuccessHttpResponseCollectorInterface $successHttpResponseCollector
    ) {
    }

    final protected function response(array $data, array $headers = []): SuccessHttpResponseCollectorInterface
    {
        $this->successHttpResponseCollector->setHeaders($headers);
        $this->successHttpResponseCollector->setHttpCode(Response::HTTP_OK);
        $this->successHttpResponseCollector->setData($data);

        return $this->successHttpResponseCollector;
    }

    final protected function responseData(
        ResponseDataInterface $responseData,
        array|Collection|EntityInterface $data,
        array $headers = []
    ): SuccessHttpResponseCollectorInterface {
        $this->successHttpResponseCollector->setHeaders($headers);
        $this->successHttpResponseCollector->setHttpCode(Response::HTTP_OK);
        $this->successHttpResponseCollector->setData($responseData->setEntities($data)->getResponse());

        return $this->successHttpResponseCollector;
    }
}