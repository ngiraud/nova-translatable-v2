<?php

namespace NGiraud\NovaTranslatable;

use Exception;
use Laravel\Nova\Http\Requests\NovaRequest;

class FieldMacros
{
    public function translatable()
    {
        return function ($locales = null) {
            $originalComponent = $this->component;

            $this->resolveUsing(function ($value, $resource, $attribute) use ($locales, $originalComponent) {
                // We check if the model has the Spatie's translatable trait
                throw_if(
                    !method_exists($resource, 'guardAgainstNonTranslatableAttribute'),
                    new Exception("You must attach the \Spatie\Translatable\HasTranslations trait to model " . get_class($resource) . " in order to use the translatable functionality.")
                );

                $localizedValuesAreObject = ['key-value-field'];

                $this->component = 'translatable-field';

                $this->withMeta([
                    'translatable' => [
                        'original_attribute' => $this->attribute,
                        'original_component' => $originalComponent,
                        'locales' => resolveLocales($locales),
                        'value' => $resource->getTranslations($attribute),
                        'localized_value_is_an_object' => in_array($originalComponent, $localizedValuesAreObject)
                    ],
                ]);

                // If it's a CREATE or UPDATE request, we want to apply rules to each locale
                if (in_array(request()->method(), ['PUT', 'POST'])) {
                    $this->attribute = "{$this->attribute}.*";
                }

                return $this->resolveAttribute($resource, $attribute);
            });

            $this->fillUsing(function (NovaRequest $request, $model, $attribute, $requestAttribute) {
                // Because we add a ".*" to the attribute in the resolveUsing method,
                // we need to remove to check if the attribute exists in the request
                $requestAttribute = str_replace('.*', '', $requestAttribute);

                if ($request->exists($requestAttribute)) {
                    throw_if(
                        !is_array($request[$requestAttribute]),
                        new Exception("Translations must be filled as array.")
                    );

                    $data = collect($request[$requestAttribute])->map(function ($value) {
                        // We check if the data is or must be a json string
                        if (
                            is_string($value) && is_array(json_decode($value, true)) && (json_last_error() == JSON_ERROR_NONE) ||
                            $this->meta['translatable']['localized_value_is_an_object'] === true
                        ) {
                            $value = json_decode($value, true);
                        }

                        return empty($value) ? '' : $value;
                    })->filter()->toArray();

                    // We set the new values
                    $model->{$requestAttribute} = $data;
                }
            });

            return $this;
        };
    }

    public function rulesFor()
    {
        return function ($locale, $rules) {
            throw_if(
                !in_array($locale, array_keys(resolveLocales())),
                new Exception("Invalid locale specified ({$locale})")
            );

            $this->rules['translatable'][$locale] = $rules;

            return $this;
        };
    }
}
