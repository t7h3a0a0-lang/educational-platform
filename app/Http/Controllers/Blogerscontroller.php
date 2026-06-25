<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class Blogerscontroller extends Controller
{
    function datauser () {
    $data=request();
    $name=$data->name;
    $emile=$data->email;
    $password=$data->password;
    $conpassword=$data->password_confirmation;
  //  $inser_user= new User;
  //  $inser_user->name=$name;
  //  $inser_user->email=$emile;
  //  $inser_user->password=$password;
  //  $inser_user->save();
  //  return"secses full";
   $select_user=User::where("name","taha")->first();
   if($select_user){
    echo   "<br>".$select_user->$name;
      echo "<br>". $select_user->$emile;
    echo  $select_user->$password;

   }
   
return  $select_user;

}
}

