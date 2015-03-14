<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace VestaCP;

use VestaCP\Command\AddUserCommand;
use VestaCP\Command\CommandInterface;
use VestaCP\HTTP\Client as httpClient;

class Client
{

    const RESULT_OK = '0';
    const RESULT_E_ARGS = '1';
    const RESULT_E_INVALID = '2';
    const RESULT_E_NOTEXIST = '3';
    const RESULT_E_EXISTS = '4';
    const RESULT_E_SUSPENDED = '5';
    const RESULT_E_UNSUSPENDED = '6';
    const RESULT_E_INUSE = '7';
    const RESULT_E_LIMIT = '8';
    const RESULT_E_PASSWORD = '9';
    const RESULT_E_FORBIDDEN = '10';
    const RESULT_E_DISABLED = '11';
    const RESULT_E_PARSING = '12';
    const RESULT_E_DISK = '13';
    const RESULT_E_LA = '14';
    const RESULT_E_CONNECT = '15';
    const RESULT_E_FTP = '16';
    const RESULT_E_DB = '17';
    const RESULT_E_RRD = '18';
    const RESULT_E_UPDATE = '19';
    const RESULT_E_RESTART = '20';

    /**
     * @var
     */
    protected $hostIp;

    /**
     * @var int
     */
    protected $hostPort;

    /**
     * @var string
     */
    protected $adminUsername;

    /**
     * @var string
     */
    protected $adminPassword;

    /**
     * @var httpClient
     */
    protected $http;

    /**
     * Create the client object
     *
     * @param string $hostIp
     * @param int    $hostPort
     * @param string $adminUsername
     * @param string $adminPassword
     *
     * @throws Exception
     */
    public function __construct($hostIp, $hostPort, $adminUsername, $adminPassword)
    {
        if (!extension_loaded('curl')) {
            throw new Exception('Curl module not found', ErrorCodes::ERROR_CURL_NOT_FOUND);
        }

        $hostPort = (int) $hostPort;

        $this->http = new httpClient(sprintf('https://%s:%d/api/', $hostIp, $hostPort));

        $this->adminUsername = $adminUsername;
        $this->adminPassword = $adminPassword;
    }

    /**
     * @param CommandInterface $command
     * @param array|string     $args
     *
     * @return mixed
     * @throws Exception
     */
    public function execute(CommandInterface $command, $args)
    {
        $general = array(
            'user' => $this->adminUsername,
            'password'=> $this->adminPassword,
            'returncode' => $command->getReturnCode(),
            'cmd' => $command->getCommand()
        );

        $argumentArray = array();

        for($i=0; $i < count($args); $i++) {
            $argumentArray['arg'.($i+1)] = $args[$i];
        }

        $postVars = array_merge($general, $argumentArray);
        $result = $this->http->send($postVars);

        if ($result == self::RESULT_E_PASSWORD) {
            throw new Exception('Invalid password', ErrorCodes::ERROR_CANNOT_CONNECT_TO_HOST);
        } elseif ($result == self::RESULT_E_DISK) {
            throw new Exception('Not enough free diskspace to perform the request', ErrorCodes::ERROR_HOST);
        }elseif ($result == self::RESULT_E_LIMIT) {
            throw new Exception('Hosting package limits reached', ErrorCodes::ERROR_LIMITS_REACHED);
        }

        return $result;

    }

    /**
     * @param string $userName
     * @param string $password
     * @param string $email
     * @param string $package
     * @param string $firstName
     * @param string $lastName
     *
     * @throws Exception
     * @return mixed
     */
    public function addUser($userName, $password, $email, $package = 'default', $firstName = '', $lastName = '')
    {

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email given', ErrorCodes::ERROR_ADD_USER);
        }

        $command = new AddUserCommand();

        $returnCode = $this->execute($command, array(
            $userName,
            $password,
            $email,
            $package,
            $firstName,
            $lastName
        ));

        if ($returnCode == self::RESULT_E_EXISTS) {
            throw new Exception(sprintf('User %s already exists', $userName), ErrorCodes::ERROR_ADD_USER);
        }

        return $returnCode == self::RESULT_OK;
    }

}