<?php

namespace Workbench\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Vinkas\Cda\Server\Client;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Vinkas\Cda\Server\Client>
 */
class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'         => $this->faker->name(),
            'key'          => $this->faker->unique()->uuid(),
            'secret'       => $this->faker->uuid(),
            'redirect_url' => $this->faker->url(),
        ];
    }
}
