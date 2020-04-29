<?php

namespace backend\models\page;

use backend\models\page\UpdatePage;
use yii\base\Model;
use Yii;

/**
 * Page
 */
class UpdatePage extends Page {

    public $pageType;
    public $pageContent;
    public $pageName;
    public $pageTitle;
    public $metaTitle;
    public $metaKeyword;
    public $metaDescriptions;

    /**
     * @tableName
     */
    public static function tableName() {
        return 'page';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['pageType', 'pageContent', 'image'], 'string'],
            [['pageName', 'pageTitle', 'pageContent'], 'required'],
            ['pageName', 'match', 'pattern' => '/^[a-z ]+$/i', 'message' => 'Page Name only accepts alphabets and space.'],
            [['pageName', 'pageTitle'], 'string', 'max' => 40],
            [['metaTitle', 'metaKeyword'], 'string', 'max' => 100],
            [['metaDescriptions'], 'string', 'max' => 180]
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
    public function updatePage($id) {
        if (!$this->validate()) {
            return null;
        }

        $page = Page::findOne(['id' => $id]);
        $slug = str_replace(' ', '-', $this->pageName);
        $page->pageDateCreated = date('y-m-d h:i:s');
        $page->slug = $slug;
        $page->pageName = $this->pageName;
        $page->pageTitle = $this->pageTitle;
        $page->pageType = $this->pageType;
        $page->metaTitle = $this->metaTitle;
        $page->metaKeyword = $this->metaKeyword;
        $page->metaDescriptions = $this->metaDescriptions;
        $page->pageContent = $this->pageContent;
        
        $page->image = (!empty($this->image) ) ? $this->image : '';
        
        $page->save();
        return $page->save() ? $page : null;
    }

}
