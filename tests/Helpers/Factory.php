<?php

namespace Tests\Helpers;

trait Factory
{


    protected $times = 1;
    protected $ids = [];
    protected $attachTypes = [];
    protected $attachTimes = 1;
    protected $attachRelation = '';
    protected $attachFields = [];

    protected function times($count)
    {
        $this->times = $count;
        return $this;
    }

    protected function attach($types, $times = 1, $relation = '', $fields = []) {

        if( !is_array($types) )
        {
            $types = array( $types );
        }

        $this->attachTypes = $types;
        $this->attachTimes = $times;
        $this->attachRelation = $relation;
        $this->attachFields = $fields;
        return $this;

    }

    protected function make($type, $fields = [])
    {

        while ($this->times-- > 0) {

            $model = factory($type)->create($fields);

            if ($this->attachTypes)
            {

                while ($this->attachTimes-- > 0)
                {

                    foreach ($this->attachTypes as $attachType)
                    {

                        $class = $this->classFrom($attachType);

                        $relation = $this->attachRelation ? $this->attachRelation : lcfirst(str_plural($class));

                        $attach = factory($attachType)->create($this->attachFields);

                        if ($model->$relation() instanceof \Illuminate\Database\Eloquent\Relations\BelongsTo)
                        {

                            $model->$relation()->associate($attach);

                        }
                        elseif ($model->$relation() instanceof \Illuminate\Database\Eloquent\Relations\HasMany)
                        {

                            $model->$relation()->save($attach);

                        }
                        else
                        {

                            $model->$relation()->attach($attach->getKey());

                        }

                    }

                }

            }

            $this->ids[] = $model->getAttributeValue($model->getKeyName());

        }
        $this->reset();

        return last($this->ids);
    }

    protected function classFrom($type)
    {

        $path = explode('\\', $type);
        return array_pop($path);

    }

    protected function reset()
    {

        $this->times = 1;
        $this->attachTypes = [];
        $this->attachTimes = 1;
        $this->attachRelation = '';
        $this->attachFields = [];

    }

}
