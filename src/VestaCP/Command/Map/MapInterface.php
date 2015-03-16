<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace VestaCP\Command\Map;

use VestaCP\Client;

interface MapInterface
{
    public function __construct(Client $client);
}
