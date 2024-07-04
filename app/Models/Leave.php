<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'type_conge',
        'date_de_depart',
        'date_de_fin',
        'description',
    ];

    public function getStatusAttribute()
    {
        $currentDate = Carbon::now();

        if ($currentDate->between($this->date_de_depart, $this->date_de_fin)) {
            return 'en cours';
        } elseif ($currentDate->greaterThan($this->date_de_fin)) {
            return 'terminé';
        }

        return 'à venir'; // If the leave has not started yet
    }
}
