<?php

namespace App\Model;

class SettingsEntity extends \Nette\Object implements ISettingsEntity
{

   private $id;
   private $field;
   private $value;

    public function id($id = null) {
        if (!is_null($id))
            $this->id = $id;
        return $this->id;
    }

    public function field($field = null) {
        if (!is_null($field))
            $this->field = $field;
        return $this->field;
    }

    public function value($value = null) {
        if (!is_null($value))
            $this->value = $value;
        return $this->value;
    }

}
