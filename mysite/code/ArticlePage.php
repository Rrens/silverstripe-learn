<?php
class ArticlePage extends Page
{
    private static $db = array(
        'Date' => 'Date',
        'Teaser' => 'Text',
        'Author' => 'Varchar',
        // 'Content' => 'Text',
    );

    private static $has_one = array(
        'Photo' => 'Image',
        'Brochure' => 'File',
        'Region' => 'RegionData',
    );

    private static $many_many = array(
        'Categories' => 'ArticleCategoryData',
        'Tags' => 'TagData'
    );

    private static $has_many = array(
        'Comments' => 'ArticleCommentData'
    );


    private static $can_be_root = false;


    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->addFieldToTab(
            'Root.Main',
            DateField::create('Date', 'Date of article')
                ->setConfig('showcalendar', true)
                ->setConfig('dateformat', 'd MMMM yyyy'),
            'Content'
        );
        $fields->addFieldToTab('Root.Main', TextareaField::create('Teaser'), 'Content');
        $fields->addFieldToTab('Root.Main', TextField::create('Author', 'Author of article'), 'Content');
        $fields->addFieldToTab('Root.Attachments', $photo = UploadField::create('Photo'));
        $fields->addFieldToTab('Root.Attachments', $brochure = UploadField::create('Brochure', 'Travel brochure, optional (PDF only)'));

        $photo->getValidator()->setAllowedExtensions(array('png', 'gif', 'jpg', 'jpeg'));
        $photo->setFolderName('travel-photos');

        $brochure->getValidator()->setAllowedExtensions(array('pdf'));
        $brochure->setFolderName('travel-brochures');

        $fields->addFieldToTab('Root.Categories', CheckboxSetField::create(
            'Categories',
            'Selected Categories',
            $this->Parent()->Categories()->map('ID', 'Title')
        ));

        $fields->addFieldsToTab('Root.Tags', CheckboxSetField::create(
            'Tags',
            'Selected Tags',
            $this->parent()->Tags()->map('ID', 'Title')
        ));

        $fields->addFieldsToTab('Root.Main', DropdownField::create(
            'RegionID',
            'Region',
            RegionData::get()->map('ID', 'Title')
        )->setEmptyString('-- None --'), 'Content');

        return $fields;
    }

    public function archiveDates()
    {
        // echo '<pre>';
        $list = ArrayList::create();
        $stage = Versioned::current_stage();

        $query = new SQLQuery(array());
        $query->selectField("DATE_FORMAT(`Date`,'%Y_%M_%m')", "DateString")
            ->setFrom("ArticlePage_{$stage}")
            ->setOrderBy("DateString", "ASC")
            // ->setWhere("Date", 'NOT NULL')
            ->setDistinct(true);
        // die($query);
        $result = $query->execute();

        if ($result) {

            while ($record = $result->nextRecord()) {
                list($year, $monthName, $monthNumber) = explode('_', $record['DateString']);
                $prevLink = Controller::join_links($this->Link(), "../date/$year/$monthNumber");

                $list->push(ArrayData::create(array(
                    'Year' => $year,
                    'MonthName' => $monthName,
                    'MonthNumber' => $monthNumber,
                    'Link' => $prevLink,
                    'ArticleCount' => ArticlePage::get()->where("
                    DATE_FORMAT(`Date`,'%Y%m') = '{$year}{$monthNumber}'
                    ")->Count()
                )));
                // print_r(ArticlePage::get()->where("
                //     DATE_FORMAT(`Date`,'%Y%m') = '{$year}{$monthNumber}'
                //     AND ParentID = {$this->ID}
                //     "));
            }
        }

        // foreach ($list as $item) {
        //     print_r($item);
        // }
        return $list;
    }

    public function tagsList()
    {
        $tag = TagData::get();

        return $tag;
    }

    public function categoriesList()
    {
        if ($this->Categories()->exists()) {
            return implode(', ', $this->Categories()->column('Title'));
        }
    }
}
