<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = ['shop_name', 'genre_id', 'area_id', 'description', 'avg_rating', 'image_url'];

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function owners()
    {
        return $this->belongsToMany(User::class, 'owners', 'shop_id', 'user_id');
    }

    public function scopeGenreSearch($query, $genre_id)
    {
        if (!empty($genre_id)) {
            $query->where('genre_id', $genre_id);
        }
    }

    public function scopeAreaSearch($query, $area_id)
    {
        if (!empty($area_id)) {
            $query->where('area_id', $area_id);
        }
    }

    public function scopeKeywordSearch($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where('shop_name', 'like', '%' . $keyword . '%');
        }
    }

    public function getIsFavoritedAttribute()
    {
        if (Auth::check()) {
            return $this->favorites()->where('user_id', Auth::id())->exists();
        }
        return false;
    }

    // Shop.php

    public function hasReviewed(int $userId)
    {
        return $this->reviews()->where('user_id', $userId)->exists();
    }

    // 平均評価を取得するアクセサ
    public function getAverageRatingAttribute()
    {
        // reviewsリレーションから平均値を計算
        return $this->reviews()->avg('rating') ?? 0;
    }
}
