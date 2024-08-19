<?php

namespace App\Actions;

class GenerateProductVariant
{
    /**
     * Generate product variation based on inputs
     * @param  array  $input
     * @return array
     */
    public static function handle(array $input): array
    {
        if (! count($input)) return [];

        $result = [[]];

        foreach ($input as $key => $values) {
            $append = [];
            foreach ($values as $value) {
                foreach ($result as $data) {
                    $append[] = $data + [$key => $value];
                }
            }
            $result = $append;
        }

        return $result;
    }
}
