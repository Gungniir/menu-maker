<?php

namespace App\Models;

use Database\Factories\ImageFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Image
 *
 * @property-read User|null $creator
 * @method static ImageFactory factory(...$parameters)
 * @method static Builder|Image newModelQuery()
 * @method static Builder|Image newQuery()
 * @method static Builder|Image query()
 * @mixin Eloquent
 * @property int $id
 * @property int $creator_id
 * @property string $name Название файла
 * @property string $filename Имя файла в фс в папке пользователя
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static Builder|Image whereCreatedAt(Carbon $value)
 * @method static Builder|Image whereCreatorId(int $value)
 * @method static Builder|Image whereDeletedAt(Carbon $value)
 * @method static Builder|Image whereFilename(string $value)
 * @method static Builder|Image whereId(int $value)
 * @method static Builder|Image whereName(string $value)
 * @method static Builder|Image whereUpdatedAt(Carbon $value)
 */
class Image extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
