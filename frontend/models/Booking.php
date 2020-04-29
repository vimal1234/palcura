<?php

namespace frontend\models;

use Yii;

/**
 * Booking model
 *
 * @property integer $id
 * @property string $Inerests
 */
class Booking extends \yii\db\ActiveRecord {

    public $number_of_pets;
    public $services;
//    public $description;
    public $booking_type;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'booking';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'booking_from_date', 'booking_to_date', 'booking_type'], 'required'],
            ['booking_from_date', 'validateDates'],
            [['number_of_pets', 'services'], 'required', 'when' => function($model) {
                    return $model->booking_type == OWNER;
                }, 'whenClient' => "function (attribute, value) { return $('#booking-booking_type').val() == '1'; }"],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'booked_from_date' => Yii::t('yii', 'From date'),
            'booked_to_date' => Yii::t('yii', 'To date'),
            'name' => Yii::t('yii', 'Booking name'),
        ];
    }

    /**
     * validateDates
     * @param N/A
     * @return array
     */
    public function validateDates() {
        if (strtotime($this->booking_from_date) > strtotime($this->booking_to_date)) {
            $this->addError('booking_from_date', Yii::t('yii', 'Please select a valid date range'));
            $this->addError('booking_to_date', Yii::t('yii', 'Please select a valid date range'));
        }
    }

    /**
     * isBooked
     * @param $id
     * @return array
     */
    //~ public function isBooked($id) {
    //~ $fromDate = $this->booked_from_date;
    //~ $toDate = $this->booked_to_date;
    //~ $WHERE = "booking_status = '1' AND guyde_user_id = $id AND (CAST('$fromDate' AS DATE) BETWEEN booking.booked_from_date and booking.booked_to_date OR CAST('$toDate' AS DATE) BETWEEN booking.booked_from_date and booking.booked_to_date)";
    //~ $query = new yii\db\Query;
    //~ $query->select('booking_id')
    //~ ->from('booking')
    //~ ->where($WHERE);
    //~ $Result = $query->createCommand()->queryAll();
    //~ if (empty($Result))
    //~ return false;
    //~ else
    //~ return true;
    //~ }

    /**
     * getCustomer
     * @param N/A
     * @return array
     */
    //~ public function getCustomer() {
    //~ return $this->hasOne(\backend\models\users\Users::className(), ['id' => 'customer_user_id']);
    //~ }

    /**
     * getGuyde
     * @param N/A
     * @return array
     */
    public function getGuyde() {
        return $this->hasOne(\backend\models\users\Users::className(), ['id' => 'guyde_user_id']);
    }

    public function getMember() {
        return $this->hasOne(\backend\models\users\Users::className(), ['id' => 'guyde_user_id'])->from(['uto' => \backend\models\users\Users::tableName()]);
    }

    /**
     * getPaidStatus
     * @param N/A
     * @return array
     */
    public function getPaidStatus() {
        if ($this->adminPaymentStatus == '0')
            $status = 'Pending';
        else if ($this->adminPaymentStatus == '1')
            $status = 'Paid';
        else if ($this->adminPaymentStatus == '2')
            $status = 'Unpaid';

        return $status;
    }

    public function saveBookingData($data) {
        $model = new Booking;
        $model->name = $data['booking_name'];
        $model->pet_sitter_id = $data['pet_sitter_id'];
        $model->pet_owner_id = $data['pet_owner_id'];
        $model->booking_from_date = $data['booking_from_date'];
        $model->booking_to_date = $data['booking_to_date'];
        $model->amount = $data['booking_amount'];
        $model->admin_fee = $data['booking_admin_fee'];
        $model->reward_points = $data['reward_points'];
        $model->booking_credits = $data['booking_credits'];
        $model->booking_status = ACTIVE;
        $model->completed = PENDING;
        $model->cancelled_by = PENDING;
        $model->status = ACTIVE;
        $model->payment_status = ACTIVE;
        $model->date_created = date('Y-m-d H:i:s');
        return $model->save();
    }

    public function confirmbooking($bookingId, $data) {
        // $session = Yii::$app->session;
        // $usertype = $session->get('loggedinusertype'); 					
        $connection = \Yii::$app->db;
        $model = $connection->createCommand('UPDATE booking SET booking_status = "1", amount="' . $data['booking_price'] . '" , admin_fee="' . $data['admin_fee'] . '",booking_credits="' . $data['sitter_revenue'] . '",in_payment_transaction_fee="' . $data['in_payment_transaction_fee'] . '",out_payment_transaction_fee="' . $data['out_payment_transaction_fee'] . '",palcura_revenue="' . $data['palcura_revenue'] . '" WHERE id =' . $bookingId);
        if ($model->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function rejectbooking($bookingId) {
        $connection = \Yii::$app->db;
        $model = $connection->createCommand('UPDATE booking SET booking_status = "2", booking.cancelled_by="1" WHERE id =' . $bookingId);
        if ($model->execute()) {
            return true;
        } else {
            return false;
        }
    }

}

?>
