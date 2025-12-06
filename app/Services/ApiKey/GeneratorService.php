<?php

namespace App\Services\ApiKey;

use Illuminate\Support\Str;

abstract class GeneratorService
{
    /**
     * generate
     *
     * @param  mixed  $envName
     * @return mixed
     */
    public function generate($envName)
    {
        // short id used for lookups
        $prefix = Str::random(8);

        // prefix part for token
        $env = config('settings.api_key.prefix.invoicekit')
            . '_'
            . $envName;

        // random part for token
        $random = bin2hex(random_bytes(24));

        // plain key
        $plain = "{$env}_{$prefix}_{$random}";

        // hash key
        $hash = hash('sha256', $plain);

        // return plain and hash values
        return [
            'plain' => $plain,
            'key_hash' => $hash,
            'key_prefix' => $prefix,
        ];
    }
}
