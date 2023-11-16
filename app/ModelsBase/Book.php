<?php

/**
 * Created by Reliese Model.
 */

namespace App\ModelsBase;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Book
 * 
 * @property int $id
 * @property string $title
 * @property string $author
 * @property string $isbn
 * @property Carbon $published_at
 * @property int|null $copies
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Collection|Checkout[] $checkouts
 *
 * @package App\ModelsBase
 */
class Book extends Model
{
	protected $table = 'books';

	protected $casts = [
		'published_at' => 'datetime',
		'copies' => 'int'
	];

	protected $fillable = [
		'title',
		'author',
		'isbn',
		'published_at',
		'copies'
	];

	public function checkouts()
	{
		return $this->hasMany(Checkout::class);
	}
}
