<?php

class PropertySearchPage extends Page
{
}

class PropertySearchPage_Controller extends Page_Controller
{

    public function index(SS_HTTPRequest $request)
    {
        // echo '<pre>';
        $properties = Property::get()->limit(20);
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
                'Label' => '$Min. \$$minPrice',
                'RemoveLink' => HTTP::setGetVar('MinPrice', null)
            )));
            $properties = $properties->filter(array(
                'PricePerNight:GreaterThanOrEqual' => $minPrice
            ));
        }

        if ($maxPrice = $request->getVar('MaxPrice')) {
            $filters->push(ArrayData::create(array(
                'Label' => '$Max. \$$maxPrice',
                'RemoveLink' => HTTP::setGetVar('MaxPrice', null)
            )));
            $properties = $properties->filter(array(
                'PricePerNight:LessThanOrEqual' => $maxPrice
            ));
        }


        $paginateProperties = PaginatedList::create(
            $properties,
            $request
        )
            ->setPageLength(5)
            ->setPaginationGetVar('s');

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

    public function PropertySearchForm()
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
            'PropertySearchForm',
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
                    ->addExtraClass('form-control')
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
}
