<?php

namespace LoginMeNow\App\Models;

use LoginMeNow\WpMVC\App;
use LoginMeNow\WpMVC\Database\Eloquent\Model;
use LoginMeNow\WpMVC\Database\Resolver;

class User extends Model {
    public static function get_table_name():string {
        return 'users';
    }

    public function resolver():Resolver {
        return App::$container->get( Resolver::class );
    }
}