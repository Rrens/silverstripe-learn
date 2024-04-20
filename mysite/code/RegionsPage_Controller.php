<?php
class RegionsPage_Controller extends Page_Controller
{
    private static $allowed_actions = array(
        'show',
        'test'
    );

    public function test()
    {
        die('it works');
    }

    public function show(SS_HTTPRequest $request)
    {
        $region = RegionData::get()->filter(['Slug' => $request->param('ID')])->first();
        if (!$region) {
            return $this->httpError(404, 'That region could not be found');
        }
        return array(
            'Region' => $region,
            'Title' => $region->Title
        );
    }
}
