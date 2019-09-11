<?php

namespace neilherbertuk\celcatwebapi\Classes;

class RequestBuilder
{
    /**
     * @var
     */
    private $requestMethod;

    /**
     * @var
     */
    private $config;

    /**
     * QueryBuilder constructor.
     * @param $requestMethod
     * @param $config
     */
    public function __construct($requestMethod = 'GET', $config = [])
    {
        $this->requestMethod = $requestMethod;
        $this->config = $config;
    }

    /**
     * @param $parameters
     * @return array
     */
    public function options($parameters = [])
    {

        $options = array_merge(
            $this->header(),
            $this->SSLOptions(),
            $this->parameters($parameters)
        );

        return $options;
    }

    /**
     * @return array
     */
    protected function header()
    {
        return ['headers' => [
                    'Accept' => 'application/json',
                    'Content-type' => 'application/json',
                    'APICode' => $this->config['APICode'],
                    'TimetableId' => env('CELCAT_WEB_API_TIMETABLE_ID', '1'),
                ]
        ];
    }

    /**
     * @return mixed
     */
    protected function SSLOptions()
    {
        if ($this->config['VerifySSL']) {
            if (file_exists(base_path($this->config['PEM']))) {
                return ['verify' => $this->config['PEM']];
            } else {
                // TODO - Error Handling
                throw new \RuntimeException('PEM Certificate does not exist');
            }
        }
        return ['verify' => false];
    }

    /**
     * @param $parameters
     * @return mixed
     */
    protected function parameters($parameters)
    {
        $options = [];

        if ($this->requestMethod == "GET") {
            $options['query'] = $parameters;
        } else {
            $options['form_params'] = $parameters;
        }
        return $options;
    }

    /**
     * @param $name
     * @return string
     */
    public function URL($name)
    {
        return rtrim($this->config['ServerAddress'], '/').'/'.$name;
    }
}