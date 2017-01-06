<?php
/**
 * Created by PhpStorm.
 * User: Andrianina OELI
 * Date: 15/11/2016
 * Time: 15:11
 */

namespace App\Models;


class BaseModel
{
    private $faker;

    function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    public function getRecords($side)
    {
        $data = [];
        while ($side) {
            $data[] = [
                'name' => $this->faker->name,
                'adress' => $this->faker->address,
                'description' => $this->faker->sentences(4, true)
            ];
            $side--;
        }
        return $data;
    }
}