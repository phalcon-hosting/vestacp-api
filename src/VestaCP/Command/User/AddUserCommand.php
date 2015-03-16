<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace VestaCP\Command\User;

use VestaCP\Command\AbstractCommand;
use VestaCP\Command\Exception;
use VestaCP\ErrorCodes;

/**
 * Class AddUserCommand
 *
 * @package VestaCP\Command
 */
class AddUserCommand extends AbstractCommand
{

    /**
     * @param array $arguments
     *
     * @return mixed|void
     * @throws Exception
     */
    public function run(array $arguments)
    {
        if (!filter_var($arguments[2], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email given', ErrorCodes::ERROR_ADD_USER);
        }

        return true;
    }

    /**
     * @param string $returnCode
     *
     * @return bool
     */
    public function check($returnCode)
    {
        return true;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return 'v-add-user';
    }

    /**
     * @return string
     */
    public function getReturnCode()
    {
        return 'yes';
    }
}