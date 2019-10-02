<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\Curse;

class NoCurse implements Rule
{
    private $curse_detected;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $curse_detected = false;
    }

    
    /**
       
       * Checks if a string (the username) has a forbidden word in it.
         
       * @param string 
         
       * @return boolean if the string contains a forbidden word.
  
       * @return string of the forbidden word.
         
       */
      
    private function has_curse(string $target) {
        //get all the curse words
        $results = Curse::all();
        foreach ($results as $curse) {
            //checks if the forbidden word is/exists in the target string.
            if(strtolower($target)!==strtolower($curse['word']));
            //if(strpos(strtolower($target),strtolower($curse['word']))!==FALSE);
            //if it does, return the forbidden word.
            else return $curse['word'];
        }
        return false;
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
        $curse_detected = NoCurse::has_curse($value);
        return $curse_detected===false;
    }

    public function temp_function($attribute, $value){
        return has_curse($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You have entered a curse word. Come on, we have children browsing this site.';
    }
}