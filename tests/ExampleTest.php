<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testErrors_no_params()
    {
        $data = $this->json('GET','/v1/bpi')->seeJson(
            [
                "from" => ["The from field is required."],
                "to" => ["The to field is required."],
            ]
        );
    }

    public function testErrors_params_to()
    {
        $this->json('GET','/v1/bpi?from=2019-01-01')->seeJson(
            [
                "to" => ["The to field is required."],
            ]
        );
    }

    public function testErrors_params_from()
    {
        $this->json('GET','/v1/bpi?to=2019-01-01')->seeJson(
            [
                "from" => ["The from field is required."],
            ]
        );
    }

    public function testErrors_params_date()
    {
        $this->json('GET','/v1/bpi?to=2019-01-01&from=2019-01-07')->seeJson(
            [
                "to" => ["The to must be a date after from."],
            ]
        );

        $this->json('GET','/v1/bpi?from=2019-01-01&to=3000-01-07')->seeJson(
            [
                "to" => ["The to must be a date before or equal to now."],
            ]
        );
    }

    public function testCorrect_response()
    {
        $this->json('GET','/v1/bpi?from=2019-01-01&to=2019-01-07')->seeJson(
            [
                "2020-12-31" => 28956.265,
            ]
        );
    }
}
