<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table       = 'menu';

    protected $guarded     = ['MenuID'];
    protected $primaryKey  = 'MenuID';

    public static  function get_single_record($column,$value)
    {
         $query =  Menu::where($column,$value)->limit(1)->get();
         if ($query->count() == 1) 
         {
         	return  $query->toArray()[0];
         }
         else
         {
         	return 'false';
         }
    }
}
