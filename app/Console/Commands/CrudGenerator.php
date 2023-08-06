<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CrudGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:generator
    {name : Class (singular) for example User} {path : Class (singular) for example User Api} {table : Class (singular) for example users} {--module=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create CRUD operations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $name = $this->argument('name');
        $path = $this->argument('path');
        $dbname = $this->argument('table');
        $paramName = lcfirst($name);
        $notUse = ['id', 'created_at', 'updated_at', 'deleted_at', 'is_deleted'];
        // $this->model($name, $dbname);
        $this->resource($name, $dbname);
        $this->request($name, $dbname, $notUse);
        $this->repository($name, $paramName, $dbname);
        $this->controller($name, $path, $dbname, $paramName, $notUse, 'SWAGGER');
        $this->interface($name, $paramName);

        if ($path == '/') {
            $namespace = '';
        } else {
            $namespace = str_replace('/', '\\', $path);
        }

        $routeName = str_replace('_', '-', $dbname);

        $routes = "
/*--------------------------------------------------------------------------------
    {$name} ROUTES  => START
--------------------------------------------------------------------------------*/
    Route::prefix('v1')->group(function () {
        Route::middleware('auth:api')->group(function () {
            Route::middleware('scope:admin')->group(function() {
                Route::prefix('admin/{$routeName}')->group(function () {
                    Route::get('/', [App\Http\Controllers\\{$namespace}\\{$name}Controller::class,'adminIndex']);
                    Route::post('/', [App\Http\Controllers\\{$namespace}\\{$name}Controller::class,'create']);
                    Route::put('{{$paramName}}', [App\Http\Controllers\\{$namespace}\\{$name}Controller::class,'update'])->where('{{$paramName}}', '[0-9]+');
                    Route::get('{{$paramName}}', [App\Http\Controllers\\{$namespace}\\{$name}Controller::class,'show'])->where('{{$paramName}}', '[0-9]+');
                    Route::delete('{{$paramName}}', [App\Http\Controllers\\{$namespace}\\{$name}Controller::class,'destroy'])->where('{{$paramName}}', '[0-9]+');
                });
            });
        });
        Route::prefix('{$routeName}')->group(function () {
            Route::get('/', [App\Http\Controllers\\{$namespace}\\{$name}Controller::class,'index']);
            Route::get('{{$paramName}}',[App\Http\Controllers\\{$namespace}\\{$name}Controller::class,'show'])->where('{{$paramName}}', '[0-9]+');
        });
    });
/*--------------------------------------------------------------------------------
    {$name} ROUTES  => END
--------------------------------------------------------------------------------*/
";
        //  File::append(base_path('routes/api.php'), $routes);
        $this->info('The command was successful!');
    }

    protected function model(string $name, string $tableName): void
    {
        $attributes = Schema::getColumnListing($tableName);
        $fields = '';
        $columns = '';
        $i = 0;
        $count = count($attributes);
        $callTraits = '';
        $path = '';
        $translatableFunctions = '';
        $files = ['poster', 'photo', 'icon', 'file', 'video'];
        $relations = '';
        if (in_array('lang_hash', $attributes)) {
            $path .= 'use  '.config('system.crud-path.translatable').";\n";
            $callTraits .= "\n\tuse Translatable;";
            $translatableFunctions .= $this->translatableFunctions();

        }
        if (in_array('deleted_at', $attributes)) {
            $path .= "use Illuminate\Database\Eloquent\SoftDeletes;\n";
            $callTraits .= "\n\tuse SoftDeletes;";
        }

        foreach ($attributes as $attribute) {
            $type = Schema::getColumnType($tableName, $attribute);
            if ($attribute != 'id') {
                $i++;
                if ($i == $count) {
                    $fields .= "'{$attribute}'";
                } else {
                    $fields .= "'{$attribute}', ";
                }
                $columns .= match ($type) {
                    'text', 'datetime' => "\n * @property string $$attribute",
                    'bigint' => "\n * @property integer $$attribute",
                    default => "\n * @property {$type} $$attribute",
                };
            }

            /** create relation functions */
            if (str_contains($attribute, '_id')) {
                $modelName = rtrim($attribute, '_id');
                $modelNameToUpCase = ucfirst($modelName);
                $path .= "use Illuminate\Database\Eloquent\Relations\BelongsTo;\n";
                if (in_array($modelName, $files)) {
                    if (! str_contains($path, 'Files')) {
                        $path .= "use Modules\Filemanager\Entities\Files;\n";
                    }

                    $relations .= "\n\tpublic function $modelName(): BelongsTo
    {
        return \$this->belongsTo(Files::class);
    }\n";

                } elseif (File::exists(base_path("app/Models/{$modelNameToUpCase}.php"))) {
                    $relations .= "\n\tpublic function $modelName(): BelongsTo
    {
        return \$this->belongsTo($modelNameToUpCase::class);
    }\n";

                }

            }
        }

        $modelTemplate = str_replace(
            [
                '{{modelName}}',
                '{{fillable}}',
                '{{table}}',
                '{{columns}}',
                '{{callTraits}}',
                '{{path}}',
                '{{translatableFunctions}}',
                '{{relations}}',

            ],
            [
                $name,
                $fields,
                $tableName,
                $columns,
                $callTraits,
                $path,
                $translatableFunctions,
                $relations,
            ],
            $this->getStub('Model')
        );

        file_put_contents(app_path("/Models/{$name}.php"), $modelTemplate);
    }

    protected function getStub($type): bool|string
    {
        return file_get_contents(resource_path("stubs/$type.stub"));
    }

    public function getColumnInfo($table, $column)
    {

        return DB::select("SELECT is_nullable,character_maximum_length
FROM information_schema.columns
WHERE table_schema = 'public'
AND table_name = '{$table}'
AND column_name='{$column}';")[0];
    }

    protected function controller($name, $path, $tableName, $paramName, $notUse, $documentation)
    {
        $attributes = Schema::getColumnListing($tableName);
        $fields = '';
        $response = '';
        $stub = '';
        foreach ($attributes as $attribute) {
            $type = Schema::getColumnType($tableName, $attribute);
            switch ($type) {
                case 'text':
                    $type = 'string';
                    break;
                case 'bigint':
                    $type = 'integer';
                    break;
            }
            if (strtoupper($documentation) === 'SWAGGER') {
                if (! in_array($attribute, $notUse)) {
                    $fields .= "\n\t *  \t\t\t@OA\Property(property='$attribute',type='$type'),";
                    $fields = str_replace("'", '"', $fields);
                    $stub = $this->getStub('ControllerSwagger');

                }
            } else {
                $fields .= "     * @bodyParam {$attribute} {$type}\n";
                $response .= "     *  \"{$attribute}\": \"{$type}\",\n";
                $stub = $this->getStub('Controller');

            }

        }

        if ($path == '/') {
            $path = "/Http/Controllers/{$name}Controller.php";
            $namespace = '';
        } else {
            $namespace = '\\'.str_replace('/', '\\', $path);
            $path = "/Http/Controllers/{$path}/{$name}Controller.php";
        }

        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{fields}}',
                '{{namespace}}',
                '{{response}}',
                '{{paramName}}',
                '{{routeName}}',

            ],
            [
                $name,
                strtolower(Str::plural($name)),
                strtolower($name),
                $fields,
                $namespace,
                $response,
                $paramName,
                $tableName,

            ],
            $stub
        );

        file_put_contents(app_path($path), $controllerTemplate);
        $artisanCall = $documentation === 'SWAGGER' ? 'l5-swagger:generate' : 'scribe:generate';
        Artisan::call($artisanCall);
    }

    protected function repository($name, $paramName, $tableName)
    {
        $this->createDirectory($name, 'Repository', 'Repositories');
        $attributes = Schema::getColumnListing($tableName);
        if (in_array('lang_hash', $attributes)) {
            $stub = file_get_contents(resource_path('stubs/RepositoryWithTranslation.stub'));
        } else {
            $stub = $this->getStub('Repository');
        }
        $repositoryTemplate = str_replace(['{{modelName}}', '{{paramName}}'], [$name, $paramName], $stub);
        file_put_contents(app_path("Http/Repositories/{$name}Repository.php"), $repositoryTemplate);
    }

    protected function resource($name, $tableName)
    {
        $attributes = Schema::getColumnListing($tableName);
        $columns = '';

        foreach ($attributes as $attribute) {
            $columns .= "\n\t\t\t'{$attribute}' => \$this->$attribute,";
        }
        $this->createDirectory($name, 'Resource', 'Resources');
        $resourceTemplate = str_replace(['{{columns}}', '{{modelName}}'], [$columns, $name], $this->getStub('Resource'));
        file_put_contents(app_path("Http/Resources/{$name}Resource.php"), $resourceTemplate);
    }

    protected function request($name, $tableName, $notUse)
    {
        $attributes = Schema::getColumnListing($tableName);
        $rulesCreate = '';
        $rulesUpdate = '';
        foreach ($attributes as $attribute) {
            $type = Schema::getColumnType($tableName, $attribute);
            if (! in_array($attribute, $notUse)) {
                $columnInfo = $this->getColumnInfo($tableName, $attribute);
                $maxLength = $columnInfo->character_maximum_length;
                $ter = is_null($maxLength) ? null : "|max:{$maxLength}";
                $isReq = $columnInfo->is_nullable == 'NO' ? 'required' : 'nullable';

                $rulesCreate .= match ($type) {
                    'text' => "\n\t\t\t'{$attribute}' => 'string|{$isReq}{$ter}',",
                    'bigint' => "\n\t\t\t'{$attribute}' => 'integer|{$isReq}',",
                    default => "\n\t\t\t'{$attribute}' => '{$type}|{$isReq}{$ter}',",
                };
                $rulesUpdate .= match ($type) {
                    'text' => "\n\t\t\t'{$attribute}' => 'string|nullable{$ter}',",
                    'bigint' => "\n\t\t\t'{$attribute}' => 'integer|nullable{$ter}',",
                    default => "\n\t\t\t'{$attribute}' => '{$type}|nullable{$ter}',",
                };
            }
        }

        $createRequestTemplate = str_replace(
            [
                '{{class}}',
                '{{rules}}',
                '{{path}}',
            ],
            [
                "{$name}CreateRequest",
                $rulesCreate,
                "{$name}Request",
            ],

            $this->getStub('Request')
        );
        $updateRequestTemplate = str_replace(
            [
                '{{class}}',
                '{{rules}}',
                '{{path}}',
            ],
            [
                "{$name}UpdateRequest",
                $rulesUpdate,
                "{$name}Request",
            ],

            $this->getStub('Request')
        );
        $this->createDirectory($name, 'Request', 'Requests');

        file_put_contents(app_path("Http/Requests/{$name}Request/{$name}CreateRequest.php"), $createRequestTemplate);
        file_put_contents(app_path("Http/Requests/{$name}Request/{$name}UpdateRequest.php"), $updateRequestTemplate);
    }

    protected function interface($name, $paramName)
    {
        $bind = '';
        $path = '';
        $getProviderStub = $this->getStub('RepositoryServiceProvider');
        $interfaceTemplate = str_replace(
            [
                '{{modelName}}',
                '{{paramName}}',
            ],
            [
                $name,
                $paramName,
            ],

            $this->getStub('Interface')
        );

        file_put_contents(app_path("Http/Interfaces/{$name}Interface.php"), $interfaceTemplate);
        $interfaces = (scandir(app_path('Http/Interfaces')));

        foreach ($interfaces as $interface) {
            $fileName = substr($interface, 0, -13);
            if (! str_contains($getProviderStub, $fileName)) {
                $bind .= "\n\t\t\$this->app->bind({$fileName}Interface::class, {$fileName}Repository::class);";
                $path .= "\nuse App\Http\Interfaces\\{$fileName}Interface;";
                $path .= "\nuse App\Http\Repositories\\{$fileName}Repository;";
            }
        }

        $providerTemplate = str_replace(
            [
                '{{path}}',
                '{{bind}}',
            ],
            [
                $path,
                $bind,
            ],

            $getProviderStub

        );
        file_put_contents(app_path('/Providers/RepositoryServiceProvider.php'), $providerTemplate);

    }

    protected function createDirectory($name, $path, $plural)
    {
        if ($path === 'Request') {
            if (! File::exists(base_path("app/Http/{$plural}/{$name}{$path}"))) {
                mkdir(base_path("app/Http/{$plural}/{$name}{$path}"), 0777, true);
            }

        } else {
            if (! File::exists(base_path("app/Http/{$plural}"))) {
                mkdir(base_path("app/Http/{$plural}"), 0777, true);
            }
        }

    }

    protected function translatableFunctions(): string
    {
        return
            "\n\tpublic function setTitleAttribute(\$value)
    {
        \$this->setTranslation(\$value, 'title');
    }

    public function getTranslationsAttribute(): array
    {
        return \$this->getTranslation(\$this);
    }";

    }
}
