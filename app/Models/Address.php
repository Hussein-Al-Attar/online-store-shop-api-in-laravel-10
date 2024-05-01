<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','address_line_1','address_line_2','street', 'city', 'state', 'postal_code', 'country'];

    // Define relationships or additional methods as needed
}
