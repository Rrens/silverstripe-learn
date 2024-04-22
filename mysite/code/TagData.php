<?php


class TagData extends DataObject
{

    private static $db = array(
        'Title' => 'Varchar',
        'Slug' => 'Varchar',
        'Description' => 'Varchar',
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
            TextField::create('Title')->setTitle('Tag'),
            TextField::create('Slug')->setTitle('Slug')->setDescription('Slug Tag must be unique')
        );

        return $fields;
    }

    public function validate()
    {
        $result = parent::validate();

        if ($this->Slug) {
            // $this->Slug = str_replace(' ', '-', $this->Slug);
            $this->Slug = GeneratorGlobalClass::generate($this->Slug);
            $existingSlug = TagData::get()->filter('Slug', $this->Slug)->exclude('ID', $this->ID)->first();

            if ($existingSlug) {
                throw new ValidationException('Slug must be unique');
            }
        }
        return $result;
    }

    public function link()
    {
        return $this->ArticleHolder()->link('tag/' . $this->Slug);
    }
}
