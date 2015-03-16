<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace VestaCP\Command\Map;

use VestaCP\Command\User\AddUserCommand;
use VestaCP\Command\User\DeleteUserCommand;

class User extends AbstractMap
{

    /**
     * @param string $userName
     * @param string $password
     * @param string $email
     * @param string $package
     * @param string $firstName
     * @param string $lastName
     *
     * @return mixed
     * @throws \VestaCP\Exception
     */
    public function add($userName, $password, $email, $package = 'default', $firstName = '', $lastName = '')
    {
        $this->command = new AddUserCommand();
        return $this->executeCommand(func_get_args());
    }

    public function delete($userName)
    {
        $this->command = new DeleteUserCommand();
        return $this->executeCommand(func_get_args());
    }

}