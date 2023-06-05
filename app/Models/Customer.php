<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['category', 'firstname', 'lastname', 'email', 'gender', 'birthDate'];

    protected $dates = ['birthDate'];

    public function scopeFilter(Builder $query, $filters)
    {
        $query
            ->when($category = $filters->get('category'), function ($query) use ($category) {
                $query->where('category', 'like', '%' . $category . '%');
            })
            ->when($gender = $filters->get('gender'), function ($query) use ($gender) {
                $query->where('gender', $gender);
            })
            ->when($range_start = $filters->get('range_start'), function ($query) use ($range_start) {
                $birthStart = Carbon::now()->addYears(-(int)$range_start);
                $query->where('birthDate', '<=', $birthStart);
            })
            ->when($range_end = $filters->get('range_end'), function ($query) use ($range_end) {
                $birthEnd = Carbon::now()->addYears(-(int)$range_end);
                $query->where('birthDate', '>=', $birthEnd);
            });
    }

}
