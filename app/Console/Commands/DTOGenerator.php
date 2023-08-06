<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class DTOGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:dto {name} {--table= : for example films} {--tables= : for example banners,posts }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $table = $this->option('table');
        $tables = $this->option('tables');
        $name = $this->argument('name');
        $properties = '';
        if (empty($table) && empty($tables)) {
            $this->info('Please enter a table name or multiple table names, e.g. --table=films or --tables=banners,posts');
        } else {
            if (empty($tables) && ! empty($table)) {
                $attributes = Schema::getColumnListing($table);
                $properties = $this->makeProperty($attributes, $table, $properties);

            } else {
                $getTables = explode(',', $tables);
                for ($i = 0; $i < count($getTables); $i++) {
                    $attributes = Schema::getColumnListing($getTables[$i]);
                    $properties = $this->makeProperty($attributes, $getTables[$i], $properties);
                }
                $properties = implode(';', array_unique(explode(';', $properties)));
            }

            if (! File::exists(app_path('Http/Repositories/DTO'))) {
                mkdir(app_path('Http/Repositories/DTO'), 0777, true);
            }

            $dtoTemplate = str_replace(['{{name}}', '{{properties}}'], [$name, $properties], file_get_contents(resource_path('stubs/Dto.stub')));
            file_put_contents(app_path("Http/Repositories/DTO/{$name}DTO.php"), $dtoTemplate);
            $this->info('The command was successful!');
        }

    }

    protected function makeProperty($attributes, $tables, $properties)
    {
        $notUse = ['id', 'created_at', 'updated_at', 'deleted_at', 'lang_hash'];
        for ($j = 0; $j < count($attributes); $j++) {
            if (! in_array($attributes[$j], $notUse)) {
                $type = Schema::getColumnType($tables, $attributes[$j]);
                $getColumnInfo = $this->getColumnInfo($tables, $attributes[$j]);
                $properties .= match ($type) {
                    'text', 'datetime' => $getColumnInfo->is_nullable == 'NO' ? "\n\t public string $$attributes[$j];" : "\n\t public ?string $$attributes[$j];",
                    'bigint', 'integer' => $getColumnInfo->is_nullable == 'NO' ? "\n\t public int $$attributes[$j];" : "\n\t public ?int $$attributes[$j];",
                    default => $getColumnInfo->is_nullable == 'NO' ? "\n\t public {$type} $$attributes[$j];" : "\n\t public ?{$type} $$attributes[$j];",
                };

            }
        }

        return $properties;
    }

    protected function getColumnInfo($table, $column)
    {

        return DB::select("SELECT is_nullable,character_maximum_length
FROM information_schema.columns
WHERE table_schema = 'public'
AND table_name = '{$table}'
AND column_name='{$column}';")[0];
    }
}
