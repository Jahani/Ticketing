<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    protected $fillable = ['name', 'start', 'end'];

    /**
     * Relations
     */

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class)
            ->withPivot('price')->withTimestamps();
    }

    public function seats()
    {
        return $this->belongsToMany(Seat::class)
            ->as('reserves')
            ->withPivot('price', 'status', 'session_id', 'user_id', 'order_id')
            ->withTimestamps()
            ->using(SeatShow::class);
    }

    /**
     * Helpers
     */

    public function venues()
    {
        return $this->sections->map(function($section) {
            return $section->stage->venue;
        })->unique();
    }

    public function sectionSeats(Section $section)
    {
        $sectionSeats = $section->seats;
        // TODO : Built query is not specific about section_id
        $showSeats = $this->seats->where('section_id', $section->id);
        $seats = $sectionSeats->merge($showSeats);
        return $seats;
    }

    public function hasSection(Section $section)
    {
        return $this->sections->contains($section);
    }

    public function sectionPrice(Section $section)
    {
        return $this->sections->find($section)->pivot->price;
    }
}
