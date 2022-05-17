<?php

namespace app\controllers;

use Yii;
use app\models\News;
use yii\data\Pagination;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\db\Expression;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller {
    /**
     * @inheritDoc
     */
    public function behaviors() {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class'   => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }
    
    /**
     * Lists all News models.
     *
     * @return string
     */
    public function actionIndex() {
        $oQuery = News::find();
        $oPagination = new Pagination([
                                          'defaultPageSize' => 9,
                                          'totalCount'      => $oQuery->count(),
                                      ]);
        
        $aNews = $oQuery->orderBy('id')
            ->offset($oPagination->offset)
            ->limit($oPagination->limit)
            ->all();
        
        return $this->render('index', [
            'aNews'       => $aNews,
            'oPagination' => $oPagination,
        ]);
    }
    
    /**
     * Displays a single News model.
     *
     * @param int $id ID
     *
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    
    /**
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate() {
        $oModel = new News(['scenario' => News::SCENARIO_CREATE]);
        if ($this->request->isPost) {
            if ($oModel->load($this->request->post()) ) {
    
                return $this->saveNews($oModel);
            }
        } else {
            $oModel->loadDefaultValues();
        }
        
        return $this->render('create', [
            'model' => $oModel,
        ]);
    }
    
    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id ID
     *
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $oModel = $this->findModel($id);
        $oModel->scenario = News::SCENARIO_UPDATE;
        if ($this->request->isPost && $oModel->load($this->request->post())) {
            return $this->saveNews($oModel);
        }
        
        return $this->render('update', [
            'model' => $oModel,
        ]);
    }
    
    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id ID
     *
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $oModel = $this->findModel($id);
        $oModel->delete();
        FileHelper::unlink(Yii::getAlias('@webroot/' . $oModel->image));
        return $this->redirect(['index']);
    }
    
    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id ID
     *
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = News::findOne(['id' => $id])) !== NULL) {
            return $model;
        }
        
        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /**
     * @param \app\models\News $oModel
     *
     * @return \yii\web\Response
     */
    private function saveNews(News $oModel) {
        
        $oModel->image = UploadedFile::getInstance($oModel, 'image');
        if ($oModel->validate()) {
            $aUpdateFields = ['title', 'content', 'create_date', 'update_date'];
            
            $oModel->update_date = ($oModel->id)? new Expression('UNIX_TIMESTAMP()') : 0;
            $oModel->create_date = (!$oModel->id)? new Expression('UNIX_TIMESTAMP()'): $oModel->create_date;
            $oModel->save(FALSE, $aUpdateFields);
    
            if ($oModel->image !== NULL) {
                $oModel->uploadImage($oModel->image);
                $aUpdateFields = ['image'];
                $oModel->update(FALSE, $aUpdateFields);
            }
            
        }
        
        return $this->redirect(['view', 'id' => $oModel->id]);
    }
}
