<?php

class Region extends DataObject
{
    private static $db = array(
        'Title' => 'Varchar',
        'Description' => 'Text'
    );

    private static $has_one = array(
        'Photo' => 'Image',
        'RegionsPage' => 'RegionsPage'
    );

    private static $has_many = array(
        'Articles' => 'ArticlePage'
    );

    private static $summary_fields = array(
        'GridThumbnail' => '',
        'Title' => 'Title of region',
        'Description' => 'Short description',
    );

    public function getGridThumbnail()
    {
        if ($this->Photo()->exists()) {
            return $this->Photo()->setWidth(100);
        }

        return '(no image)';
    }

    public function getCMSFields()
    {
        $fields = FieldList::create(
            TextField::create('Title'),
            TextareaField::create('Description'),
            $uploader = UploadField::create('Photo')
        );

        $uploader->setFolderName('region-photos');
        $uploader->getValidator()->setAllowedExtensions(array(
            'png', 'jpg', 'jpeg', 'gif',
        ));

        return $fields;
    }

    public function Link()
    {
        return $this->RegionsPage()->link('show/' . $this->ID);
    }

    public function LinkingMode()
    {
        return Controller::curr()->getRequest()->param('ID') == $this->ID ? 'current' : 'link';
    }

    public function ArticleLink()
    {
        $page = ArticleHolder::get()->first();

        if ($page) {
            return $page->link('region/' . $this->ID);
        }
    }
}
