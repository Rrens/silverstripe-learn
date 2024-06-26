<?php

class ArticleCategoryData extends DataObject
{

    private static $db = array(
        'Title' => 'Varchar'
    );

    private static $has_one = array(
        'ArticleHolder' => 'ArticleHolder'
    );

    private static $belongs_many_many = array(
        'Articles' => 'ArticlePage',
    );

    public function getCMSFields()
    {
        return FieldList::create(
            TextField::create('Title')
        );
    }

    public function link()
    {
        return $this->ArticleHolder()->link('category/' . $this->Title);
    }
}
