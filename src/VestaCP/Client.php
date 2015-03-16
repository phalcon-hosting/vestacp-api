<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace VestaCP;

use VestaCP\Command\AddUserCommand;
use VestaCP\Command\CommandInterface;
use VestaCP\Command\Map\User;
use VestaCP\HTTP\Client as httpClient;
use VestaCP\Command\Exception as CommandException;

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
     * Command maps below
     */

    /**
     * User command map
     *
     * @var User
     */
    public $user;

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

        // register command maps
        $this->user = new User($this);
    }

    /**
     * @param CommandInterface $command
     * @param array|string     $args
     *
     * @throws CommandException
     * @throws Exception
     * @return mixed
     */
    public function execute(CommandInterface $command, $args)
    {
        $return = false;

        if ($result = $command->run($args)) {

            $general = array(
                'user' => $this->adminUsername,
                'password'=> $this->adminPassword,
                'returncode' => $command->getReturnCode(),
                'cmd' => $command->getCommand()
            );

            $argumentArray = array();
            ;
            for($i=0; $i < count($args); $i++) {
                $argumentArray['arg'.($i+1)] = $args[$i];
            }

            $postVars = array_merge($general, $argumentArray);
            $commandResult = $this->http->send($postVars);

            if ($commandResult == self::RESULT_E_PASSWORD) {
                throw new CommandException('Invalid password', self::RESULT_E_PASSWORD);
            } elseif ($commandResult == self::RESULT_E_DISK) {
                throw new CommandException('Not enough free diskspace to perform the request', self::RESULT_E_DISK);
            } elseif ($commandResult == self::RESULT_E_ARGS) {
                throw new CommandException('Incorrect usage; invalid or missing arguments',self::RESULT_E_ARGS);
            } elseif ($commandResult == self::RESULT_E_LIMIT) {
                throw new CommandException('Hosting package limits reached', self::RESULT_E_LIMIT);
            } elseif ($commandResult == self::RESULT_E_EXISTS) {
                throw new CommandException('Given object already exists', self::RESULT_E_EXISTS);
            }

            $result = $command->check($commandResult);

            if ($result == null) {
                throw new Exception('Command should either return true or false in the check() method', ErrorCodes::ERROR_INVALID_COMMAND);
            }

            // return true if the command executed successfully
            $return = ($result && $commandResult == self::RESULT_OK);
        }

        if ($result == null) {
            throw new Exception('Command should either return true or false in the run() method', ErrorCodes::ERROR_INVALID_COMMAND);
        }

        return $return;
    }

}