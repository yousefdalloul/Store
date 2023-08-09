<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Str;

class ModelPolicy
{

    public function before($user,$ability)
    {
        if ($user->super_admin){
            return true;
        }
    }

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function __cal($name,$arguments)
    {
        $class_name = str_replace('policy','',class_bassename($this));
        $class_name = Str::plural(Str::lower($class_name));

        $ability = $class_name . '.' . Str::kebab($name);
        if ($name == 'viewAny'){
            $name='view';
        }
        $user = $arguments[0];
        if (isset($arguments[1])) {
            $model = $arguments[1];
            if ($model->store_id != $user->store_id) {
                return false;
            }
        }
        return $user->hasAbility($ability);
    }
}
