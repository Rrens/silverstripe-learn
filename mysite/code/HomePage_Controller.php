<?php

class HomePage_Controller extends Page_Controller
{
    public function latestArticles($limit = null)
    {
        return ArticlePage::get()
            ->sort('Created', 'DESC')
            ->limit($limit);
    }

    public function featuredProperties()
    {
        return PropertyData::get()
            ->filter(array(
                'FeaturedOnHomepage' => true
            ))
            ->limit(6);
    }
}
