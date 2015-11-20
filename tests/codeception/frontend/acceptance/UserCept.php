<?php
use tests\codeception\frontend\AcceptanceTester;
use tests\codeception\common\_pages\LoginPage;
use tests\codeception\frontend\_pages\UserPage;
use tests\codeception\frontend\_pages\UserEditPage;

/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);
$I->wantTo('Login view and update my profile');

$loginPage = LoginPage::openBy($I);

$I->amGoingTo('Login with correct credentials');
$loginPage->login('erau', 'password_0');

$I->amGoingTo('ensure user profile page works');

$userPage = UserPage::openBy($I);

$I->expectTo('see user personal data');
$I->see('sfriesen@jenkins.info');
$I->seeLink('Edit my profile');

$I->amGoingTo('Open Edit my profile page');

$userEditPage = UserEditPage::openBy($I);

$I->see('Edit my profile', 'h1');

$I->amGoingTo('update my profile with correct data');

$userEditPage->submit([
    'phone_mobile' => '+49 30 6840 978 167',
]);
if (method_exists($I, 'wait')) {
    $I->wait(3); // only for selenium
}
$I->see('You profile has been updated.');
$I->see('+49 30 6840 978 167');
