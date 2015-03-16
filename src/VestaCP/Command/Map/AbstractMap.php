<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace VestaCP\Command\Map;


use VestaCP\Client;
use VestaCP\Command\CommandInterface;

/**
 * Class AbstractMap
 *
 * @package VestaCP\Command\Map
 */
abstract class AbstractMap implements MapInterface
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var CommandInterface
     */
    protected $command;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param CommandInterface $command
     */
    public function setCommand(CommandInterface $command)
    {
        $this->command = $command;
    }

    /**
     * @param array $arguments
     *
     * @return mixed
     * @throws \VestaCP\Command\Exception
     * @throws \VestaCP\Exception
     */
    public function executeCommand(array $arguments)
    {
        $this->command->setArguments($arguments);

        return $this->client->execute($this->command, $arguments);
    }
}