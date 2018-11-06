<?php

namespace neilherbertuk\celcatwebapi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Config\Repository as Config;

use neilherbertuk\celcatwebapi\Classes\RequestBuilder;
use neilherbertuk\celcatwebapi\Classes\Log;
use neilherbertuk\celcatwebapi\Traits\ResourcesTrait;
use RuntimeException;

class CelcatWebAPI
{
    use ResourcesTrait;
    /**
     * @var - Stores instance of config settings
     */
    private $config;

    /**
     * @var - Stores log entries to output in case of error
     */
    public $logs;

    /**
     * CelcatWebAPI constructor.
     * @param $config
     */
    public function __construct(Config $config)
    {
        $this->setConfiguration($config);
    }

    /**
     * @param Config $config
     */
    private function setConfiguration(Config $config)
    {
        if ($config->has('celcat')) {
            $this->config['ServerAddress'] = $config->get('celcat.ServerAddress');
            $this->config['APICode'] = $config->get('celcat.APICode') ?: $this->throwRunTimeException('No API Code Provided');
            $this->config['VerifySSL'] = $config->get('celcat.VerifySSL');
            $this->config['PEM'] = $config->get('celcat.PEM');
            $this->config['DEBUG'] = $config->get('celcat.DEBUG') ?: false;
            $this->config['PROXY'] = $config->get('celcat.PROXY') ?: null;
        } else {
            throw new RunTimeException('No config found');
        }
    }

    /**
     * @param $exceptionMessage
     */
    private function throwRunTimeException($exceptionMessage)
    {
        throw new RuntimeException($exceptionMessage);
    }

    /**
     * Perform an API Query from the Celcat Web API
     *
     * @param $name
     * @param string $requestMethod
     * @param array $parameters
     * @return mixed
     */
    private function query($name, $requestMethod = 'GET', $parameters = [])
    {
        $this->log()->info("Starting to perform query - resource: {$name} method: {$requestMethod}". (empty($parameters ?: " parameters: ". implode($parameters))));
        $client = new Client();
        if ($this->config['PROXY']) {
            $client = new Client(['proxy' => $this->config['PROXY']]);
            if(!empty($this->config['PROXY']['no'])) {
                putenv('no_proxy='. implode(' ,', $this->config['PROXY']['no']));
            }
        }

        $options = $this->buildRequest($requestMethod)->options($parameters);
        $url = $this->buildRequest()->URL($name);

        try{
            $request = $client->request($requestMethod, $url, $options);
            $header = $request->getHeaders();
            if($request->getStatusCode() >= 200 and $request->getStatusCode() <= 299) {

                $this->log()->info('Received ' . $request->getStatusCode());

                // Build object to return
                // Include pagination details
                if ($header['Pagination'] !== null) {
                    $result['pagination'] = json_decode($header['Pagination'][0], true);
                }

                if (!empty($parameters)) {
                    $result['parameters'] = $parameters;
                }
                $result['data'] = json_decode($request->getBody()->getContents(), true);

                return $result;
            } else {
                // TODO - Error Handling
                $this->log()->error('An error occurred, received a '. $request->getStatusCode());
                $this->log()->transferLogs();
                $this->throwRunTimeException('An error occurred, received a '. $request->getStatusCode());
            }
        }
        catch (\Exception $exception){
            if($exception instanceof ClientException){
                if($exception->getCode() == 404) {
                    $this->log()->info('Received '. $exception->getCode());
                    $result['error']['code'] = 404;
                    $result['error']['message'] = "No Results Found";
                    return $result;
                }
            }
            // TODO - Error Handling
            $this->log()->error('An error occurred, received a '. $exception->getCode() . ' '. $exception->getMessage());
            $this->log()->transferLogs();
            $this->throwRunTimeException('An error occurred, received a '. $exception->getCode());
        }
    }

    /**
     * Returns RequestBuilder instance - used to build an api request
     *
     * @param string $requestMethod
     * @return RequestBuilder
     */
    private function buildRequest($requestMethod = 'GET'){
        return new RequestBuilder($requestMethod, $this->config);
    }

    /**
     * Performs a get request to the Celcat Web API
     *
     * @param $name
     * @param array $parameters
     * @return mixed
     */
    public function get($name, $parameters = [])
    {
        return self::query($name, 'GET', $parameters);
    }

    /**
     * Returns a log instance
     *
     * @return Log
     */
    public function log(){
        return new Log($this);
    }

}