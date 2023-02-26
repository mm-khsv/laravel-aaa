<?php

namespace dnj\AAA\Models;

use dnj\AAA\Contracts\ITypeLocalizedDetails;
use dnj\AAA\Database\Factories\TypeLocalizedDetailsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int    $id
 * @property string $lang
 * @property int    $type_id
 * @property string $title
 * @property Type   $type
 */
class TypeLocalizedDetails extends Model implements ITypeLocalizedDetails
{
    use HasFactory;

    public static function newFactory(): TypeLocalizedDetailsFactory
    {
        return TypeLocalizedDetailsFactory::new();
    }

    /**
     * @var string
     */
    protected $table = 'aaa_types_translates';

    protected $fillable = ['lang', 'title'];

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLang(): string
    {
        return $this->lang;
    }

    public function getTypeID(): int
    {
        return $this->type_id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
