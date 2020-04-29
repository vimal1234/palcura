<?php

namespace backend\models\feedback;

use backend\models\feedback\UpdateFeedback;
use yii\base\Model;
use Yii;

/**
 * UpdateFeedback
 */
class UpdateFeedback extends Feedback {

    public $comment;
    public $starrating;
    public $status;


    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'feedback_rating';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['comment', 'starrating'], 'required'],
            [['comment'], 'string', 'max' => 2000],
            ['starrating', 'string', 'max'=> 1],
            ['starrating', 'integer', 'min'=> 0, 'max' => 5]

        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id]);
    }

    /**
     * update user.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function UpdateFeedback($id) {
        if (!$this->validate()) {

            return null;
        }

        $feed = Feedback::findOne(['id' => $id]);
        $slug = str_replace(' ', '-', $this->comment);
        $feed->date_time = date('y-m-d h:i:s');
        $feed->slug = $slug;
        $feed->comment = $this->comment;
        $feed->starrating = $this->starrating;
        #$feed->status = $this->status;

        $feed->save();
        return $feed->save() ? $feed : null;
    }

}
