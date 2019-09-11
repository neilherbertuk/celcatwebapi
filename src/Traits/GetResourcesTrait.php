<?php

namespace neilherbertuk\celcatwebapi\Traits;

use neilherbertuk\celcatwebapi\CelcatWebAPI;

/**
 * Trait GetResourcesTrait
 * @package neilherbertuk\celcatwebapi\Traits
 */
trait GetResourcesTrait
{
    /**
     * @var
     */
    protected $celcatWebAPI;

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * GetResourcesTrait constructor.
     * @param CelcatWebAPI $celcatWebAPI
     */
    public function __construct(CelcatWebAPI $celcatWebAPI)
    {
        $this->celcatWebAPI = $celcatWebAPI;
    }

    /**
     * Get Operator - Performs a single request for a resource
     *
     * @param int $pageSize
     * @return mixed
     * @throws \ReflectionException
     */
    public function get($parameters = [])
    {
	$this->parameters = array_merge($this->parameters, $parameters);
        if (empty($this->parameters['pageSize'])) {
            $this->parameters['pageSize'] = 50;
        }

        $this->celcatWebAPI->log()->info('Getting '.(new \ReflectionClass($this))->getShortName());
        return $this->celcatWebAPI->get((empty($this->name) ? (new \ReflectionClass($this))->getShortName() : $this->name), $this->parameters);
    }

    /**
     * GetAll Operator - Uses the get operator and pagination results to request all results for a resource
     *
     * @param int $pageSize
     * @return array|mixed
     * @throws \ReflectionException
     */
    public function getAll($parameters = [])
    {

	$this->parameters = array_merge($this->parameters, $parameters);	
        if (empty($this->parameters['pageSize'])) {
            $this->parameters['pageSize'] = 1000;
        }

        $results = $this->get();

        if ($this->hasPagination($results)) {
            for ($page = 1; $page < $results['pagination']['TotalPages']; $page++) {
                $this->parameters['page'] = $page;
                $pageResults = $this->get();

                $results['pagination']['CurrentPage'] = $pageResults['parameters']['page'] + 1;
                unset($pageResults['pagination'], $pageResults['parameters']);
                $results = array_merge_recursive($results, $pageResults);
            }
        }
        return $results;
    }

    /**
     * First Operator - Requests the first element of a resource
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public function first()
    {
        $this->parameters['pageSize'] = 1;
        $this->parameters['page'] = 0;
        return $this->get();
    }

    /**
     * Where Operator - Chainable method to add parameters to requests
     *
     * @param array $parameters
     * @return $this
     */
    public function where($parameters = [])
    {
        $this->parameters = array_merge($this->parameters, $parameters);
        return $this;
    }

    /**
     * Does the result have pagination?
     *
     * @param $results
     * @return bool
     */
    protected function hasPagination($results)
    {
        return !empty($results['pagination']['TotalPages']);
    }
}
