<?php

use yii\db\Migration;

class m151119_145400_user_phone_avatar extends Migration
{
    public function safeUp()
    {
        // phone +49 176 860 600 96
        $this->addColumn('{{%user}}', 'phone_mobile', $this->string(20));

        // avatar url upload/ + username + extension, maximum username size 255
        $this->addColumn('{{%user}}', 'avatar_url', $this->string(310));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%profile}}', 'phone_mobile');
        $this->dropColumn('{{%profile}}', 'avatar_url');
    }
}
