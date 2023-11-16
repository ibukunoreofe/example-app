<?php

/**
 * Created by Reliese Model.
 */

namespace App\ModelsBase;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Checkout
 * 
 * @property int $id
 * @property int $user_id
 * @property int $book_id
 * @property Carbon $checkout_date
 * @property Carbon|null $return_date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Book $book
 * @property User $user
 *
 * @package App\ModelsBase
 */
class Checkout extends Model
{
	protected $table = 'checkouts';

	protected $casts = [
		'user_id' => 'int',
		'book_id' => 'int',
		'checkout_date' => 'datetime',
		'return_date' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'book_id',
		'checkout_date',
		'return_date'
	];

	public function book()
	{
		return $this->belongsTo(Book::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
