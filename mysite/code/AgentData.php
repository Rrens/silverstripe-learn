<?php

class AgentData extends DataObject
{
    private static $db = array(
        'Name' => 'Varchar',
        'About' => 'Text',
        'Whatsapp' => 'Text',
    );

    private static $has_one = array(
        'Photo' => 'Image'
    );

    private static $belongs_many_many = array(
        'Properties' => 'PropertyData'
    );

    public function getCMSFields()
    {
        $fields = FieldList::create(
            TextField::create('Name'),
            TextField::create('About'),
            TextField::create('Whatsapp'),
            $uploader = UploadField::create('Photo')
        );

        $uploader->setFolderName('agent-photos');
        $uploader->getValidator()->setAllowedExtensions(array(
            'png', 'jpg', 'jpeg', 'gif',
        ));

        return $fields;
    }

    public function validate()
    {
        $result = parent::validate();
        // Hapus spasi dari nomor WhatsApp
        if ($this->Whatsapp) {
            $this->Whatsapp = str_replace(' ', '', $this->Whatsapp);
        }

        if (substr($this->Whatsapp, 0, 1) === '0') {
            $this->Whatsapp = '62' . substr($this->Whatsapp, 1);
        } elseif (substr($this->Whatsapp, 0, 2) !== '62') {
            $this->Whatsapp = '62' . $this->Whatsapp;
        }

        return $result;
    }
}
