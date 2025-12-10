<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\Admin\Pages\Tenancy\CreateBranch;
use App\Filament\Admin\Pages\Tenancy\EditBranchProfile;
use App\Http\Middleware\SetLocale;
use App\Models\Branch;
use App\Settings\GeneralSettings;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Throwable;

final class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->brandName(function () {
                try {
                    return resolve(GeneralSettings::class)->brandName;
                } catch (Throwable) {
                    return config('app.name');
                }
            })
            ->brandLogo(function (): ?string {
                try {
                    $logo = resolve(GeneralSettings::class)->brandLogo;

                    if (! $logo) {
                        return null;
                    }

                    return Storage::url($logo);
                } catch (Throwable) {
                    return null;
                }
            })
            ->brandLogoHeight('3.5rem')
            ->tenant(Branch::class)
            ->tenantRegistration(CreateBranch::class)
            ->tenantProfile(EditBranchProfile::class)
            ->tenantRoutePrefix('branch')
            ->emailVerification()
            ->spa()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->font('Poppins')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\Filament\Admin\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\Filament\Admin\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\Filament\Admin\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
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
                SetLocale::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->tenantMenuItems([
                'profile' => fn (Action $action): Action => $action->icon(Heroicon::PencilSquare),
            ])
            ->userMenuItems([
                'profile' => fn (Action $action): Action => $action->label(__('My Profile'))
                    ->url(fn (): string => route('filament.user.auth.profile')),
                'admin' => Action::make('admin')
                    ->label(__('Admin Panel'))
                    ->url(fn (): string => url('/admin'))
                    ->icon('heroicon-m-shield-check')
                    ->visible(function () {
                        /** @var Panel $panel */
                        $panel = filament()->getPanel('admin');

                        return auth()->user()?->canAccessPanel($panel) ?? false;
                    }),
            ]);
    }
}
