<?php

namespace App\Providers;

use Laravel\Nova\Nova;
use Laravel\Nova\Panel;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Illuminate\Support\Facades\Gate;
use App\Providers\AppServiceProvider;
use GeneaLabs\NovaTelescope\NovaTelescope;
use Bolechen\NovaActivitylog\NovaActivitylog;
use OptimistDigital\NovaSettings\NovaSettings;
use Laravel\Nova\NovaApplicationServiceProvider;
use Vyuldashev\NovaPermission\NovaPermissionTool;
use SimonHamp\LaravelNovaCsvImport\LaravelNovaCsvImport;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->registerSettings();
    }

    protected function registerSettings()
    {
        // Using an array
        \OptimistDigital\NovaSettings\NovaSettings::addSettingsFields([
            Panel::make('Charitable Settings', [
                Number::make('Default Donation Percentage', 'charitable_percentage'),
            ]),
            Panel::make('Restrictions', [
                Number::make('Minimum Age (Years)', 'minimum_age'),
            ]),
        ]);
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define(
            'viewNova',
            function ($user) {
                return AppServiceProvider::isUserAdmin($user);
            }
        );
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            new Help,
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        $user = auth()->user();
        return array_filter([
            \Vyuldashev\NovaPermission\NovaPermissionTool::make(),
            new LaravelNovaCsvImport,
            new NovaSettings,
            AppServiceProvider::isUserAdmin($user) ? NovaActivitylog::make() : null,
            AppServiceProvider::isUserDeveloper($user) ? NovaTelescope::make() : null,
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}