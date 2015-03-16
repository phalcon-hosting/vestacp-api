<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace VestaCP\Command;

use VestaCP\Client;

/**
 * Interface MethodInterface
 *
 * @package VestaCP\Methods
 */
interface CommandInterface
{

    /**
     * @param Client $client
     *
     * @return mixed
     */
    public function setClient(Client $client);

    /**
     * @return Client
     */
    public function getClient();

    /**
     * @return string
     */
    public function getCommand();

    /**
     * @return string
     */
    public function getReturnCode();

    /**
     * Run should contain the running logic (check for errors etc) and should simply return true
     * in order to be executed properly. If the method returns false, the command is considered to be have failed.
     *
     * @param array $arguments
     *
     * @return mixed
     */
    public function run(array $arguments);

    /**
     *
     * Method to run a check that executes after the command has returned a value
     *
     * @param string $returnCode
     *
     * @return mixed
     */
    public function check($returnCode);

    /**
     * @param array $arguments
     *
     * @return mixed
     */
    public function setArguments(array $arguments);
}
