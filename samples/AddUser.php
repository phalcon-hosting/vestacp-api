<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */
use VestaCP\Client;

require __DIR__ . '/../vendor/autoload.php';


$host = 'server01.phalconhosting.com';
$port = '8083';
$username = 'admin';
$password = '';

$client = new Client($host, $port, $username, $password);

$result = $client->addUser('johndoe', 'testtest', 'test@phalconhosting.com', 'default', 'John', 'Doe');

if ($result) {
    echo 'Client created successfully.';
} else {
    echo 'Client could not be created.';
}