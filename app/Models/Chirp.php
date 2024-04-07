<?php

namespace App\Models;

use App\Events\ChirpCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Chirp extends Model
{
    use HasFactory;


    protected $fillable = [
        'message',
    ];

    protected $dispatchesEvents = [
        'created' => ChirpCreated::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function followUserChirps(int $id): Collection
    {
        return DB::table('chirps')
            ->join('users', 'users.id', '=', 'chirps.user_id')
            ->leftJoin('followers', 'followers.user_id', '=', 'chirps.user_id')
            ->where('followers.follower_user_id', $id)
            ->orWhere('chirps.user_id', $id)
            ->select('chirps.*', 'users.name')
            ->get();
    }
}
