<?php

namespace NGiraud\NovaTranslatable\Tests\Fixtures;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Resource;
use NGiraud\NovaTranslatable\HasTranslatable;

class ProductResource extends Resource
{
    use HasTranslatable;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \NGiraud\NovaTranslatable\Tests\Fixtures\Product::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make(__('Name'), 'name')
                ->sortable()
//                ->rules(['max:255'])
                ->translatable()
                ->rulesFor('fr', [
                    'required'
                ]),

//            KeyValue::make(__('Characteristics'), 'characteristics')
//                    ->keyLabel(__('Label'))
//                    ->valueLabel(__('Value'))
//                    ->actionText(__('Add characteristic'))
//                    ->rules(['required'])
//                    ->rulesFor('fr', [
//                        'required',
//                        function ($attribute, $characteristics, $fail) {
//                            if (!empty($characteristics)) {
//                                foreach (json_decode($characteristics, true) as $label => $value) {
//                                    if (empty($value)) {
//                                        return $fail(trans('validation.required', ['attribute' => $label]));
//                                        break;
//                                    }
//                                }
//                            }
//                        }
//                    ])
//                    ->translatable(),
        ];
    }

    /**
     * Get the URI key for the resource.
     *
     * @return string
     */
    public static function uriKey()
    {
        return 'products';
    }
}
