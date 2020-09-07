<?php

use yii\db\Migration;

/**
 * Handles the creation of table messages.
 */
class m200906_204116_create_messages_table extends Migration
{
	/**
	 * @return bool|void
	 */
    public function safeUp()
    {
        $this->createTable('messages', [
			'id' => $this->primaryKey(),
			'user_id' => $this->string()->notNull(),
			'user_name' => $this->string()->notNull(),
			'text' => $this->text(),
        ]);
    }

	/**
	 * @return bool|void
	 */
    public function safeDown()
    {
        $this->dropTable('messages');
    }
}
