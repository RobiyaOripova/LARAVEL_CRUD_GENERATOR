<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;

use App\Http\Interfaces\BannerInterface;
use App\Http\Repositories\BannerRepository;
use App\Http\Interfaces\CategoryInterface;
use App\Http\Repositories\CategoryRepository;
use App\Http\Interfaces\CountryInterface;
use App\Http\Repositories\CountryRepository;
use App\Http\Interfaces\FaqInterface;
use App\Http\Repositories\FaqRepository;
use App\Http\Interfaces\MenuInterface;
use App\Http\Repositories\MenuRepository;
use App\Http\Interfaces\MenuItemInterface;
use App\Http\Repositories\MenuItemRepository;
use App\Http\Interfaces\PageInterface;
use App\Http\Repositories\PageRepository;
use App\Http\Interfaces\PostInterface;
use App\Http\Repositories\PostRepository;
use App\Http\Interfaces\SettingsInterface;
use App\Http\Repositories\SettingsRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
      
		$this->app->bind(BannerInterface::class, BannerRepository::class);
		$this->app->bind(CategoryInterface::class, CategoryRepository::class);
		$this->app->bind(CountryInterface::class, CountryRepository::class);
		$this->app->bind(FaqInterface::class, FaqRepository::class);
		$this->app->bind(MenuInterface::class, MenuRepository::class);
		$this->app->bind(MenuItemInterface::class, MenuItemRepository::class);
		$this->app->bind(PageInterface::class, PageRepository::class);
		$this->app->bind(PostInterface::class, PostRepository::class);
		$this->app->bind(SettingsInterface::class, SettingsRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
