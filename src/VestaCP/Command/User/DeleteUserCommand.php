<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace VestaCP\Command\User;

use VestaCP\Client;
use VestaCP\Command\AbstractCommand;
use VestaCP\Command\CommandInterface;
use VestaCP\Command\Exception;
use VestaCP\ErrorCodes;

/**
 * Class DeleteUserCommand
 *
 * @package VestaCP\Command
 */
class DeleteUserCommand extends AbstractCommand
{

    /**
     * @param array $arguments
     *
     * @return mixed|void
     * @throws Exception
     */
    public function run(array $arguments)
    {
        return true;
    }

    /**
     * @param $returnCode
     *
     * @return bool|mixed
     * @throws Exception
     */
    public function check($returnCode)
    {
        if ($returnCode == Client::RESULT_E_INUSE) {
            throw new Exception(sprintf('User \'%s\' is in use and cannot be deleted', $this->arguments[0]), Client::RESULT_E_INUSE);
        } elseif($returnCode == Client::RESULT_E_NOTEXIST) {
            throw new Exception(sprintf('User \'%s\' does not exist', $this->arguments[0]), Client::RESULT_E_NOTEXIST);
        }

        return true;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return 'v-delete-user';
    }

    /**
     * @return string
     */
    public function getReturnCode()
    {
        return 'yes';
    }
}