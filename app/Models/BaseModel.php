<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Scopes\SortByLastUpdatedScope;

class BaseModel extends Model
{

    use Transformable, Fillable;

    /**
     * A Faker instance for the model.
     *
     * @TODO This sorta belongs in Fillable, but it should likely be a Provider, not tied to model(s).
     *
     * @var \Faker\Generator
     */
    public $faker;


    /**
     * Create a new model instance. Also instantiates a $faker class.
     *
     * @param  array  $attributes
     * @return void
     */
    function __construct($attributes = array())
    {
        parent::__construct($attributes);

        // We are putting the faker definition here to avoid __construct conflicts
        $this->faker = \Faker\Factory::create();

    }


    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var boolean
     */
    public $incrementing = false;


    /**
     * The attributes that aren't mass assignable. Generally,
     * we want all attributes to be mass assignable. Laravel
     * defaults to guarding all attributes.
     *
     * @var array
     */
    protected $guarded = [];


    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {

        parent::boot();
        static::addGlobalScope(new SortByLastUpdatedScope());

    }


    /**
     * Return the cached instance or create a new instance of this model.
     * Uses an array to accomodate for multiple inherited models calling
     * this same method.
     *
     * @return static
     */
    public static function instance()
    {

        static $instances = array();

        $calledClass = get_called_class();

        if (!isset($instances[$calledClass]))
        {

            $instances[$calledClass] = new $calledClass();

        }

        return $instances[$calledClass];

    }


    /**
     * Find the record matching the given id or create it.
     *
     * @param  int    $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function findOrCreate($id)
    {

        $model = static::find($id);
        return $model ?: static::create([static::instance()->getKeyName() => $id]);

    }


}
