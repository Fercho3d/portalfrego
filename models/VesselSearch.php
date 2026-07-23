<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Vessel;

/**
 * VesselSearch represents the model behind the search form of `app\models\Vessel`.
 */
class VesselSearch extends Vessel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vessel_id'], 'integer'],
            [['vessel_name'], 'safe'],
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
        $query = Vessel::find();

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
            'vessel_id' => $this->vessel_id,
        ]);

        $query->andFilterWhere(['like', 'vessel_name', $this->vessel_name]);

        return $dataProvider;
    }
}
