<?php

namespace Hetg\LunchGenerator\Model;


abstract class Model
{
    public static $table = null;

    public static $primaryField = 'id';

    public static $fields = [
        'id' => 'integer'
    ];
}