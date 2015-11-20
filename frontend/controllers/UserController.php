<?php

namespace frontend\controllers;

use Yii;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{

    const AVATAR_UPLOAD_DIR = 'uploads/';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'update', 'delete', 'remove-avatar'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Shows user profile
     * @return mixed
     */
    public function actionIndex()
    {
        $userId = $this->getCurrentUserId();

        return $this->render('view', [
            'model' => $this->findModel($userId),
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate()
    {
        $model = $this->findModel($this->getCurrentUserId());

        if ($model->load(Yii::$app->request->post())) {
            // Update avatar
            if (UploadedFile::getInstance($model, 'avatar_file')) {
                $model->avatar_file = UploadedFile::getInstance($model, 'avatar_file');

                if ($model->avatar_file->getHasError()) {
                    Yii::$app->session->setFlash('error', $model->avatar_file->error);

                    return $this->redirect(['update']);
                }

                // check dir and try to create
                $uploadDir = \Yii::getAlias('@webroot') . '/' . self::AVATAR_UPLOAD_DIR;

                if (!is_dir($uploadDir)) {
                    try {
                        mkdir($uploadDir);
                    } catch (\Exception $e) {
                        // better to change to log message
                        Yii::$app->session->setFlash('error', $e->getMessage());
                        $this->redirect(['index']);
                    }
                }

                // unique filename md5 from username to avoid encoding problems
                $avatarName = md5($model->username);
                $avatarUrl = self::AVATAR_UPLOAD_DIR . '/'. $avatarName . '.' . $model->avatar_file->extension;
                $avatarFilePath = $uploadDir . '/' . $avatarName . '.' . $model->avatar_file->extension;

                $model->avatar_url = $avatarUrl;
                $model->avatar_file->saveAs($avatarFilePath);

                $model->avatar_file = null;
            }

            // Update password
            if ($model->password_new) {
                $model->setPassword($model->password_new);
            }

            Yii::$app->session->setFlash('success', 'You profile has been updated.');
            $model->save();

            return $this->redirect(['index']);

        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionDelete()
    {
        $model = $this->findModel($this->getCurrentUserId());

        // here we need to delete avatar file
        // then logout user
        Yii::$app->user->logout();
        $model->delete();

        return $this->goHome();
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Get current user
     * @return integer
     */
    protected function getCurrentUserId()
    {
        return Yii::$app->user->identity->id;
    }
    /**
     * Deletes an existing avatar.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @return mixed
     */
    public function actionRemoveAvatar()
    {
        $model = $this->findModel($this->getCurrentUserId());

        if ($model->avatar_url) {
            // then delete the file
            $filePath = \Yii::getAlias('@webroot') . '/' . $model->avatar_url;

            $model->avatar_url = '';
            $model->save();

            // Delete avatar file
            if (is_file($filePath)) {
                try {
                    unlink($filePath);
                } catch (\Exception $e) {
                    // we can put it to log file
                    Yii::$app->session->setFlash('error', $e->getMessage());
                    $this->redirect(['index']);
                }

            }
        }

        return $this->redirect(['index']);
    }
}
