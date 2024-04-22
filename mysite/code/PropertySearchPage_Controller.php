<?php
class PropertySearchPage_Controller extends Page_Controller
{

    private static $allowed_actions = array(
        'property',
        'test'
    );

    public function index(SS_HTTPRequest $request)
    {
        // echo '<pre>';
        $properties = PropertyData::get()->limit(20);
        $filters = ArrayList::create();

        if ($search = $request->getVar('Keywords')) {

            $filters->push(ArrayData::create(array(
                'Label' => "Keywords: '$search'",
                'RemoveLink' => HTTP::setGetVar('Keywords', null)
            )));

            $properties = $properties->filter(array(
                'Title:PartialMatch' => $search
            ));
        }

        if ($arrival = $request->getVar('ArrivalDate')) {
            $arrivalStamp = strtotime($arrival);
            $nightAdder = '+' . $request->getVar('Nights') . ' days';
            $startDate = date('Y-m-d', $arrivalStamp);
            $endDate = date('Y-m-d', strtotime($nightAdder, $arrivalStamp));

            $properties = $properties->filter(array(
                'AvailableStart:LessThanOrEqual' => $startDate,
                'AvailableEnd:GreaterThanOrEqual' => $endDate
            ));
        }

        if ($bedrooms = $request->getVar('Bedrooms')) {
            $filters->push(ArrayData::create(
                array(
                    'Label' => "$bedrooms bedrooms",
                    'RemoveLink' => HTTP::setGetVar('Bedrooms', null)
                )
            ));
            $properties = $properties->filter(array(
                'Bedrooms:GreaterThanOrEqual' => $bedrooms
            ));
        }

        if ($bathrooms = $request->getVar('Bathrooms')) {
            $filters->push(ArrayData::create(array(
                'Label' => "$bathrooms bathrooms",
                'RemoveLink' => HTTP::setGetVar('Bathrooms', null)
            )));
            $properties = $properties->filter(array(
                'Bedrooms:GreaterThanOrEqual' => $bathrooms
            ));
        }

        if ($minPrice = $request->getVar('MinPrice')) {
            $filters->push(ArrayData::create(array(
                'Label' => '$Min' . "\$$minPrice",
                'RemoveLink' => HTTP::setGetVar('MinPrice', null)
            )));
            $properties = $properties->filter(array(
                'PricePerNight:GreaterThanOrEqual' => $minPrice
            ));
        }

        if ($maxPrice = $request->getVar('MaxPrice')) {
            $filters->push(ArrayData::create(array(
                'Label' => '$Max' . "\$$maxPrice",
                'RemoveLink' => HTTP::setGetVar('MaxPrice', null)
            )));
            $properties = $properties->filter(array(
                'PricePerNight:LessThanOrEqual' => $maxPrice
            ));
        }

        if ($propertyType = $request->getVar('PropertyType')) {
            $filters->push(ArrayData::create(array(
                'Label' => 'Property Type',
                'RemoveLink' => HTTP::setGetVar('PropertyType', null)
            )));
            $properties = $properties->filter(array(
                'PropertyTypeID:PartialMatch' => $propertyType,
            ));
        }

        if ($transactionType = $request->getVar('TransactionType')) {
            $filters->push(ArrayData::create(array(
                'Label' => 'Transaction Type',
                'RemoveLink' => HTTP::setGetVar('TransactionType', null)
            )));

            $properties =  $properties->filter(array(
                'TransactionType:PartialMatch' => $transactionType,
            ));
        }

        $paginateProperties = PaginatedList::create(
            $properties,
            $request
        )
            ->setPageLength(5)
            ->setPaginationGetVar('s');

        // $arrayList = ArrayList::create();
        // foreach ($paginateProperties as $item) {
        //     // $item->linkURL = $this->Link("property/$item->Title");
        //     $arrayList->push(ArrayData::create(array(
        //         'Title' => $item->Title,
        //         'Link' => $this->Link("property/$item->Title"),
        //         'PricePerNight' => $item->PricePerNight,
        //         'Bedrooms' => $item->Bedrooms,
        //         'Bathrooms' => $item->Bathrooms,
        //         'AvailableStart' => $item->AvailableStart,
        //         'AvailableEnd' => $item->AvailableEnd,
        //         'Description' => $item->Description,
        //         // 'Address' => $item->Address,
        //         // 'PropertyType' => $item->PropertyType,
        //         // 'TransactionType' => $item->TransactionType,
        //         // 'FeaturedOnHomepage' => $item->FeaturedOnHomepage
        //         'Photo' => $item->Photo

        //     )));
        //     print_r($$arrayList);
        // }


        $data =  array(
            'Results' => $paginateProperties,
            'ActiveFilters' => $filters
        );

        if ($request->isAjax()) {
            return $this->customise($data)
                ->renderWith('PropertySearchResult');
        }
        return $data;
    }

    public function propertySearchForm()
    {
        $nights = array();
        foreach (range(1, 14) as $i) {
            $nights[$i] = "$i night" . (($i > 1) ? 's' : '');
        }
        $prices = array();
        foreach (range(100, 1000, 10) as $i) {
            $prices[$i] = '$' . $i;
        }

        $form = Form::create(
            $this,
            'propertySearchForm',
            FieldList::create(
                TextField::create('Keywords')
                    ->setAttribute('placeholder', 'City, State, Country, etc...')
                    ->addExtraClass('form-control'),
                // TextField::create('ArrivalDate', 'Arrive on...')
                //     ->setAttribute('data-datepicker', true)
                //     ->setAttribute('data-date-format', 'DD-MM-YYYY')
                //     ->addExtraClass('form-control'),
                // DropdownField::create('Nights', 'Stay for...')
                //     ->setSource($nights)
                //     ->addExtraClass('form-control'),
                DropdownField::create('Bedrooms')
                    ->setSource(ArrayLib::valuekey(range(1, 5)))
                    // ->setValue($this->request->getVar('MinBedRooms'))
                    ->addExtraClass('form-control'),
                DropdownField::create('Bathrooms')
                    ->setSource(ArrayLib::valuekey(range(1, 5)))
                    ->addExtraClass('form-control'),
                DropdownField::create('MinPrice', 'Min. price')
                    ->setEmptyString('-- any --')
                    ->setSource($prices)
                    ->addExtraClass('form-control'),
                DropdownField::create('MaxPrice', 'Max. price')
                    ->setEmptyString('-- any --')
                    ->setSource($prices)
                    ->addExtraClass('form-control'),
                DropdownField::create('PropertyType', 'Property Type')
                    ->setEmptyString('-- choose property type --')
                    ->setSource(PropertyTypeData::get()->map('ID', 'Title'))
                    ->addExtraClass('form-control'),
                DropdownField::create('TransactionType', 'Transaction Type')
                    ->setEmptyString('-- choose transaction type --')
                    ->addExtraClass('form-control')
                    ->setSource(array(
                        'Sell' => 'Sell',
                        'Rent' => 'Rent',
                    )),
            ),
            FieldList::create(
                FormAction::create('doPropertySearch', 'Search')
                    ->addExtraClass('btn-lg btn-fullcolor')
            )
        );

        $form->setFormMethod('GET')
            ->setFormAction($this->link())
            ->disableSecurityToken()
            ->loadDataFrom($this->request->getVars());

        return $form;
    }

    public function property(SS_HTTPRequest $request)
    {
        $property = PropertyData::get()->filter(array(
            'Slug' => $request->param('ID'),
        ))->first();

        $agent = AgentData::get()->filter(array(
            'ID' => $property->AgentsID
        ))->first();

        $propertyType = PropertyTypeData::get()->filter(array(
            'ID' => $property->PropertyTypeID
        ))->first();

        if (!$property) {
            return $this->httpError(404, 'That region could not be found');
        }

        $data = array(
            'Property' => $property,
            'Title' => $property->Title,
            'Agent' => $agent,
            'PropertyType' => $propertyType,
        );



        if ($request->isAjax()) {
            return $this->renderWith('PropertySearchPage_property');
        }
        // if ($request->isAjax()) {
        //     return $this->customise($data)
        //         ->renderWith('PropertySearchResult');
        // }

        return $data;
    }

    public function test()
    {
        die('test');
    }
}
