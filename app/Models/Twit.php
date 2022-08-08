<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Сообщения
 * @package App\Models
 *
 * @property int $id
 * @property int $category_id
 * @property string $username
 * @property string $content
 * @property Carbon $created_at
 *
 * @property Category $category
 */
class Twit extends Model
{
    use HasFactory;

    protected $table = 'twits';

    protected $fillable = [
        'category_id',
        'username',
        'content',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    public $timestamps = false;

    /**
     * Категории сообщений
     *
     * @return BelongsTo
     */
    public function category() {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
