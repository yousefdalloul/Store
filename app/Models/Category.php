<?php

namespace App\Models;

use Couchbase\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','parent_id','description','image','status','slug'
    ];

    public function scopeActive(Builder $builder){
        $builder->where('status','=','active');
    }

    public function scopeFilter(Builder $builder,$filters){

        $builder->when($filters['name']??false, function($builder,$value){
            $builder->where('categories.name','LIKE',"%{$value}");
        });
        $builder->when($filters['status']??false, function($builder,$value){
            $builder->where('categories.status','LIKE',$value);
        });
    }

    public static function rules($id=0){

        return[
            'name'=> ['required',
            'String',
            'min:3',
            'max:255',
                //unique:categories,name,$id,
            Rule::unique('categories','name')->ignore($id),
                function($attribute,$value,$fails){
                if(strtolower($value) =='laravel'){
                    $fails('This name is forbidden!');
                }
                }
            ],
            'parent_id' =>[
                'nullable','int','exists:categories,id'
            ],
            'image' => [
                'image', 'max:1048576','dimensions:min_width=100,min_height=100'
            ],
            'status' => 'required|in:active,archived',
        ];
    }
}
