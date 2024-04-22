<?php

class PropertyData extends DataObject
{
    private static $db = array(
        'Title' => 'Varchar',
        'PricePerNight' => 'Currency',
        'Bedrooms' => 'Int',
        'Bathrooms' => 'Int',
        'FeaturedOnHomepage' => 'Boolean',
        'AvailableStart' => 'Date',
        'AvailableEnd' => 'Date',
        'Description' => 'Text',
        'Address' =>  'Text',
        // 'PropertyType' => 'Varchar',
        'TransactionType' => 'Varchar',
        'City' => 'Varchar',
        'Slug' => 'Varchar',
    );

    private static $has_one = array(
        'Region' => 'RegionData',
        'PrimaryPhoto' => 'Image',
        'PropertySearchPage' => 'PropertySearchPage',
        'PropertyType' => 'PropertyTypeData',
    );

    private static $many_many = array(
        'Facilities' => 'FacilityData',
        'Agents' => 'AgentData',
    );

    private static $summary_fields = array(
        'Title' => 'Title',
        'Region.Title' => 'RegionData',
        'PricePerNight.Nice' => 'Price',
        'FeaturedOnHomepage.Nice' => 'Featured?'
    );

    private static $can_be_root = false;

    public function searchableFields()
    {
        return array(
            'Title' => array(
                'filter' => 'PartialMatchFilter',
                'title' => 'Title',
                'field' => 'TextField'
            ),
            'RegionID' => array(
                'filter' => 'ExactMatchFilter',
                'title' => 'Region',
                'field' => DropdownField::create('RegionID')
                    ->setSource(
                        RegionData::get()->map('ID', 'Title')
                    )
                    ->setEmptyString('-- Any region --')
            ),
            'FeaturedOnHomepage' => array(
                'filter' => 'ExactMatchFilter',
                'title' => 'Only featured'
            )
        );
    }

    public function getCMSFields()
    {

        // $fields = parent::getCMSFields();
        $fields = FieldList::create(TabSet::create('Root'));

        $fields->addFieldsToTab('Root.Main', array(
            TextField::create('Title'),
            TextField::create('Slug'),
            TextareaField::create('Description'),
            // DropdownField::create('PropertyType', 'Property Type', array(
            //     'House' => 'House',
            //     'Apartment' => 'Apartment',
            // ))->setEmptyString('-- any property --'),
            DropdownField::create('PropertyTypeID', 'Property Type')
                ->setSource(PropertyTypeData::get()->map('ID', 'Title'))
                ->setEmptyString('-- any property --'),
            DropdownField::create('TransactionType', 'Transaction Type', array(
                'Sell' => 'Sell',
                'Rent' => 'Rent'
            ))->setEmptyString('-- any --'),
            TextField::create('City'),
            TextareaField::create('Address'),
            CurrencyField::create('PricePerNight', 'Price (per night)'),
            DropdownField::create('Bedrooms')
                ->setSource(ArrayLib::valuekey(range(1, 10))),
            DropdownField::create('Bathrooms')
                ->setSource(ArrayLib::valuekey(range(1, 10))),
            DropdownField::create('RegionID', 'Region')
                ->setSource(RegionData::get()->map('ID', 'Title'))
                ->setEmptyString('-- Select a region --'),
            DropdownField::create('AgentsID', 'Agent')
                ->setSource(AgentData::get()->map('ID', 'Title'))
                ->setEmptyString('-- Select a agent --'),
            CheckboxField::create('FeaturedOnHomepage', 'Feature on homepage')
        ));

        $fields->addFieldsToTab('Root.Photos', $upload = UploadField::create(
            'PrimaryPhoto',
            'Primary photo'
        ));

        $fields->addFieldsToTab('Root.Facilites', CheckboxSetField::create(
            'Facilities',
            'Selected Facility',
            FacilityData::get()->map('ID', 'Title')
        ));

        $fields->addFieldsToTab('Root.Agents', CheckboxSetField::create(
            'Agents',
            'Agent',
            AgentData::get()->map('ID', 'Name')
        ));

        $upload->getValidator()->setAllowedExtensions(array(
            'png', 'jpg', 'jpeg', 'gif',
        ));

        $upload->setFolderName('property-photos');

        return $fields;
    }

    public function validate()
    {
        $result = parent::validate();

        if ($this->Slug) {
            $this->Slug = GeneratorGlobalClass::generate($this->Slug);
            $existingSlug = PropertyData::get()->filter('Slug', $this->Slug)
                ->exclude('ID', $this->ID)->first();

            if ($existingSlug) {
                throw new ValidationException('Slug must be unique');
            }
        }

        return $result;
    }

    public function getLinkProperty()
    {
        $page = PropertySearchPage::get()->first();

        return $page->Link('property/' . $this->Slug);
    }
}
