<?php

namespace LoginMeNow\App\Models;

use LoginMeNow\WpMVC\App;
use LoginMeNow\WpMVC\Database\Eloquent\Model;
use LoginMeNow\WpMVC\Database\Resolver;

class PostMeta extends Model {
    public static function get_table_name():string {
        return 'postmeta';
    }

    public function resolver():Resolver {
        return App::$container->get( Resolver::class );
    }
}