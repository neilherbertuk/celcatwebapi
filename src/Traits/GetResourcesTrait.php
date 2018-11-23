<?php

namespace neilherbertuk\celcatwebapi\Traits;

use neilherbertuk\celcatwebapi\CelcatWebAPI;

trait GetResourcesTrait
{
    /**
     * @var
     */
    protected $celcatWebAPI;

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
     * @return mixed
     */
    public function get($pagesize = 50)
    {
        if (empty($this->parameters['pageSize'])) {
                    $this->parameters['pageSize'] = $pagesize;
        }

        $this->celcatWebAPI->log()->info('Getting '.(new \ReflectionClass($this))->getShortName()); // . (empty($this->parameters) ?: ' with ' . implode(',', $this->parameters)));
        return $this->celcatWebAPI->get((empty($this->name) ? (new \ReflectionClass($this))->getShortName() : $this->name), $this->parameters);
    }

    /**
     * GetAll Operator - Uses the get operator and pagination results to request all results for a resource
     *
     * @param int $pagesize
     * @return array|mixed
     */
    public function getAll($pagesize = 1000)
    {
        if (empty($this->parameters['pageSize'])) {
                    $this->parameters['pageSize'] = $pagesize;
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
     * @param array $parameters
     * @return mixed
     */
    public function first()
    {
        $this->parameters['pageSize'] = 1;
        $this->parameters['page'] = 0;
        return $this->get();
    }

    /**
     * @param $results
     * @return bool
     */
    protected function hasPagination($results)
    {
        return !empty($results['pagination']['TotalPages']);
    }

    public function __call($method, $args)
    {
        print "Method $method called:\n";
        var_dump($args);
        return;
    }

    public function where($parameters = [])
    {
        $this->parameters = array_merge($this->parameters, $parameters);
        return $this;
    }

}