<?php

namespace App\Controllers;

use Phalcon\Mvc\Controller;

use League\Fractal\Manager;
use League\Fractal\Serializer\JsonApiSerializer;
use League\Fractal\TransformerAbstract;

use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use Throwable;

class BaseController extends Controller
{
    protected Manager $manager;

    public function onConstruct()
    {
        $this->manager = new Manager();
        $this->manager->setSerializer(new JsonApiSerializer());
    }

    protected function getItemResponse($item, TransformerAbstract $transformer, ?string $resourceKey = null) : array
    {
        $resource = new Item($item, $transformer);
        $rootScope = $this->manager->createData($resource);
        return $rootScope->toArray();
    }

    protected function getCollectionResponse($collection, TransformerAbstract $transformer, ?string $resourceKey = null) : array
    {
        $resource = new Collection($collection, $transformer);
        $rootScope = $this->manager->createData($resource);
        return $rootScope->toArray();
    }
}
