<?php
/*
 * This is used in the User model.
 * assisting_clubs()
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class ClubAssistant extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'ClubAssistant';
}