<?php

namespace NGiraud\NovaTranslatable;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Laravel\Nova\Http\Requests\NovaRequest;

trait HasTranslatable
{
    /**
     * Override the default formatRules methods to add the translatable rulesFor functionality.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param array $rules
     *
     * @return array
     */
    protected static function formatRules(NovaRequest $request, array $rules)
    {
        $rules = parent::formatRules($request, $rules);
        $locales = resolveLocales();

        $newRules = [];

        foreach ($rules as $attribute => $attributeRules) {
            // Field is not a translatable field
            if (!isset($attributeRules['translatable'])) {
                $newRules[$attribute] = $attributeRules;
                continue;
            }

            $attributeName = $attribute;

            if(empty($attributeRules['translatable'])) {
                foreach (array_keys($locales) as $locale) {
                    $newRules[str_replace('.*', '', $attributeName) . ".{$locale}"] = collect($attributeRules)->except('translatable')->all();
                }
            } else {
                foreach ($attributeRules['translatable'] as $locale => $localeRules) {
                    // We copy the locale rule into the rules array
                    // i.e. ['name.fr' => ['required']]
                    $newRules[str_replace('.*', '', $attributeName) . ".{$locale}"] = collect($attributeRules)->except('translatable')->merge($localeRules)->all();
                }
            }

            // We unset the translatable entry
            if (isset($newRules[$attributeName]['translatable'])) {
                unset($newRules[$attributeName]['translatable']);
            }
        }

        return parent::formatRules($request, $newRules);
    }

    /**
     * Handle any post-validation processing.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    protected static function afterValidation(NovaRequest $request, $validator)
    {
        $locales = resolveLocales();

        dd($validator->errors()->messages());
        foreach ($validator->errors()->messages() as $attribute => $errors) {
            // We ensure that we only treat translatable fields
            if(!Str::startsWith($attribute, 'translatable-field.')) {
                continue;
            }

            [$translatableLabel, $attr, $locale] = explode('.', $attribute);

            if(!in_array($locale, array_keys($locales))) {
                continue;
            }

            $translated = Lang::has('validation.attributes.'.$attr) ? trans('validation.attributes.'.$attr) : $attr;
            $translated .= " ({$locales[$locale]})";

            foreach ($errors as $error) {
                $validator->errors()->add(
                    str_replace($translatableLabel.'.', '', $attribute),
                    str_replace($attribute, $translated, $error)
                );
            }
        }
    }
}
