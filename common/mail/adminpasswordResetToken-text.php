<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $admin->password_reset_token]);
?>
Hello <?= $admin->username ?>,

Follow the link below to reset your password:

<?= $resetLink ?>
