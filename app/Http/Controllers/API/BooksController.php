<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Responses\OkResponse;
use App\Http\Responses\PreConditionFailedResponse;
use App\Models\Book;
use App\Models\Checkout;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
     *                 ref="App\Http\Controllers\API\SwaggerDefinitions\BooksResponseComponent"
     *             )
     *         )
     *     )
     * )
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|Collection
     */
    public function getBooks(): Collection|array
    {
        return Book::query()->get();
    }

    public function storeBook(Request $request): OkResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'string', 'max:255'],
            'published_at' => ['required', 'date_format:Y-m-d', 'before:today'],
            'copies' => ['required', 'integer', 'min:1', 'max:30000'],
        ]);

        $book = Book::query()->create($validated);

        return new OkResponse($book);
    }

    public function updateBook(Request $request, Book $book): OkResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'string', 'max:255'],
            'published_at' => ['required', 'date_format:Y-m-d', 'before:today'],
            'copies' => ['required', 'integer', 'min:1', 'max:30000'],
        ]);

        $book->update($validated);

        return new OkResponse($book);
    }

    public function deleteBook(Book $book): Response
    {
        $book->delete();

        return response()->noContent();
    }

    public function checkouts(Request $request): PreConditionFailedResponse|OkResponse
    {
        $validated = $request->validate([
            'book_id' => ['required', 'numeric', 'exists:books,id'],
            'user_id' => ['required', 'numeric', 'exists:users,id'],
        ]);

        // TODO: This should be atomic and thread safe. Using Mutex, it should allow only one thread at a time
        $book = Book::getById($request->input('book_id'));

        if (! $book->copies) {
            return new PreConditionFailedResponse(['message' => 'There is no available copy of this book to checkout!']);
        }

        $checkout = Checkout::query()->create(array_merge($validated, [
            'checkout_date' => now(),
            'return_date' => null,
        ]));

        $book->copies--;
        $book->update();

        return new OkResponse($checkout);
    }

    public function returnCheckout(Checkout $checkout): PreConditionFailedResponse|OkResponse
    {
        if ($checkout->return_date) {
            return new PreConditionFailedResponse(['message' => 'Book has already been marked returned!']);
        }

        $checkout->update(['return_date' => now()]);

        $checkout->book->copies++;
        $checkout->book->update();

        return new OkResponse();
    }
}
