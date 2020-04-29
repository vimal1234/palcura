<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;

class BookingCareNote extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%booking_care_notes}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['booking_id', 'user_pet_id', 'care_note'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'care_note' => Yii::t('yii', 'Care Note')
        ];
    }

    public function save_care_note($bookingId, $careNote, $userPetId) {
        $this->care_note = $careNote;
        $this->booking_id = $bookingId;
        $this->user_pet_id = $userPetId;
        if ($this->save()) {
//            return array(
//                'id' => $this->id,
//                'name' => $this->name,
//                'type' => $this->type,
//                'type_name' => Yii::$app->commonmethod->getPetTypeById($this->type),
//                'care_note' => $this->care_note
//            );
        } else {
            return '';
        }
    }

}
