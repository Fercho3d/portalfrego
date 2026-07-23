<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Modality;

/**
 * ModalitySearch represents the model behind the search form of `app\models\Modality`.
 */
class ModalitySearch extends Modality
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['modality_id'], 'integer'],
            [['modality_name'], 'safe'],
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
        $query = Modality::find();

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
            'modality_id' => $this->modality_id,
        ]);

        $query->andFilterWhere(['like', 'modality_name', $this->modality_name]);

        return $dataProvider;
    }
}
