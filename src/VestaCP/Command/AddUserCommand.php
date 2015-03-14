<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace VestaCP\Command;

/**
 * Class AddUserCommand
 *
 * @package VestaCP\Command
 */
class AddUserCommand implements CommandInterface
{

    /**
     * @return string|void
     */
    public function getName()
    {
        return 'Add user';
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