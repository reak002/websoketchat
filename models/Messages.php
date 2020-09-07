<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "messages".
 *
 * @property int $id
 * @property string $user_id
 * @property string $user_name
 * @property string|null $text
 */
class Messages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'messages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text'], 'string'],
            [['user_id', 'user_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'user_name' => 'User Name',
            'text' => 'Text',
        ];
    }

    public function saveMessage($mess){
    	$this->text = $mess;
    	$this->save(false);
	}

	public function beforeSave($insert)
	{

		if (parent::beforeSave($insert)) {

			if(!Yii::$app->user->isGuest){
				$this->user_id = Yii::$app->user->getId();
				$this->user_name = Yii::$app->user->identity->username;
			}
			else{
				$user_name = 'Guest';
				$user_id = '0';
			}

			return true;
		}
		return false;
	}

}
