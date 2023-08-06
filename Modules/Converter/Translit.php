<?php

namespace Modules\Converter;

/**
 * Class Translit
 */
class Translit
{
    public static function convert($string)
    {
        $textInterpreter = new TextInterpreter();
        $textInterpreter->setTokenizer(new LatinTokenizer());
        $textInterpreter->addBehavior(new LatinBehaviour([]));

        $string = $textInterpreter->process($string)->getText();

        return $string;
    }
}
