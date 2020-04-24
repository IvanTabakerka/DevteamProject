<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\projectexchange\models\RequestStatus */

$this->title = Yii::t('app', 'Create Request Status');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Request Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-status-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
