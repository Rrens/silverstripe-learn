<?php

class HomePage extends Page
{
}

class HomePage_Controller extends Page_Controller
{
    public function LatestArticles($limit = null)
    {
        return ArticlePage::get()
            ->sort('Created', 'DESC')
            ->limit($limit);
    }

    public function FeaturedProperties()
    {
        return Property::get()
            ->filter(array(
                'FeaturedOnHomepage' => true
            ))
            ->limit(6);
    }
}
