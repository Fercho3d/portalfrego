<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TaxCode;

/**
 * TaxCodeSearch represents the model behind the search form of `app\models\TaxCode`.
 */
class TaxCodeSearch extends TaxCode
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tax_code_id'], 'integer'],
            [['tax_code'], 'safe'],
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
        $query = TaxCode::find();

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
            'tax_code_id' => $this->tax_code_id,
        ]);

        $query->andFilterWhere(['like', 'tax_code', $this->tax_code]);

        return $dataProvider;
    }
}
