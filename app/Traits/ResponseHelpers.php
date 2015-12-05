<?php namespace Scheduler\Traits;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Illuminate\Http\Response as HttpResponse;
use Response;

/**
 * Class ResponseHelpers
 *
 * @package Scheduler\Traits
 * @author Sam Tape <sctape@gmail.com>
 */
trait ResponseHelpers
{
    /**
     * @var int
     */
    protected $statusCode = HttpResponse::HTTP_OK;

    /**
     * @var Manager
     */
    protected $fractal;

    /**
     * @var TransformerAbstract
     */
    protected $transformer;

    /**
     * Getter for statusCode
     *
     * @return int
     */
    protected function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Setter for statusCode
     *
     * @param int $statusCode Value to set
     *
     * @return $this
     */
    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param $item
     * @param $callback
     * @param array|string|null $includes
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithItem($item, $callback, $includes = null)
    {
        $resource = new Item($item, $callback);

        $this->addParseIncludes($includes);
        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * @param $collection
     * @param $callback
     * @param array|string|null $includes
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithCollection($collection, $callback, $includes = null)
    {
        $resource = new Collection($collection, $callback);

        $this->addParseIncludes($includes);
        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * @param array $array
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithArray(array $array, array $headers = [])
    {
        return Response::json($array, $this->statusCode, $headers);
    }

    /**
     * Wraps an array in the 'data' key of a new array
     *
     * @param array $array
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithDataArray(array $array, array $headers = [])
    {
        return $this->respondWithArray(['data' => $array], $headers);
    }

    /**
     * @param array|string|null $includes
     */
    private function addParseIncludes($includes)
    {
        if ($includes) {
            $this->fractal->parseIncludes($includes);
        }
    }
}
