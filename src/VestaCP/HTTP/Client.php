<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace VestaCP\HTTP;


use VestaCP\ErrorCodes;

class Client
{

    /**
     * @var string
     */
    protected $host;

    public function __construct($host)
    {
        $this->host = $host;

        // test the connection to the host
        if ($this->send() === false) {
            throw new Exception(sprintf('Cannot connect to host %s', $host), ErrorCodes::ERROR_CANNOT_CONNECT_TO_HOST);
        }
    }

    public function send(array $postVars = array())
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->host);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postVars));

        return curl_exec($curl);
    }
}