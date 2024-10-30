<?php

namespace KirschbaumDevelopment\NovaComments\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use KirschbaumDevelopment\NovaComments\Models\Comment as CommentModel;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;
use Outl1ne\NovaTrumbowygField\Trumbowyg;

class Comment extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = CommentModel::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'comment',
    ];

    /**
     *
     * @return string
     */
    public static function label()
    {
        return __('Comment');
    }

    /**
     * Determine if this resource is available for navigation.
     */
    public static function availableForNavigation(Request $request): bool
    {
        return config('nova-comments.available-for-navigation');
    }

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Trumbowyg::make(__('comment'), 'comment')
                ->alwaysShow()
                ->hideFromIndex(),

            MorphTo::make(__('Commentable'))->onlyOnIndex(),

            Text::make(__('comment'), 'comment')
                ->displayUsing(function ($comment) {
                    return Str::limit(strip_tags($comment), config('nova-comments.limit'));
                })
                ->onlyOnIndex(),

            BelongsTo::make(__('Commenter'), 'commenter', config('nova-comments.commenter.nova-resource'))
                ->exceptOnForms(),

            DateTime::make(__('Created'), 'created_at')
                ->exceptOnForms()
                ->sortable(),
        ];
    }

    /**
     * Get the cards available for the request.
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
