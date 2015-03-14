<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace VestaCP\Command;

/**
 * Interface MethodInterface
 *
 * @package VestaCP\Methods
 */
interface CommandInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getCommand();

    /**
     * @return string
     */
    public function getReturnCode();
}
