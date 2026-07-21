<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\WorkflowSurat;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {

            $suratMasukCount = 0;

            if (Auth::check() && Auth::user()->role !== 'cs') {

                $userRole = Auth::user()->role;

                $query = WorkflowSurat::whereHas('surat', function ($q) {
                    $q->where('status', 'proses');
                });

                if ($userRole === 'admin_pusat') {
                    $query->whereIn('current_role', [
                        'admin_pusat',
                        'admin_pusat_final'
                    ]);
                } else {
                    $query->where('current_role', $userRole);
                }

                $suratMasukCount = $query->count();
            }

            $view->with('suratMasukCount', $suratMasukCount);
        });
    }
}
