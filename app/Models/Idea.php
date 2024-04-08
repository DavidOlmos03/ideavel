<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Idea extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','title','description','likes'];  //Se utiliza para especificar los campos de Idea para darle un poco mas de seguridad al sistema

    protected $casts = ['created_at'=>'datetime'];   //Por medio de esta variable $cast puedo agregar aquellos atributos que van a requerir un tipo de casteo o formato diferente al dado por defecto
    /**Para una relación de "una idea pertenece(belongsTo) a un usuario" */
    public function user(): BelongsTo
    {
        return $this->belongsTo( User::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany( User::class);
    }

    public function scopeMyIdeas(Builder $query, $filter):void
    {
        if(!empty($filter) && $filter == 'mis-ideas'){
            $query->where('user_id',auth()->id());
        }
    }

    public function scopeTheBest(Builder $query, $filter):void
    {
        if(!empty($filter) && $filter == 'las-mejores'){
            $query->orderBy('user_id','desc');
        }
    }
}
