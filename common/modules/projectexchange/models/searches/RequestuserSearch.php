<?php

namespace common\modules\projectexchange\models\searches;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\projectexchange\models\Request;

/**
 * RequestSearch represents the model behind the search form of `common\modules\projectexchange\models\Request`.
 */
class RequestuserSearch extends Request
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID', 'PersonCount', 'TZ', 'ParentID', 'IsActual', 'StatusID', 'TypeID', 'PersonID'], 'integer'],
            [['Tasks', 'Objective', 'Issue', 'ProductResults', 'Cost', 'RequestDate', 'VersionDate', 'DeletedDate'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Request::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'ID' => $this->ID,
            'PersonCount' => $this->PersonCount,
            'TZ' => $this->TZ,
            'RequestDate' => $this->RequestDate,
            // 'ParentID' => $this->ParentID,
            'IsActual' => $this->IsActual,
            'VersionDate' => $this->VersionDate,
            'DeletedDate' => $this->DeletedDate,
            'StatusID' => $this->StatusID,
            'TypeID' => $this->TypeID,
            'PersonID' => Yii::$app->user->identity->PersonID,
        ]);

        $query->andFilterWhere(['like', 'Tasks', $this->Tasks])
            ->andFilterWhere(['like', 'Objective', $this->Objective])
            ->andFilterWhere(['like', 'Issue', $this->Issue])
            ->andFilterWhere(['like', 'ProductResults', $this->ProductResults])
            ->andFilterWhere(['like', 'Cost', $this->Cost]);

        return $dataProvider;
    }
}
