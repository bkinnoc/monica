<?php

namespace App\Providers;

use Laravel\Nova\Nova;
use Eminiarts\Tabs\Tab;
use Laravel\Nova\Panel;
use Eminiarts\Tabs\Tabs;
use App\Helpers\AppHelper;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use App\Models\MailcowMailbox;
use App\Nova\Metrics\NewUsers;
use App\Helpers\InstanceHelper;
use App\Nova\Lenses\UserQuotas;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Boolean;
use App\Nova\Metrics\UsersPerDay;
use Laravel\Cashier\Subscription;
use Illuminate\Support\Collection;
use Mako\CustomTableCard\Table\Row;
use Illuminate\Support\Facades\Gate;
use Mako\CustomTableCard\Table\Cell;
use App\Nova\Metrics\UsersPerCharity;
use App\Providers\AppServiceProvider;
use Mako\CustomTableCard\CustomTableCard;
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
        if (config('nova.enable_settings')) {
            \OptimistDigital\NovaSettings\NovaSettings::addSettingsFields([
                Panel::make('Charitable Settings', [
                    Number::make('Default Donation Percentage', 'charitable_percentage'),
                ]),
                Panel::make('Restrictions', [
                    Number::make('Minimum Age (Years)', 'minimum_age'),
                ]),
                Panel::make('Abandoned Cart Settings', [
                    Boolean::make("Disable abandoned cart emails", "abandoned_cart_emails_disabled"),
                    Number::make('# of emails to send', 'abandoned_cart_emails_to_send')->default(nova_get_setting('abandoned_cart_emails_to_send', 1))
                ]),
                Tabs::make(
                    'Abandoned Cart Emails',
                    Collection::times(
                        nova_get_setting('abandoned_cart_emails_to_send', 1),
                        function ($index) {
                            return Tab::make("Email ${index}", [
                                Number::make('Send after (hrs)', "abandoned_cart_${index}_hours")->help($index == 1 ? 'Send the first email after the above  hours' : 'Sent after the last email was sent'),
                                Text::make('Subject', "abandoned_cart_${index}_subject")->help("Use tokens such as to replace text:
                                <br/>{{name}} - The user's full name
                                <br/>{{email}} - The user's email
                                <br/>{{firstName}} - The user's first name
                                <br/>{{lastName}} - The user's last name"),
                                Trix::make("Email ${index}", "abandoned_cart_${index}_email")->help("Use tokens such as to replace text:
                                <br/>{{name}} - The user's full name
                                <br/>{{email}} - The user's email
                                <br/>{{firstName}} - The user's first name
                                <br/>{{lastName}} - The user's last name")
                            ]);
                        }
                    )
                )
            ]);
        }
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
            new NewUsers,
            new UsersPerDay,
            new UsersPerCharity,
            (new CustomTableCard)->header([
                new Cell('User'),
                new Cell('Mailbox'),
                new Cell('Subscription'),
                new Cell('Total Storage'),
                new Cell('Available Storage')
            ])
                ->data(
                    MailcowMailbox::with('user.account')
                        ->select([
                            'quota', 'quota', 'username'
                        ])
                        ->get()
                        ->map(function ($mailbox) {
                            $subscription = optional(optional($mailbox->user)->account)->getSubscribedPlan();
                            $plan = $subscription instanceof Subscription ? InstanceHelper::getPlanInformationFromSubscription($subscription) : [];
                            // dump(optional($mailbox->user)->account->getSubscribedPlan(), $plan);
                            return new Row(
                                new Cell(optional($mailbox->user)->name),
                                new Cell($mailbox->username),
                                new Cell(
                                    implode(
                                        ' @ ',
                                        [
                                            Str::title(Arr::get($plan, 'name') ?: Arr::get($plan, 'type') ?: 'Unnamed Plan'),
                                            Arr::get($plan, 'friendlyPrice') ?: '$0.00',
                                        ]
                                    )
                                ),
                                new Cell(AppHelper::bytesToHumanReadable($mailbox->quota)),
                                new Cell(AppHelper::bytesToHumanReadable($mailbox->quota)),
                            );
                        })->toArray()
                )
                ->title('User Quotas')
                ->viewAll(['label' => 'View All', 'link' => 'resources/mailcow-mailboxes'])
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