<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoBadChar implements Rule
{

    /**
       
     * Checks if a string (the username) has a code-relevant character in it.
       
     * @param string 
       
     * @return boolean if the string contains a forbidden word.

     * @return string of the forbidden word.
       
     */
    
function has_bad_char(string $target) {
        
    $tmp = preg_quote($target);
    if ($tmp != $target)
        return true;
    $matches = Array(3);
    
    //looping through the string and finding any quotation marks
    //that are in said string
    //this code works with PHP version 7.0.27 and an earlier iteration only worked with >7.2.0
    return preg_match('["|\']', $target, $matches);
    /**
     * quotes[0][1] contains the target
     * quotes[1][1] would return a "\"" substring if found in target
     * quotes[2][1] would return a "\'" substring if found in target
     * 
     * this is an unusual way to do this therefore 
     * a larger comment block is dedicated towards explaining what
     * the thought process was behind it.
     */
    if (strlen($matches[1][1]) !== 0)
      return true;
    if (strlen($matches[2][1]) !== 0)
      return true;
    return false;
}


    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !(NoBadChar::has_bad_char($value));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'No input may contain the characters " \' \\ + * ? [ ^ ] $ ( ) { } = ! < > | : -';
    }
}