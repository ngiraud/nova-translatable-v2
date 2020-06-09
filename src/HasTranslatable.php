<?php

namespace NGiraud\NovaTranslatable;

use Illuminate\Support\MessageBag;
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

        foreach ($rules as $attribute => $attributeRules) {
            // We do not have single rules for translatable
            if (empty($attributeRules['translatable'])) {
                continue;
            }

            foreach ($attributeRules['translatable'] as $locale => $localeRules) {
                // We copy the locale rule into the rules array
                // i.e. ['name.fr' => ['required']]
                $rules[str_replace('.*', '', $attribute) . ".{$locale}"] = $localeRules;

                // We unset the translatable locale entry since we copy the rule into the rules array
                unset($rules[$attribute]['translatable'][$locale]);
            }

            // We unset the translatable entry once we lopped on each locale
            if (isset($rules[$attribute]['translatable'])) {
                unset($rules[$attribute]['translatable']);
            }

            // We unset the attribute entry if there is no other rule
            if (empty($rules[$attribute])) {
                unset($rules[$attribute]);
            }
        }

        return parent::formatRules($request, $rules);
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

        foreach ($validator->errors()->getMessages() as $attribute => $errors) {
            [$attr, $locale] = explode('.', $attribute);

            $translated = trans('validation.attributes.'.$attr);

            if(Str::startsWith($translated, 'validation.attributes.')) {
                $translated = $attr;
            }

            $translated .= " ({$locales[$locale]})";

            foreach ($errors as $error) {
                $validator->errors()->add(
                    str_replace('.', '->', $attribute),
                    str_replace($attribute, $translated, $error)
                );
            }
        }
    }
}
