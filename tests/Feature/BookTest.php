<?php

namespace Tests\Feature;

use Tests\InteractsWithUsers;
use Tests\TestCase;

class BookTest extends TestCase
{
    use InteractsWithUsers;

    public function test_user_can_store_book(): void
    {
        $this->setUpUser();
        $response = $this->post('/api/books',
            [
                'title' => 'Ibukun Bello',
                'author' => 'Ibukun Bello',
                'isbn' => 'ibukunoreofe@gmail.com',
                'published_at' => '2023-07-25',
                'copies' => 15,
            ]);
        $response->assertOk();
    }

    public function test_user_can_list_books(): void
    {
        $this->setUpUser();
        $response = $this->get('/api/books');
        $response->assertOk();
        $response->assertJsonCount(1);
    }

    public function test_user_can_update_books(): void
    {
        $this->setUpUser();
        $response = $this->put('/api/books/1', [
            'title' => 'Nice Title',
            'author' => 'Ibukun Bello',
            'isbn' => 'ibukunoreofe@gmail.com',
            'published_at' => '2023-07-25',
            'copies' => 10,
        ]);
        $response->assertOk();
    }

    public function test_wrong_update_books(): void
    {
        $this->setUpUser();
        $response = $this->put('/api/books/5', [
            'title' => 'Nice Title',
            'author' => 'Ibukun Bello',
            'isbn' => 'ibukunoreofe@gmail.com',
            'published_at' => '2023-07-25',
            'copies' => 10,
        ]);
        $response->assertStatus(417);
    }

    public function test_user_can_checkout_books(): void
    {
        $this->setUpUser();
        $response = $this->post('/api/checkouts', [
            'user_id' => $this->user->id,
            'book_id' => 1,
        ]);
        $response->assertOk();
    }

    public function test_check_out_invalid_books(): void
    {
        $this->setUpUser();
        $response = $this->post('/api/checkouts', [
            'user_id' => $this->user->id,
            'book_id' => 55,
        ]);

        $response->assertStatus(412);
    }

    public function test_user_can_return_books(): void
    {
        $this->setUpUser();
        $response = $this->put('/api/checkouts/1');
        $response->assertOk();
    }

    public function test_user_can_delete_books(): void
    {
        $this->setUpUser();
        $response = $this->delete('/api/books/1');
        $response->assertNoContent();
    }
}
