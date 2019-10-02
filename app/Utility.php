<?php

/* 
 * Utility is used for the functionality given below:
 * - Getting images from tables
 * - - Therefore getting the user profile image from the database
 * 
 * Controllers using the Utility model:
 * Controllers\...
 * - Auth\UsersController.php
 * - ClubController.php
 * - ClubFilterController.php
 * 
 * Views using the User model:
 * Resources\Views\....blade.php
 * - Auth\edituser
 * - TAOEX\club
 * - TAOEX\editClubProfile
 * 
 * utility:
 * common functions for controlers or views
 * 
 */
namespace App;

use DB;
use Intervention\Image\ImageManagerStatic as Image;

class Utility {
    public static function get_image_fromTable($id,$table_name)
    {
        $image = DB::table($table_name)
                 ->where('id', $id)
                 ->first();
                            
        if (isset($image) && isset( $image->image ) ) {
            $type = $image->image_type;
            $data  = $image->image;  
        }else{
            $path = realpath("./images");
            $file = $path . "/empty_profile.png";
            $imageStr = (string) Image::make( $file)->
                resize( 150, null, function ( $constraint ) {
                    $constraint->aspectRatio();
                })->encode( 'png' );
            $type = "png";
            $data = base64_encode($imageStr);  
        }
        return array("type"=>$type,"data"=>$data);
    }
    
    public static function get_image_fromFile($image_file)
    {
        $imageType = $image_file->getClientOriginalExtension();
        
        /*resize the image to a width of 300 and constrain aspect ratio (auto height)*/
            
        $imageStr = (string) Image::make( $image_file )->
                                 resize( 150, null, function ( $constraint ) {
                                         $constraint->aspectRatio();
                                 })->encode( $imageType );
        $data = base64_encode($imageStr);
        return array("type"=>$imageType,"data"=>$data);
    }

}


?>