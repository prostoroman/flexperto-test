<?php

namespace tests\codeception\frontend\_pages;

use \yii\codeception\BasePage;

/**
 * Represents user edit page
 * @property \codeception_frontend\AcceptanceTester|\codeception_frontend\FunctionalTester $actor
 */
class UserEditPage extends BasePage
{

    public $route = 'user/update';

    /**
     * @param array $userData
     */
    public function submit(array $userData)
    {
        foreach ($userData as $field => $value) {
            $inputType = 'input';
            $this->actor->fillField('input[name="User[' . $field . ']"]', $value);
        }
        $this->actor->click('save-button');
    }
}
