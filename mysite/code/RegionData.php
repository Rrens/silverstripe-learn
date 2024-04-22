<?php

class RegionData extends DataObject
{
    private static $db = array(
        'Title' => 'Varchar',
        'Description' => 'Text',
        'Slug' => 'Varchar',
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

    public function getCMSFields()
    {
        $fields = FieldList::create(
            TextField::create('Title'),
            TextField::create('Slug')->setTitle('Slug')->setDescription('Slug Should be unique'),
            TextareaField::create('Description'),
            $uploader = UploadField::create('Photo')
        );


        $uploader->setFolderName('region-photos');
        $uploader->getValidator()->setAllowedExtensions(array(
            'png', 'jpg', 'jpeg', 'gif',
        ));

        return $fields;
    }

    public function validate()
    {
        $result = parent::validate();

        if ($this->Slug) {
            $this->Slug = GeneratorGlobalClass::generate($this->Slug);
            $existingSlug = RegionData::get()->filter('Slug', $this->Slug)->exclude('ID', $this->ID)->first();
            if ($existingSlug) {
                throw new ValidationException('Slug must be unique');
            }
            // $this->Slug->setValue(str_replace(' ', '-', $this->Slug));
            // print_r($result);

            return $result;
        }
    }

    public function GetGridThumbnail()
    {
        if ($this->Photo()->exists()) {
            return $this->Photo()->setWidth(100);
        }

        return '(no image)';
    }

    public function link()
    {
        return $this->RegionsPage()->link('show/' . strtolower($this->Slug));
    }

    public function linkingMode()
    {
        return Controller::curr()->getRequest()->param('ID') == $this->ID ? 'current' : 'link';
    }

    public function articleLink()
    {
        $page = ArticleHolder::get()->first();

        if ($page) {
            return $page->link('region/' . $this->Slug);
        }
    }
}
