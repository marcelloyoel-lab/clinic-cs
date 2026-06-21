<?php

namespace App\Providers;

use App\Services\MenuService;
use Illuminate\Support\Facades\View;
use Illuminate\Routing\Route;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  // public function boot(): void
  // {
  //   $verticalMenuJson = file_get_contents(base_path('resources/menu/clinicMenu.json'));
  //   $verticalMenuData = json_decode($verticalMenuJson);

  //   // Share all menuData to all the views
  //   $this->app->make('view')->share('menuData', [$verticalMenuData]);
  // }

  public function boot(): void
  {
      View::composer('*', function ($view) {
          $verticalMenuJson = file_get_contents(
              base_path('resources/menu/clinicMenu.json')
          );

          $verticalMenuData = json_decode($verticalMenuJson);

          $verticalMenuData->menu = app(MenuService::class)
              ->filter(
                  $verticalMenuData->menu,
                  auth()->user()
              );

          $view->with('menuData', [$verticalMenuData]);
      });
  }
}
