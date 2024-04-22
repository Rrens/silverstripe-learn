<?php

class PropertyTypeData extends DataObject
{
    private static $db = array(
        'Title' => 'Varchar'
    );

    private static $belongs_many_many = array(
        'Properties' => 'PropertyData'
    );

    public function getCMSFields()
    {
        $fields = FieldList::create(TabSet::create('Root'));

        $fields->addFieldsToTab('Root.Main', array(
            TextField::create('Title')
        ));

        return $fields;
    }
}
