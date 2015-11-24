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
            $model = $this->updateAvatar($model);
            $model = $this->updatePassword($model);
            $model->save();

            Yii::$app->session->setFlash('success', 'You profile has been updated');

            return $this->redirect(['index']);
        } else {
            return $this->render('update', ['model' => $model]);
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

        // here we need to delete avatar file then logout user
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

        if (!$model->avatar_url) {
            return $this->redirect(['index']);
        }

        if ($this->deleteFile(Yii::getAlias('@webroot') . '/' . $model->avatar_url)) {
            $model->avatar_url = '';
            $model->save();
        }

        return $this->redirect(['index']);
    }

    /**
     * Update avatar
     * @param User $model
     * @return User $model
     */
    protected function updateAvatar($model)
    {
        $uploadFile = UploadedFile::getInstance($model, 'avatar_file');

        if ($uploadFile) {
            // Update avatar and give unique filename md5 from username to avoid encoding problems
            $model->avatar_url = $this->processUpload($uploadFile, md5($model->username));
        }
        return $model;
    }

    /**
     * Update password
     * @param User $model
     * @return User $model
     */
    protected function updatePassword($model)
    {
        if ($model->password_new) {
            $model->setPassword($model->password_new);
        }
        return $model;
    }

    /**
     * Process a file uploading
     * If upload is successful return uploaded path
     * @param UploadedFile $uploadedFile
     * @param string $filename
     * @return string
     * @throws \Exception if there is error on upload or save
     */
    protected function processUpload($uploadedFile, $filename)
    {
        $relativePath = self::AVATAR_UPLOAD_DIR . $filename . '.' . $uploadedFile->extension;
        $absolutePath = Yii::getAlias('@webroot') . '/' . $relativePath;

        if ($uploadedFile->error || !$uploadedFile->saveAs($absolutePath)) {
            throw new \Exception('There is an error while uploading a file');
        }

        return $relativePath;
    }

    /**
     * Deletes a file
     * @param string $path
     * @return bool
     * @throws \Exception if there is error on delete
     */
    protected function deleteFile($path)
    {
        if (!is_file($path)) {
            return false;
        }
        try {
            unlink($path);
        } catch (\Exception $e) {
            throw new \Exception('There is an error while deleting a file');
        }

        return true;
    }
}
