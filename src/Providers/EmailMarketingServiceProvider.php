<?php

namespace Juzaweb\Modules\EmailMarketing\Providers;

use Juzaweb\Modules\Core\Facades\Menu;
use Juzaweb\Modules\Core\Providers\ServiceProvider;
use Illuminate\Support\Facades\File;
use Juzaweb\Modules\EmailMarketing\Contracts\AutomationTriggerRegistryInterface;
use Juzaweb\Modules\EmailMarketing\Support\AutomationTriggerRegistry;

class EmailMarketingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerMenus();

        $this->registerAutomationTriggers();

        $this->commands([
            \Juzaweb\Modules\EmailMarketing\Commands\CheckBirthdayAutomationCommand::class,
        ]);

        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Registered::class,
            [\Juzaweb\Modules\EmailMarketing\Listeners\AutomationListener::class, 'handle']
        );
    }

    public function register(): void
    {
        $this->app->singleton(
            AutomationTriggerRegistryInterface::class,
            AutomationTriggerRegistry::class
        );

        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->app->register(RouteServiceProvider::class);
    }

    protected function registerMenus(): void
    {
        Menu::make('email-marketing', function () {
            return [
                'title' => __('email-marketing::translation.email_marketing'),
                'icon' => 'fas fa-envelope-open-text',
                'position' => 50,
            ];
        });

        Menu::make('email-marketing.campaigns', function () {
            return [
                'title' => __('email-marketing::translation.campaigns'),
                'parent' => 'email-marketing',
                'icon' => 'fas fa-bullhorn',
                'permissions' => 'email-marketing.campaigns.index',
            ];
        });

        Menu::make('email-marketing.subscribers', function () {
            return [
                'title' => __('email-marketing::translation.subscribers'),
                'parent' => 'email-marketing',
                'icon' => 'fas fa-users',
                'permissions' => 'email-marketing.subscribers.index',
            ];
        });

        Menu::make('email-marketing.email-templates', function () {
            return [
                'title' => __('email-marketing::translation.email_templates'),
                'parent' => 'email-marketing',
                'icon' => 'fas fa-file-alt',
                'permissions' => 'email-marketing.email-templates.index',
            ];
        });

        Menu::make('email-marketing.segments', function () {
            return [
                'title' => __('email-marketing::translation.segments'),
                'parent' => 'email-marketing',
                'icon' => 'fas fa-layer-group',
                'permissions' => 'email-marketing.segments.index',
            ];
        });

        Menu::make('email-marketing.automation', function () {
            return [
                'title' => __('email-marketing::translation.automation.title'),
                'parent' => 'email-marketing',
                'icon' => 'fas fa-robot',
                'permissions' => 'email-marketing.automation.index',
            ];
        });
    }

    protected function registerAutomationTriggers(): void
    {
        $registry = app(AutomationTriggerRegistryInterface::class);

        $registry->register('user_registered', [
            'label' => __('email-marketing::translation.automation.trigger.user_registered'),
            'description' => __('email-marketing::translation.automation.trigger.user_registered_desc'),
            'delay_support' => true,
        ]);

        $registry->register('user_birthday', [
            'label' => __('email-marketing::translation.automation.trigger.user_birthday'),
            'description' => __('email-marketing::translation.automation.trigger.user_birthday_desc'),
            'delay_support' => true,
        ]);

        $registry->register('member_registered', [
            'label' => __('email-marketing::translation.automation.trigger.member_registered'),
            'description' => __('email-marketing::translation.automation.trigger.member_registered_desc'),
            'delay_support' => true,
        ]);

        $registry->register('member_birthday', [
            'label' => __('email-marketing::translation.automation.trigger.member_birthday'),
            'description' => __('email-marketing::translation.automation.trigger.member_birthday_desc'),
            'delay_support' => true,
        ]);
    }

    protected function registerConfig(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/email-marketing.php' => config_path('email-marketing.php'),
        ], 'email-marketing-config');
        $this->mergeConfigFrom(__DIR__ . '/../../config/email-marketing.php', 'email-marketing');
    }

    protected function registerTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'email-marketing');
        $this->loadJsonTranslationsFrom(__DIR__ . '/../resources/lang');
    }

    protected function registerViews(): void
    {
        $viewPath = resource_path('views/modules/email-marketing');

        $sourcePath = __DIR__ . '/../resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', 'email-marketing-module-views']);

        $this->loadViewsFrom($sourcePath, 'email-marketing');
    }
}
