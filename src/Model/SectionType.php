<?php

namespace Realm\Model;

class SectionType extends AbstractModel
{
    use PropertyTrait;

    protected $id;
    protected $label;
    protected $fields = [];

    public function addField(SectionFieldType $field)
    {
        $this->fields[] = $field;
        return $this;
    }
}
