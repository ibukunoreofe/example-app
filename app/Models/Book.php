<?php

namespace App\Models;

/**
 * @OA\Schema(
 *   schema="Book",
 *   type="object",
 *   @OA\Property(property="id", type="integer", format="int64"),
 *   @OA\Property(property="title", type="string"),
 *   @OA\Property(property="author", type="string"),
 *   @OA\Property(property="isbn", type="string"),
 *   @OA\Property(property="published_at", type="string", format="date-time"),
 *   @OA\Property(property="created_at", type="string", format="date-time", description="A date and time in the ISO 8601 format."),
 *   @OA\Property(property="updated_at", type="string"),
 *   @OA\Property(property="copies", type="integer", format="int32"),
 * )
 */
class Book extends \App\ModelsBase\Book
{
    public static function getById(int $id): ?Book
    {
        return self::find($id);
    }
}
