<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order}}`.
 */
class m200825_121232_create_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->datetime(),
            'updated_at' => $this->datetime(),
            'quantity' => $this->int(10),
            'sum' => $this->float(),
            'status' => $this->enum(0,1)->default(0),
            'name' => $this->string(255)->notNull(),
            'email' => $this->string(255),
            'phone' => $this->string(255),
            'address' => $this->string(255)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%order}}');
    }
}
