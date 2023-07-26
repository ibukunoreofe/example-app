<?php

namespace App\Models;

class Book extends \App\ModelsBase\Book
{
    public static function getById(int $id): ?Book
    {
        return self::find($id);
    }
}
