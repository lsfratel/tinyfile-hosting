<?php

namespace app\models;

use mako\database\midgard\ORM;

class File extends ORM
{
    protected $tableName = 'files';

    public function links()
    {
        return $this->hasMany(\app\models\Link::class);
    }
}
