<?php

namespace App\Http\Controllers\API\v2;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;

class BooksController extends Controller
{

    /**
     * @OA\Get(
     *     path="/books",
     *     tags={"Books"},
     *     summary="Get all books",
     *     description="Muliple tags can be provided with comma separated strings. Use tag1, tag2, tag3 for testing. operationId can be reference will see annotation from another comment",
     *     operationId="listAllBooks",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 ref="App\Models\Book"
     *             )
     *         )
     *     )
     * )
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|Collection
     */
    public function getBooks(): Collection|array
    {
        return [
            "books" => Book::query()->get(),
            "version" => "v2"
        ];
    }

}
