<?php

use phpDocumentor\Reflection\DocBlock\Description;

class TagData extends DataObject
{

    private static $db = array(
        'Title' => 'Varchar'
    );

    private static $belongs_many_many = array(
        'Articles' => 'ArticlePage',
    );

    private static $has_one = array(
        'ArticleHolder' => 'ArticleHolder'
    );

    public function getCMSFields()
    {
        $fields = FieldList::create(
            TextField::create('Title')->setTitle('Tag')->setDescription('Tag must be unique')
        );

        return $fields;
    }

    public function validate()
    {
        $result = parent::validate();

        if ($this->Title) {
            $this->Title = str_replace(' ', '-', $this->Title);
            $existingTitle = TagData::get()->filter('Title', $this->Title)->exclude('ID', $this->ID)->first();

            if ($existingTitle) {
                throw new ValidationException('Tags must be unique');
            }
        }
        return $result;
    }

    public function link()
    {
        return $this->ArticleHolder()->link('tag/' . $this->Title);
    }
}
