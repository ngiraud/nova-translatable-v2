<?php

namespace NGiraud\NovaTranslatable;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishAssets();

        $this->serveNova();

        Field::mixin(new FieldMacros);
    }

    protected function publishAssets()
    {
        $this->publishes([
            __DIR__ . '/../config/nova-translatable.php' => config_path('nova-translatable.php'),
        ], 'nova-translatable-config');
    }

    protected function serveNova()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('translatable-field', __DIR__ . '/../dist/js/translatable-field.js');
        });
    }
}
