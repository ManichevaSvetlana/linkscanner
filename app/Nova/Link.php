<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Link extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Link';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        return $this->checked_url . '  ->  ' . $this->presence_url;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'checked_url', 'presence_url', 'checked_url_status_code', 'presence_url_status_code',
        'presence_url_added', 'presence_url_removed', 'last_check', 'email', 'details', 'active'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('User')->sortable(),

            Text::make('Checked Url')->sortable(),
            Text::make('Presence Url')->sortable(),
            Text::make('Checked Url Status Code')->rules('nullable')->sortable(),
            Text::make('Presence Url Status Code')->rules('nullable')->sortable(),

            DateTime::make('Presence Url Added')->rules('nullable')->onlyOnIndex()->sortable(),
            DateTime::make('Presence Url Added')->rules('nullable')->onlyOnDetail(),
            DateTime::make('Presence Url Removed')->rules('nullable')->sortable(),
            DateTime::make('Checked', 'last_check')->rules('nullable')->sortable(),

            Text::make('Email')->rules('nullable', 'email')->sortable(),
            Textarea::make('Details')->rules('nullable')->sortable(),

            Boolean::make('Active')->sortable(),

            Text::make('Check', function () {
                return '<a href="/check/' . $this->id . '" class="btn btn-default btn-primary btn-sm" style="background: #b6bcc2; font-size: 12px">Run</a>';
            })->asHtml(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
