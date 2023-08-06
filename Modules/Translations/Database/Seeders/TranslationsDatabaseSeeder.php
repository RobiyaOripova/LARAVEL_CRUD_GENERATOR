<?php

namespace Modules\Translations\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Translations\Entities\Langs;

class TranslationsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $languages = [
            ['name' => 'Uz', 'code' => 'uz', 'status' => 1],
            ['name' => 'En', 'code' => 'en', 'status' => 1],
            ['name' => 'Ру', 'code' => 'ru', 'status' => 1],
        ];

        foreach ($languages as $language) {
            Langs::create([
                'name' => $language['name'],
                'code' => $language['code'],
                'status' => $language['status'],
            ]);
        }
    }
}
