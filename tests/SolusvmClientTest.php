<?php namespace kofj\tests;

use Dotenv;

class SolusvmClientTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var
     */
    private $client;

    /**
     * Init TestCase
     *
     */
    public function setup()
    {
        parent::setup();

        // load dotenv
        Dotenv::load(__DIR__);

        // get instance
        $key = getenv('key');
        $hash = getenv('hash');
        $host = getenv('host');
        $this->client = new SolusvmClient($key, $hash, $host);
    }
}
