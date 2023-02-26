<?php

namespace dnj\AAA\Database\Factories;

use dnj\AAA\Models\Type;
use dnj\AAA\Models\TypeLocalizedDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TypeLocalizedDetails>
 */
class TypeLocalizedDetailsFactory extends Factory
{
    protected $model = TypeLocalizedDetails::class;

    public function definition()
    {
        $locale = fake()->locale();

        return [
            'type_id' => Type::factory(),
            'lang' => substr($locale, 0, 2),
            'title' => fake($locale)->words(2, true),
        ];
    }

    public function withLang(string $lang): static
    {
        return $this->state(fn () => [
            'lang' => $lang,
        ]);
    }

    public function withType(int|Type $type): static
    {
        return $this->state(fn () => [
            'type_id' => $type,
        ]);
    }

    public function withTitle(array $title): static
    {
        return $this->state(fn () => [
            'title' => $title,
        ]);
    }
}
