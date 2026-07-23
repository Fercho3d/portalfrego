<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BookingContinuity;

/**
 * BookingContinuitySearch represents the model behind the search form of `app\models\BookingContinuity`.
 */
class BookingContinuitySearch extends BookingContinuity
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cont_id', 'booking', 'modality'], 'integer'],
            [['pickup_date', 'vacuum_maneuver', 'doc_cut_of', 'SI_date', 'draf_client', 'gated_IN', 'cleared', 'departure', 'bl_payment', 'swb'], 'safe'],
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
        $query = BookingContinuity::find();

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
            'cont_id' => $this->cont_id,
            'booking' => $this->booking,
            'pickup_date' => $this->pickup_date,
            'modality' => $this->modality,
            'SI_date' => $this->SI_date,
        ]);

        $query->andFilterWhere(['like', 'vacuum_maneuver', $this->vacuum_maneuver])
            ->andFilterWhere(['like', 'doc_cut_of', $this->doc_cut_of])
            ->andFilterWhere(['like', 'draf_client', $this->draf_client])
            ->andFilterWhere(['like', 'gated_IN', $this->gated_IN])
            ->andFilterWhere(['like', 'cleared', $this->cleared])
            ->andFilterWhere(['like', 'departure', $this->departure])
            ->andFilterWhere(['like', 'bl_payment', $this->bl_payment])
            ->andFilterWhere(['like', 'swb', $this->swb]);

        return $dataProvider;
    }
}
