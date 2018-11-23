<?php

namespace neilherbertuk\CelcatWebAPI\Classes;

use Illuminate\Support\Facades\Log as Logs;
class Log
{
    /**
     * @var
     */
    protected $celcatWebAPI;

    /**
     * Log constructor
     * @param $celcatWebAPI
     */
    public function __construct($celcatWebAPI)
    {
        $this->celcatWebAPI = $celcatWebAPI;
    }

    /**
     * Adds string to logs array
     *
     * @param  string  $string
     * @return void
     */
    public function info($string)
    {
        $this->celcatWebAPI->logs[] = ['string' => $string, 'type' => 'info'];
    }

    /**
     * Write a string as error output.
     *
     * @param  string  $string
     * @return void
     */
    public function error($string)
    {
        $this->celcatWebAPI->logs[] = ['string' => $string, 'type' => 'error'];
    }

    public function transferLogs()
    {
        foreach ($this->celcatWebAPI->logs as $log) {
            if ($log['type'] == "info") {
                Logs::info("Celcat Web API: ".$log['string']);
            } else if ($log['type'] == "error") {
                Logs::error("Celcat Web API: ".$log['string']);
            }
        }
    }

}