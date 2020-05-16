<?php

namespace common\modules\projectexchange\models;

use Yii;

/**
 * This is the model class for table "project".
 *
 * @property int $ID
 * @property string|null $BeginDate
 * @property string|null $EndDate
 * @property string|null $Name
 * @property int|null $PersonCount
 * @property int $ParentID
 * @property int|null $IsActual
 * @property string|null $VersionDate
 * @property string|null $DeletedDate
 * @property int $TypeID
 * @property int $StatusID
 * @property int $RequestParentID
 * @property int $TeamID
 */
class Project extends \common\components\VersionedActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['BeginDate', 'EndDate', 'VersionDate', 'DeletedDate'], 'safe'],
            [['PersonCount', 'ParentID', 'IsActual', 'TypeID', 'StatusID', 'RequestParentID', 'TeamID'], 'integer'],
            [['ParentID', 'TypeID', 'StatusID', ], 'required'],
            [['Name'], 'string', 'max' => 400],
          //  [['StatusID'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectStatus::className(), 'targetAttribute' => ['StatusID' => 'ID']],
          //  [['TypeID'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectType::className(), 'targetAttribute' => ['TypeID' => 'ID']],
        //    [['RequestParentID'], 'exist', 'skipOnError' => true, 'targetClass' => Request::className(), 'targetAttribute' => ['RequestParentID' => 'ParentID']],
           // [['TeamID'], 'exist', 'skipOnError' => true, 'targetClass' => Team::className(), 'targetAttribute' => ['TeamID' => 'ID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'BeginDate' => Yii::t('app', 'Begin Date'),
            'EndDate' => Yii::t('app', 'End Date'),
            'Name' => Yii::t('app', 'Name'),
            'PersonCount' => Yii::t('app', 'Person Count'),
            'ParentID' => Yii::t('app', 'Parent ID'),
            'IsActual' => Yii::t('app', 'Is Actual'),
            'VersionDate' => Yii::t('app', 'Version Date'),
            'DeletedDate' => Yii::t('app', 'Delete Date'),
            'TypeID' => Yii::t('app', 'Type ID'),
            'StatusID' => Yii::t('app', 'Status ID'),
            'RequestParentID' => Yii::t('app', 'Request Parent ID'),
            'TeamID' => Yii::t('app', 'Team ID'),
        ];
    }

    public function save($runValidation = true, $attributeNames = NULL)
    {
        
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        $fl = true;
        try {
            if(!$this->TeamID){
                $team = new Team;
                $team->Name = $this->Name;
                $fl = $fl && $team->save();
                $this->TeamID = $team->ID;
                
            }
            $request = Request::find()->where(['IsActual'=>1,'ParentID'=>$this->RequestParentID])->one();
            if($request && $request->StatusID == 2){
                $request->StatusID = 3;
                $fl = $fl && $request->save();
            }
            $fl = $fl && parent::save();
            if($fl){
                $transaction->commit();
            }else{
                $transaction->rollBack();
            }
        } catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch(\Throwable $e) {
            $transaction->rollBack();
        }
        return $fl;
    }

    public function getProjectType(){
        return $this->hasOne(ProjectType::className(), ['ID' => 'TypeID']);
    }

    public function getProjectStatus(){
        return $this->hasOne(ProjectStatus::className(), ['ID' => 'StatusID']);
    }

    public function getTeam(){
        return $this->hasOne(ProjectStatus::className(), ['ID' => 'TeamID']);
    }
    
}
