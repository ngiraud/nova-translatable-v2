<?php

use Illuminate\Support\Arr;

if (!function_exists('resolveLocales')) {
    function resolveLocales($locales = null)
    {
        // If we dont have locales passed in parameters, so we use the ones from the config
        if (empty($locales)) {
            $locales = config('nova-translatable.locales', ['en' => 'English']);
        }

        if (is_callable($locales)) {
            $locales = call_user_func($locales);
        }

        if (isValidLocaleArray($locales)) {
            return $locales;
        }

        throw new Exception("Invalid locales were given to the field.");
    }
}

if (!function_exists('isValidLocaleArray')) {
    function isValidLocaleArray($localeArray)
    {
        return !empty($localeArray) && is_array($localeArray) && Arr::isAssoc($localeArray);
    }
}
