<?php

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * Return database table name
     */
    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    /**
     * @param string $field
     * @param string $format
     * @return mixed
     */
    public function printTime($field = 'created_at', $format = '')
    {
        if ($format) {
            $configFormat = Config::get("time.$format");
            if ($configFormat) {
                $format = $configFormat;
            }
        } else {
            $format = Config::get('time.default');
        }
        return $this->$field->format($format);
    }
}