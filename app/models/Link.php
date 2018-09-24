<?php

namespace app\models;

use mako\database\midgard\ORM;

class Link extends ORM
{
    protected $tableName = 'links';
    protected $primaryKey = 'token';
    protected $primaryKeyType = ORM::PRIMARY_KEY_TYPE_CUSTOM;

    public function file()
    {
        return $this->belongsTo(\app\models\File::class);
    }

    protected function generatePrimaryKey()
    {
        return md5(microtime(true));
    }
}
