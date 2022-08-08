<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Категории сообщений
 *
 * @package App\Models
 *
 * @property int    $id
 * @property string $title Заголовок
 *
 * @property Twit[] $twits
 */
class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'title'
    ];

    public $timestamps = false;

    /**
     * Короткие сообщения
     *
     * @return HasMany
     */
    public function twits(): HasMany {
        return $this->hasMany(Twit::class, 'category_id', 'id');
    }
}
