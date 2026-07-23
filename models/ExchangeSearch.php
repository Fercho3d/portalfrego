<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Exchange;

/**
 * ExchangeSearch represents the model behind the search form of `app\models\Exchange`.
 */
class ExchangeSearch extends Exchange
{
    public $account_name;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['exchange_id', 'account', 'created_by', 'modified_by'], 'integer'],
            [['exchange_value'], 'number'],
            [['date_exchange', 'created_at', 'modified_at', 'account_name'], 'safe'],
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
        $query = Exchange::find()
        ->select(
            [
            'exchange_id',
            'exchange_value',
            'date_exchange',
            'account',
            'account.account_name'
            ]
            )
        ->joinWith('accountrel')
        ;

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
            'exchange_id' => $this->exchange_id,
            'exchange_value' => $this->exchange_value,
            'date_exchange' => $this->date_exchange,
            'account' => $this->account,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
        ]);

        $query->andFilterWhere(['like', 'account_name', $this->account_name ]);


        return $dataProvider;
    }
}
