<?php

class ArticleHolder_Controller extends Page_Controller
{
    private static $allowed_actions = array(
        'category',
        'region',
        'date',
        'tagsData',
        'tag'
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
        $category = ArticleCategoryData::get()->filter(array(
            'Title' => $request->param('ID')
        ))->first();

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

        $region = RegionData::get()->filter(array(
            'Slug' => $request->param('ID')
        ))->first();

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

    public function archiveDates()
    {
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

                $list->push(ArrayData::create(array(
                    'Year' => $year,
                    'MonthName' => $monthName,
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


    public function tagsData()
    {
        $tags = TagData::get();
        $list = ArrayList::create();

        if ($tags) {
            foreach ($tags as $tag) {
                $list->push(ArrayData::create(array(
                    'Title' => $tag->Title,
                    'Link' => $this->Link("tag/{$tag->Title}"),
                    'ArticleCount' => ArticlePage::get()->filter(array(
                        'Tags.Title' => $tag->Title
                    ))->Count()
                )));
            }
        }

        return $list;
    }

    public function tag(SS_HTTPRequest $request)
    {

        $tag = TagData::get()->filter(array(
            'TItle' => $request->param('ID')
        ))->first();

        if (!$tag) {
            return $this->httpError(404, 'That Tag was not found');
        }

        $this->articleList = $this->articleList->filter(array(
            'Tags.ID' => $tag->ID
        ));

        return array(
            'SelectedTag' => $tag,
        );
    }

    public function paginatedArticles($num = 2)
    {
        return PaginatedList::create(
            $this->articleList,
            $this->getRequest()
        )->setPageLength($num);
    }
}
