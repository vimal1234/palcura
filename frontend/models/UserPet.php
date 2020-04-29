<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;

class UserPet extends ActiveRecord {

    public $petDetailsErrors = array();

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user_pets}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'care_note'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'care_note' => Yii::t('yii', 'Care Note'),
            'type' => Yii::t('yii', 'Pet Type'),
        ];
    }

    public function save_pets($type, $name, $careNote, $userId) {
        $this->name = $name;
        $this->type = $type;
        $this->user_id = $userId;
        $this->care_note = $careNote;
        if ($this->save()) {
            return array(
                'id' => $this->id,
                'name' => $this->name,
                'type' => $this->type,
                'type_name' => Yii::$app->commonmethod->getPetTypeById($this->type),
                'care_note' => $this->care_note
            );
        } else {
            return '';
        }
    }

    public function update_pet($id, $careNote) {
        Yii::$app->db->createCommand()->update('user_pets', ['care_note' => $careNote], 'id = ' . $id)->execute();
        $pet = $this->find()->where(['id' => $id])->One();
        return array(
            'id' => $pet->id,
            'name' => $pet->name,
            'type' => $pet->type,
            'type_name' => Yii::$app->commonmethod->getPetTypeById($pet->type),
            'care_note' => $pet->care_note
        );
    }

    /**
     * Function to get pets from database on the bases of passed comma separated 1ds of pets
     * 
     */
    public function getPets($ids) {
        $pets = array();
        $ids = (is_array($ids)) ? $ids : explode(",", $ids);
        foreach ($ids as $userPetId) {
            $pets[] = $this->findOne(['id' => $userPetId]);
        }
        return $pets;
    }

    public function getPetType() {
        return Yii::$app->commonmethod->getPetTypeById($this->type);
    }
    
    public function get_care_note($bookingId){
        return Yii::$app->commonmethod->get_booking_care_note($this->id, $bookingId);
    }

    // function to validate a pet 
    public function validatePets($petDetails) {
        $this->petDetailsErrors = array();
        if (empty($petDetails)) {
            return true;
        }
        foreach ($petDetails['type'] as $key => $type) {
            // Skiping zeroth (0) key because we have a hidden empty html on dom to clone
            if ($key != 0) {
                if (trim($type) == "") {
                    $this->petDetailsErrors['type'][$key] = 'Pal type cannot be blank.';
                }
                if (trim($petDetails['name'][$key]) == "") {
                    $this->petDetailsErrors['name'][$key] = 'Pal name cannot be blank.';
                }
                if (trim($petDetails['care_note'][$key]) == "") {
                    $this->petDetailsErrors['care_note'][$key] = 'Care note cannot be blank.';
                }
            }
        }
//        echo "<pre>";
//        print_r($this->petDetailsErrors);
//        die;
        return (count($this->petDetailsErrors) > 0) ? false : true;
    }
    
    

}
