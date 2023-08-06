<?php

namespace Modules\Translations\Entities;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class Langs extends Model
{
    protected $table = 'langs';

    const LANG_UZ = 1;

    const LANG_RU = 2;

    const LANG_EN = 3;

    protected $fillable = ['id', 'name', 'code', 'status'];

    public static function rules(): array
    {
        return [
            'name' => 'string|required',
            'code' => 'string|required',
            'status' => 'integer',
        ];
    }

    public static function getLangId($lang)
    {
        if (empty($lang)) {
            throw new BadRequestException('Please enter the language');
        }

        return Langs::where('code', $lang)->first()->id;
    }

    public static function getLangCode($id)
    {
        if (empty($id)) {
            throw new BadRequestException('Please enter id of language');
        }

        return Langs::where('id', $id)->first()->code;
    }
}
