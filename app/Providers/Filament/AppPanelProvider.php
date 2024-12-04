<?php

namespace App\Providers\Filament;

use App\Enums\RoleEnum;
use App\Filament\app\Pages\RegistrationPage;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use EightyNine\Reports\ReportsPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use Swis\Filament\Backgrounds\ImageProviders\MyImages;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('app')
            ->path('app')
            ->registration(RegistrationPage::class)
            ->login()
            ->colors([
                'primary' => Color::hex('#5e4d5c'),
                'gray' => Color::Gray
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Jogo'),
                NavigationGroup::make()
                    ->label('Administrativo')
                    ->collapsed(false),
                NavigationGroup::make()
                    ->label('Relatórios')
                    ->collapsed(false),

            ])
            ->favicon(asset(asset('image/logo_light.svg')))
            ->brandLogoHeight(fn () => auth()->check() ? '50px' : '120px')
            ->brandLogo(asset('image/logo_light_horizonal.svg'))
            ->darkModeBrandLogo(asset('image/logo_dark_horizonal.svg'))
            ->discoverResources(in: app_path('Filament/app/Resources'), for: 'App\\Filament\\app\\Resources')
            ->discoverPages(in: app_path('Filament/app/Pages'), for: 'App\\Filament\\app\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/app/Widgets'), for: 'App\\Filament\\app\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                ReportsPlugin::make(),
                BreezyCore::make()
                    ->myProfile()
                    ->passwordUpdateRules(
                        rules: [Password::default()->mixedCase()->uncompromised(3)], // you may pass an array of validation rules as well. (default = ['min:8'])
                        requiresCurrentPassword: true, // when false, the user can update their password without entering their current password. (default = true)
                    )
                    ->enableTwoFactorAuthentication(),
                FilamentBackgroundsPlugin::make()
                    ->imageProvider(
                        MyImages::make()
                            ->directory('image/background')),
                FilamentShieldPlugin::make(),
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
