<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ChargeType;

/**
 * ChargeTypeSearch represents the model behind the search form of `app\models\ChargeType`.
 */
class ChargeTypeSearch extends ChargeType
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['charge_type_id'], 'integer'],
            [['charge_type_name'], 'safe'],
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
        $query = ChargeType::find();

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
            'charge_type_id' => $this->charge_type_id,
        ]);

        $query->andFilterWhere(['like', 'charge_type_name', $this->charge_type_name]);

        return $dataProvider;
    }
}
