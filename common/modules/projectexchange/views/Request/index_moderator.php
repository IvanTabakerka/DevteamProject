<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use common\modules\projectexchange\models\RequestStatus;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\projectexchange\models\searches\RequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Requests');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Request'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'ID',
            'PersonParentID',
            [
                'attribute' => 'RequestDate',
                'format' => ['date','php:d.m.Y']
            ],
            'PersonCount',
            'Cost',
            'TZ',
            [
                'attribute' => 'StatusID',
                'value' => function($model){
                    return $model->statusName;
                },
                'filter' => ArrayHelper::map(RequestStatus::find()->where(['!=','ID',1])->asArray()->all(), 'ID', 'Name')
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($myUrl, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::toRoute(['viewmoderator', 'id' => $model->ID]));
                    },
                ],
                'template' => '{view}'
            ],
        ],
    ]); ?>


</div>
