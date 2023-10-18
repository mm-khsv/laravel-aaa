<?php

namespace dnj\AAA\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class AbilityRule implements InvokableRule
{
	public function __invoke($attribute, $value, $fail)
	{
		if (!is_string($value) or empty($value)) {
			$fail('validation.required')->translate();

			return;
		}
		$atPos = strpos($value, "@");
		if (
			$atPos === false or
			(
				!interface_exists(substr($value, 0, $atPos)) and
				!class_exists(substr($value, 0, $atPos))
			)
		) {
			$fail('validation.ability')->translate();

			return;
		}
	}
}
