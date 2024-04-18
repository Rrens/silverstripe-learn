<?php

class ArticleHolder extends Page
{
    private static $has_many = array(
        'Categories' => 'ArticleCategory'
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

        return $fields;
    }

    public function Regions()
    {
        // echo '<pre>';
        $page = RegionsPage::get()->first();
        // echo print_r($page->Regions());
        // foreach ($page as $item) {
        //     // echo print_r($item);
        // }
        // die($page);

        if ($page) {
            return $page->Regions();
        }
    }
}

class ArticleHolder_Controller extends Page_Controller
{
    private static $allowed_actions = array(
        'category',
        'region',
        'date'
    );

    public function init()
    {
        parent::init();

        $this->articleList = ArticlePage::get()->filter(array(
            'ParentID' => $this->ID
        ))->sort('Date DESC');
    }

    public function category(SS_HTTPRequest $request)
    {
        $category = ArticleCategory::get()->byID(
            $request->param('ID')
        );

        if (!$category) {
            return $this->httpError(404, 'That Category was not found');
        }

        $this->articleList = $this->articleList->filter(array(
            'Categories.ID' => $category->ID
        ));

        return array(
            'SelectedCategory' => $category
        );
    }

    public function region(SS_HTTPRequest $request)
    {
        // echo '<pre>';
        $region = Region::get()->byID(
            $request->param('ID')
        );

        if (!$region) {
            return $this->httpError(404, 'That Region was not found');
        }

        $this->articleList = $this->articleList->filter(array(
            'Region.ID' => $region->ID
        ));

        // print_r($region);
        // die();

        return array(
            'SelectedRegion' => $region
        );
    }

    public function ArchiveDates()
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
                list($year, $MonthName, $monthNumber) = explode('_', $record['DateString']);

                $list->push(ArrayData::create(array(
                    'Year' => $year,
                    'MonthName' => $MonthName,
                    'MonthNumber' => $monthNumber,
                    'Link' => $this->Link("date/$year/$monthNumber"),
                    'ArticleCount' => ArticlePage::get()->where("
                    DATE_FORMAT(`Date`,'%Y%m') = '{$year}{$monthNumber}'
                    AND ParentID = {$this->ID}
                    ")->Count()
                )));
            }
        }

        // foreach ($list as $item) {
        //     print_r($item);
        // }
        return $list;
    }

    public function date(SS_HTTPRequest $request)
    {
        $year = $request->param('ID');
        $month = $request->param('OtherID');

        if (!$year) return $this->httpError(404);

        $startDate = $month ? "{$year}-{$month}-01" : "{$year}-01-01";

        if (strtotime($startDate) === false) {
            return $this->httpError(404, 'Invalid Date');
        }

        $adder = $month ? '+1 month' : '+1 year';
        $endDate = date('Y-m-d', strtotime(
            $adder,
            strtotime($startDate)
        ));

        $this->articleList = $this->articleList->filter(array(
            'Date:GreaterThanOrEqual' => $startDate,
            'Date:LessThan' => $endDate
        ));

        return array(
            'StartDate' => DBField::create_field('SS_DateTime', $startDate),
            'EndDate' => DBField::create_field('SS_DateTime', $endDate)
        );
    }

    public function PaginatedArticles($num = 2)
    {
        return PaginatedList::create(
            $this->articleList,
            $this->getRequest()
        )->setPageLength($num);
    }
}
