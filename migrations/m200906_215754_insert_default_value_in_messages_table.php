<?php

use yii\db\Migration;

/**
 * Class m200906_215754_insert_default_value_in_messages_table
 */
class m200906_215754_insert_default_value_in_messages_table extends Migration
{
	/**
	 * @return bool|void
	 */
    public function safeUp()
    {
		$this->batchInsert('messages',['user_id','user_name','text'],
			[
				['101','User_1','Some message #1'],
				['102','User_2','Some message #2'],
				['103','User_3','Some message #3']
			]);
    }

	/**
	 * @return bool
	 */
    public function safeDown()
    {
        echo "m200906_215754_insert_default_value_in_messages_table cannot be reverted.\n";

        return false;
    }

}
