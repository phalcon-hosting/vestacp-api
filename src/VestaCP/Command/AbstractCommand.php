<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace VestaCP\Command;

use VestaCP\Client;

/**
 * Class AbstractCommand
 *
 * @package VestaCP\Command\User
 */
abstract class AbstractCommand implements CommandInterface
{

    protected $arguments = array();

    /**
     * @var Client
     */
    protected $client;

    /**
     * @param Client $client
     *
     * @return mixed|void
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }

}