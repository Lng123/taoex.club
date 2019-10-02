<?php
/*
 * Curse
 * 
 * This model is used in App\Rule\NoCurse
 * If you know how to insert stuff into a table using CPanel you can change what words are restricted.
 */
namespace App;
use Illuminate\Database\Eloquent\Model;

class Curse extends Model {
    protected $primary_key = 'word';
    protected $table = 'evil_words';
}
?>