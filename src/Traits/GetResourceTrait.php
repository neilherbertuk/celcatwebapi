<?php

namespace neilherbertuk\celcatwebapi\Traits;

use neilherbertuk\celcatwebapi\CelcatWebAPI;

trait GetResourceTrait
{
    /**
     * @var
     */
    protected $celcatWebAPI;

    /**
     * GetResourceTrait constructor.
     * @param CelcatWebAPI $celcatWebAPI
     */
    public function __construct(CelcatWebAPI $celcatWebAPI)
    {
        $this->celcatWebAPI = $celcatWebAPI;
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function get($parameters = [])
    {
        $this->celcatWebAPI->log()->info('Getting '. (new \ReflectionClass($this))->getShortName() . (empty($parameters) ?: ' with ' . implode($parameters)));
        return $this->celcatWebAPI->get((empty($this->name) ? (new \ReflectionClass($this))->getShortName() : $this->name), $parameters);
    }

    public function getAll($parameters = [])
    {
        $results = $this->get(['pageSize' => 1000]);

        if($this->hasPagination($results)){
            for($page = 1; $page < $results['pagination']['TotalPages']; $page++){
                $pageResults = $this->get(['pageSize' => 1000, 'page' => $page]);

                $results['pagination']['CurrentPage'] = $pageResults['parameters']['page'] + 1;
                unset($pageResults['pagination'], $pageResults['parameters']);
                $results = array_merge_recursive($results, $pageResults);
            }
        }
        return $results;
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function first($parameters = [])
    {
        $parameters['pageSize'] = 1;
        $parameters['page'] = 0;
        return $this->get($parameters);
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

}