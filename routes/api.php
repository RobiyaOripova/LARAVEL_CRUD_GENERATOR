<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function (Request $request) {
    return response()->json('You are welcome to api BaseApp');
});

Route::prefix('v1')->group(function () {
    // Route::post('/sign-in', [App\Http\Controllers\Api\v1\UserController::class,'signIn']);

});

/*--------------------------------------------------------------------------------
            ADMIN ROUTES  => START
--------------------------------------------------------------------------------*/
Route::prefix('v1')->group(function () {
    Route::prefix('admin/user')->group(function () {
        Route::post('sign-in', [App\Http\Controllers\Api\v1\UserController::class, 'signIn']);
        Route::post('confirm', [App\Http\Controllers\Api\v1\UserController::class, 'confirmAdmin']);
        Route::middleware(['auth:api', 'scope:'.implode(',', User::ROLE_ADMINS)])->group(function () {
            Route::get('get-me', [App\Http\Controllers\Api\v1\UserController::class, 'details']);
            Route::get('/', [App\Http\Controllers\Api\v1\UserController::class, 'index']);
            Route::post('logout', [App\Http\Controllers\Api\v1\UserController::class, 'logout']);
            Route::post('/', [App\Http\Controllers\Api\v1\UserController::class, 'create']);
            Route::put('{id}', [App\Http\Controllers\Api\v1\UserController::class, 'update'])->where('id', '[0-9]+');
            Route::get('{user}', [App\Http\Controllers\Api\v1\UserController::class, 'show'])->where('user', '[0-9]+');
            Route::put('update-admin', [App\Http\Controllers\Api\v1\UserController::class, 'updateAdmin']);
        });
    });
    Route::prefix('/user')->group(function () {
        Route::post('login', [App\Http\Controllers\Api\v1\UserController::class, 'login']);
        Route::post('confirm', [App\Http\Controllers\Api\v1\UserController::class, 'confirm'])->middleware('throttle:anti_ddos');

        Route::middleware('auth:api')->group(function () {
            Route::put('{id}', [App\Http\Controllers\Api\v1\UserController::class, 'updateUser'])->where('id', '[0-9]+');
            Route::post('logout', [App\Http\Controllers\Api\v1\UserController::class, 'logout']);
        });
    });
});
/*--------------------------------------------------------------------------------
            ADMIN ROUTES  => END
--------------------------------------------------------------------------------*/

/*--------------------------------------------------------------------------------
            File manager Controller  => START
--------------------------------------------------------------------------------*/
Route::prefix('v1')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::middleware('scope:admin')->group(function () {
            Route::prefix('admin/filemanager/folder')->group(function () {
                Route::get('/', '\Modules\Filemanager\Http\Controllers\FilemanagerFolderController@index');
                Route::get('/{id}', '\Modules\Filemanager\Http\Controllers\FilemanagerFolderController@show');
                Route::post('/', '\Modules\Filemanager\Http\Controllers\FilemanagerFolderController@create');
                Route::put('/{id}', '\Modules\Filemanager\Http\Controllers\FilemanagerFolderController@update');
                Route::delete('/{id}', '\Modules\Filemanager\Http\Controllers\FilemanagerFolderController@delete');
            });
            Route::prefix('admin/filemanager')->group(function () {
                Route::get('/', '\Modules\Filemanager\Http\Controllers\FilemanagerController@index');
                Route::get('/{id}', '\Modules\Filemanager\Http\Controllers\FilemanagerController@show');
                Route::put('/{id}', '\Modules\Filemanager\Http\Controllers\FilemanagerController@update');
                Route::delete('/{id}', '\Modules\Filemanager\Http\Controllers\FilemanagerController@delete');
                Route::post('/uploads', '\Modules\Filemanager\Http\Controllers\FilemanagerController@uploads');
            });
        });
    });

    Route::prefix('filemanager')->group(function () {
        Route::delete('/{id}', '\Modules\Filemanager\Http\Controllers\FilemanagerController@delete');
        Route::post('/uploads', '\Modules\Filemanager\Http\Controllers\FilemanagerController@frontUpload');
    });
});
/*--------------------------------------------------------------------------------
            File manager Controller => END
--------------------------------------------------------------------------------*/

/*--------------------------------------------------------------------------------
            Translations manager Controller  => START
--------------------------------------------------------------------------------*/

Route::prefix('v1')->group(function () {

    Route::middleware('auth:api')->group(function () {
        Route::prefix('admin/translations')->group(function () {
            Route::get('/list', '\Modules\Translations\Http\Controllers\TranslationsController@list');
            Route::put('/list', '\Modules\Translations\Http\Controllers\TranslationsController@change');
            Route::delete('/{id}', '\Modules\Translations\Http\Controllers\TranslationsController@destroy');
        });
    });

    Route::prefix('translations')->group(function () {
        Route::get('/', '\Modules\Translations\Http\Controllers\TranslationsController@index');
        //        Route::post('/translation', '\Modules\Translations\Http\Controllers\TranslationsController@createTranslation');
        Route::post('/translation/{language}', '\Modules\Translations\Http\Controllers\TranslationsController@store');
        Route::delete('/{id:d+}', '\Modules\Translations\Http\Controllers\TranslationsController@destroy');
    });

});

/*--------------------------------------------------------------------------------
            Translations manager Controller  => END
--------------------------------------------------------------------------------*/

/*--------------------------------------------------------------------------------
    Menu ROUTES  => START
--------------------------------------------------------------------------------*/
Route::prefix('v1')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::middleware('scope:admin')->group(function () {
            Route::prefix('admin/menu')->group(function () {
                Route::get('/', [App\Http\Controllers\Api\v1\MenuController::class, 'adminIndex']);
                Route::post('/', [App\Http\Controllers\Api\v1\MenuController::class, 'create']);
                Route::put('{menu}', [App\Http\Controllers\Api\v1\MenuController::class, 'update'])->where('{menu}', '[0-9]+');
                Route::get('{menu}', [App\Http\Controllers\Api\v1\MenuController::class, 'show'])->where('{menu}', '[0-9]+');
                Route::delete('{menu}', [App\Http\Controllers\Api\v1\MenuController::class, 'destroy'])->where('{menu}', '[0-9]+');
            });
        });
    });
    Route::prefix('menu')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\v1\MenuController::class, 'index']);
        Route::get('{menu}', [App\Http\Controllers\Api\v1\MenuController::class, 'show'])->where('{menu}', '[0-9]+');
    });
});
/*--------------------------------------------------------------------------------
    Menu ROUTES  => END
--------------------------------------------------------------------------------*/

/*--------------------------------------------------------------------------------
    MenuItem ROUTES  => START
--------------------------------------------------------------------------------*/
Route::prefix('v1')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::middleware('scope:admin')->group(function () {
            Route::prefix('admin/menu-items')->group(function () {
                Route::get('/', [App\Http\Controllers\Api\v1\MenuItemController::class, 'adminIndex']);
                Route::post('/', [App\Http\Controllers\Api\v1\MenuItemController::class, 'create']);
                Route::put('{menuItem}', [App\Http\Controllers\Api\v1\MenuItemController::class, 'update'])->where('{menuItem}', '[0-9]+');
                Route::get('{menuItem}', [App\Http\Controllers\Api\v1\MenuItemController::class, 'show'])->where('{menuItem}', '[0-9]+');
                Route::delete('{menuItem}', [App\Http\Controllers\Api\v1\MenuItemController::class, 'destroy'])->where('{menuItem}', '[0-9]+');
            });
        });
    });
    Route::prefix('menu-items')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\v1\MenuItemController::class, 'index']);
        Route::get('{menuItem}', [App\Http\Controllers\Api\v1\MenuItemController::class, 'show'])->where('{menuItem}', '[0-9]+');
    });
});
/*--------------------------------------------------------------------------------
    MenuItem ROUTES  => END
--------------------------------------------------------------------------------*/

/*--------------------------------------------------------------------------------
    Settings ROUTES  => START
--------------------------------------------------------------------------------*/
Route::prefix('v1')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::middleware('scope:admin')->group(function () {
            Route::prefix('admin/settings')->group(function () {
                Route::get('/', [App\Http\Controllers\Api\v1\SettingsController::class, 'adminIndex']);
                Route::post('/', [App\Http\Controllers\Api\v1\SettingsController::class, 'create']);
                Route::put('{settings}', [App\Http\Controllers\Api\v1\SettingsController::class, 'update'])->where('{settings}', '[0-9]+');
                Route::get('{settings}', [App\Http\Controllers\Api\v1\SettingsController::class, 'show'])->where('{settings}', '[0-9]+');
                Route::delete('{settings}', [App\Http\Controllers\Api\v1\SettingsController::class, 'destroy'])->where('{settings}', '[0-9]+');
            });
        });
    });
    Route::prefix('settings')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\v1\SettingsController::class, 'index']);
        Route::get('{settings}', [App\Http\Controllers\Api\v1\SettingsController::class, 'show'])->where('{settings}', '[0-9]+');
    });
});
/*--------------------------------------------------------------------------------
    Settings ROUTES  => END
--------------------------------------------------------------------------------*/

/*--------------------------------------------------------------------------------
    Country ROUTES  => START
--------------------------------------------------------------------------------*/
Route::prefix('v1')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::middleware('scope:admin')->group(function () {
            Route::prefix('admin/country')->group(function () {
                Route::get('/', [App\Http\Controllers\Api\v1\CountryController::class, 'adminIndex']);
                Route::post('/', [App\Http\Controllers\Api\v1\CountryController::class, 'create']);
                Route::put('{country}', [App\Http\Controllers\Api\v1\CountryController::class, 'update'])->where('{country}', '[0-9]+');
                Route::get('{country}', [App\Http\Controllers\Api\v1\CountryController::class, 'show'])->where('{country}', '[0-9]+');
                Route::delete('{country}', [App\Http\Controllers\Api\v1\CountryController::class, 'destroy'])->where('{country}', '[0-9]+');
            });
        });
    });
    Route::prefix('country')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\v1\CountryController::class, 'index']);
        Route::get('{country}', [App\Http\Controllers\Api\v1\CountryController::class, 'show'])->where('{country}', '[0-9]+');
    });
});
/*--------------------------------------------------------------------------------
    Country ROUTES  => END
--------------------------------------------------------------------------------*/

/*--------------------------------------------------------------------------------
    Page ROUTES  => START
--------------------------------------------------------------------------------*/
Route::prefix('v1')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::middleware('scope:admin')->group(function () {
            Route::prefix('admin/page')->group(function () {
                Route::get('/', [App\Http\Controllers\Api\v1\PageController::class, 'adminIndex']);
                Route::post('/', [App\Http\Controllers\Api\v1\PageController::class, 'create']);
                Route::put('{page}', [App\Http\Controllers\Api\v1\PageController::class, 'update'])->where('{page}', '[0-9]+');
                Route::get('{page}', [App\Http\Controllers\Api\v1\PageController::class, 'show'])->where('{page}', '[0-9]+');
                Route::delete('{page}', [App\Http\Controllers\Api\v1\PageController::class, 'destroy'])->where('{page}', '[0-9]+');
            });
        });
    });
    Route::prefix('page')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\v1\PageController::class, 'index']);
        Route::get('{page}', [App\Http\Controllers\Api\v1\PageController::class, 'show'])->where('{page}', '[0-9]+');
    });
});
/*--------------------------------------------------------------------------------
    Page ROUTES  => END
--------------------------------------------------------------------------------*/

/*--------------------------------------------------------------------------------
    Post ROUTES  => START
--------------------------------------------------------------------------------*/
Route::prefix('v1')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::middleware('scope:admin')->group(function () {
            Route::prefix('admin/post')->group(function () {
                Route::get('/', [App\Http\Controllers\Api\v1\PostController::class, 'adminIndex']);
                Route::post('/', [App\Http\Controllers\Api\v1\PostController::class, 'create']);
                Route::put('{post}', [App\Http\Controllers\Api\v1\PostController::class, 'update'])->where('{post}', '[0-9]+');
                Route::get('{post}', [App\Http\Controllers\Api\v1\PostController::class, 'show'])->where('{post}', '[0-9]+');
                Route::delete('{post}', [App\Http\Controllers\Api\v1\PostController::class, 'destroy'])->where('{post}', '[0-9]+');
            });
        });
    });
    Route::prefix('post')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\v1\PostController::class, 'index']);
        Route::get('{post}', [App\Http\Controllers\Api\v1\PostController::class, 'show'])->where('{post}', '[0-9]+');
    });
});
/*--------------------------------------------------------------------------------
    Post ROUTES  => END
--------------------------------------------------------------------------------*/

/*--------------------------------------------------------------------------------
    Faq ROUTES  => START
--------------------------------------------------------------------------------*/
Route::prefix('v1')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::middleware('scope:admin')->group(function () {
            Route::prefix('admin/faq')->group(function () {
                Route::get('/', [App\Http\Controllers\Api\v1\FaqController::class, 'adminIndex']);
                Route::post('/', [App\Http\Controllers\Api\v1\FaqController::class, 'create']);
                Route::put('{faq}', [App\Http\Controllers\Api\v1\FaqController::class, 'update'])->where('{faq}', '[0-9]+');
                Route::get('{faq}', [App\Http\Controllers\Api\v1\FaqController::class, 'show'])->where('{faq}', '[0-9]+');
                Route::delete('{faq}', [App\Http\Controllers\Api\v1\FaqController::class, 'destroy'])->where('{faq}', '[0-9]+');
            });
        });
    });
    Route::prefix('faq')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\v1\FaqController::class, 'index']);
        Route::get('{faq}', [App\Http\Controllers\Api\v1\FaqController::class, 'show'])->where('{faq}', '[0-9]+');
    });
});
/*--------------------------------------------------------------------------------
    Faq ROUTES  => END
--------------------------------------------------------------------------------*/

/*--------------------------------------------------------------------------------
    Banner ROUTES  => START
--------------------------------------------------------------------------------*/
Route::prefix('v1')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::middleware('scope:admin')->group(function () {
            Route::prefix('admin/banners')->group(function () {
                Route::get('/', [App\Http\Controllers\Api\v1\BannerController::class, 'adminIndex']);
                Route::post('/', [App\Http\Controllers\Api\v1\BannerController::class, 'create']);
                Route::put('{banner}', [App\Http\Controllers\Api\v1\BannerController::class, 'update'])->where('{banner}', '[0-9]+');
                Route::get('{banner}', [App\Http\Controllers\Api\v1\BannerController::class, 'show'])->where('{banner}', '[0-9]+');
                Route::delete('{banner}', [App\Http\Controllers\Api\v1\BannerController::class, 'destroy'])->where('{banner}', '[0-9]+');
            });
        });
    });
    Route::prefix('banners')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\v1\BannerController::class, 'index']);
        Route::get('{banner}', [App\Http\Controllers\Api\v1\BannerController::class, 'show'])->where('{banner}', '[0-9]+');
    });
});
/*--------------------------------------------------------------------------------
    Banner ROUTES  => END
--------------------------------------------------------------------------------*/

/*--------------------------------------------------------------------------------
    Category ROUTES  => START
--------------------------------------------------------------------------------*/
Route::prefix('v1')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::middleware('scope:admin')->group(function () {
            Route::prefix('admin/categories')->group(function () {
                Route::get('/', [App\Http\Controllers\Api\v1\CategoryController::class, 'adminIndex']);
                Route::post('/', [App\Http\Controllers\Api\v1\CategoryController::class, 'create']);
                Route::put('{category}', [App\Http\Controllers\Api\v1\CategoryController::class, 'update'])->where('{category}', '[0-9]+');
                Route::get('{category}', [App\Http\Controllers\Api\v1\CategoryController::class, 'show'])->where('{category}', '[0-9]+');
                Route::delete('{category}', [App\Http\Controllers\Api\v1\CategoryController::class, 'destroy'])->where('{category}', '[0-9]+');
            });
        });
    });
    Route::prefix('categories')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\v1\CategoryController::class, 'index']);
        Route::get('{category}', [App\Http\Controllers\Api\v1\CategoryController::class, 'show'])->where('{category}', '[0-9]+');
    });
});
/*--------------------------------------------------------------------------------
    Category ROUTES  => END
--------------------------------------------------------------------------------*/
