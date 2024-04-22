<?php

class FacilityData extends DataObject
{
    private static $db = array(
        'Title' => 'Text',
    );

    private static $has_many = array(
        'Properties' => 'PropertyData'
    );

    private static $has_one = array(
        'Photo' => 'Image'
    );

    public function getCMSFields()
    {
        $fields = FieldList::create(TabSet::create('Root'));

        $fields->addFieldsToTab('Root.Main', array(
            TextField::create('Title'),
            $upload = UploadField::create(
                'Photo',
                'Photo'
            )
        ));

        $upload->getValidator()->setAllowedExtensions(array(
            'png', 'jpg', 'jpeg', 'gif',
        ));

        $upload->setFolderName('facility-photos');

        return $fields;
    }
}
