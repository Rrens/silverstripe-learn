<?php

class ArticleHolder extends Page
{
    private static $has_many = array(
        'Categories' => 'ArticleCategoryData',
        'Tags' => 'TagData',
    );


    private static $allowed_children = array(
        'ArticlePage'
    );

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->addFieldToTab('Root.Categories', GridField::create(
            'Categories',
            'Article categories',
            $this->Categories(),
            GridFieldConfig_RecordEditor::create()
        ));

        $fields->addFieldsToTab('Root.Tags', GridField::create(
            'Tags',
            'Article tags',
            $this->Tags(),
            GridFieldConfig_RecordEditor::create()
        ));

        // print_r($this->Tags());

        return $fields;
    }

    // public function regions()
    // {
    //     // echo '<pre>';
    //     $page = RegionsPage::get()->first();
    //     // echo print_r($page->Regions());
    //     // foreach ($page as $item) {
    //     //     // echo print_r($item);
    //     // }
    //     // die($page);

    //     if ($page) {
    //         return $page->regions();
    //     }
    // }
}
