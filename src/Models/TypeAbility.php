<?php

namespace dnj\AAA\Models;

use dnj\AAA\Database\Factories\TypeAbilityFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int    $id
 * @property string $name
 * @property int    $type_id
 * @property Type   $type
 */
class TypeAbility extends Model
{
    use HasFactory;

    public static function newFactory(): TypeAbilityFactory
    {
        return TypeAbilityFactory::new();
    }

    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'aaa_types_abilities';

    protected $fillable = ['name'];

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }
}
