<?php

namespace Botble\Applicant\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Support\ServiceProvider;

class ApplicantServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;
    public function boot()
    {

        $this->setNamespace('plugins/applicant')
            ->loadRoutes('web')
            ->loadMigrations()
            ->loadAndPublishViews()
            ->publishAssets();

        $this
            ->setNamespace('plugins/applicant')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes();

        DashboardMenu::registerItem([
            'id' => 'applicants',
            'priority' => 5,
            'name' => 'Applicants',
            'icon' => 'fas fa-users',
            'route' => 'applicants.index',
        ]);
    }


}
