<?php

namespace App\Http\Controllers\API\SwaggerDefinitions;

/**
 * @OA\Schema(
 *                 schema="BooksResponseComponent",
 *                 type="object",
 *                 @OA\Property(property="message", type="string"),
 *                         @OA\Property(property="id", type="integer"),
 *                         @OA\Property(property="name", type="string"),
 *                         @OA\Property(property="email", type="string", format="email"),
 * )
 */
class BooksResponseComponent
{

}