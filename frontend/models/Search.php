<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * Search Model
 */
class Search extends Model
{
  	public $service_type;
  	public $pet_weight;
  	public $zip;
  	public $date_from;
  	public $date_to;
  	public $selected_pal;
  	public $rate_per_night;
  	public $no_of_pals;	
	/**
     * @inheritdoc
     */
        
    /**
     * @rules
     */
    public function rules()
    {
        return [      
       	[['service_type','pet_weight','zip','date_from','date_to','selected_pal','rate_per_night'],'safe'],
        ];
    }
	
	public function oneOfThree($attribute, $params)
  {
      if (!$this->phone1 || !$this->phone2 || !$this->phone3)
          $this->addError($attribute, 'Atleast one field is required.');
  }

	/**
     * @attributeLabels
     */
    public function attributeLabels()
    {
        return [
        'service_type' => 'Service Offering',
        'pet_weight' => 'Pet weight',
        'zip'		=> 'Zip code/Address',
        'date_from' => 'From',
        'date_to' 	=> 'To',
        'selected_pal' => 'Pal type',
        'rate_per_night' => 'Rate per night',
        'no_of_pals' => 'How many pals you need care for?',
        ];
    }
   
} 
