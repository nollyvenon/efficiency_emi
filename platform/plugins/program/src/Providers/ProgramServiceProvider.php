<?php
// platform/plugins/program/src/Providers/ProgramServiceProvider.php

namespace Botble\Program\Providers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Supports\DashboardMenuItem;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Botble\Program\Models\Program;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\SeoHelper\SeoOpenGraph;
use Botble\Slug\Facades\SlugHelper;
use Botble\Slug\Models\Slug;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Botble\Base\Supports\ServiceProvider;


class ProgramServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot()
    {
        $this->setNamespace('plugins/program')
            ->loadRoutes('/web')
            ->loadMigrations()
            ->loadAndPublishViews()
            ->publishAssets();

        $this
            ->setNamespace('plugins/program')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes();

        SlugHelper::registering(function (): void {
            SlugHelper::registerModule(Program::class, fn () => trans('plugins/program::program.programs'));
            SlugHelper::setPrefix(Program::class, 'programs', true);
        });

        DashboardMenu::default()->beforeRetrieving(function() {
            DashboardMenu::make()
                ->registerItem([
                    'id'          => 'cms-plugins-program',
                    'priority'    => 5,
                    'name'        => 'Programs',
                    'icon'        => 'fas fa-calendar-alt',
                    'route'       => 'program.index',
                    'permissions' => ['program.index'],
                ]);
        });

        DashboardMenu::default()->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-program')
                        ->priority(5)
                        ->name('plugins/program::program.menu_name')
                        ->icon('ti ti-calendar')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-programs')
                        ->priority(10)
                        ->parentId('cms-plugins-program')
                        ->name('plugins/program::program.menu_name')
                        ->icon('ti ti-file-text')
                        ->route('program.index')
                )
                /*->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-activities')
                        ->priority(20)
                        ->parentId('cms-plugins-program')
                        ->name('plugins/program::activity.menu_name')
                        ->icon('ti ti-folder')
                        ->route('activity.index')
                )*/
                ;
        });

        $this->app->booted(function (): void {
            add_filter(BASE_FILTER_PUBLIC_SINGLE_DATA, function (Slug|array $slug): Slug|array {
                if (! $slug instanceof Slug || $slug->reference_type != Team::class) {
                    return $slug;
                }

                $condition = [
                    'id' => $slug->reference_id,
                    'status' => BaseStatusEnum::PUBLISHED,
                ];

                if (Auth::guard()->check() && request()->input('preview')) {
                    Arr::forget($condition, 'status');
                }

                $program = Program::query()
                    ->where($condition)
                    ->with(['slugable'])
                    ->firstOrFail();

                SeoHelper::setTitle($program->name)
                    ->setDescription($program->description);

                SeoHelper::setSeoOpenGraph(
                    (new SeoOpenGraph())
                        ->setDescription($program->description)
                        ->setUrl($program->url)
                        ->setTitle($program->name)
                        ->setType('article')
                );

                Theme::breadcrumb()->add($program->name);

                return [
                    'view' => 'programs.program',
                    'default_view' => 'plugins/program::themes.program',
                    'data' => compact('program'),
                    'slug' => $program->slug,
                ];
            }, 2);
        });
    }
}
