<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "jobs".
 *
 * @property integer $job_id
 * @property integer $job_type_id
 * @property string $job_starting_date
 * @property integer $project_id
 * @property string $job_quote_return_deadline_date
 * @property string $job_completion_deadline_date
 * @property string $job_material_included
 * @property string $job_budget_estimated_job_price_range
 * @property string $job_hire_out_of_state_subcontractor
 * @property string $job_posting_identity
 * @property string $job_upload_blueprint_document
 */
class Cms extends \yii\db\ActiveRecord
{
    
    public $term;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [];
    }
    
    /**
     * Get CMS page by id
     * @param integer $id
     * @return array
     */
    public function getCmsPage($id) {
        $result = Cms::find()->where(['slug'=>$id])->all();
        return $result;
    }
}
