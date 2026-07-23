<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Charge;

/**
 * ChargeSearch represents the model behind the search form of `app\models\Charge`.
 */
class ChargeSearch extends Charge
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['charge_id', 'transaction', 'type'], 'integer'],
            [['tax_code', 'description'], 'safe'],
            [['quantity', 'unit', 'price'], 'number'],
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
    public function search($params, $transaction)
    {
  $query = Charge::find()
          ->select([
              'charge_id',
              'description',
              'quantity',
              'price_confirmation',
              'unit',
              'price',
              'IFNULL(prepaid,0) prepaid' ,
              'charge_type.charge_type_name ChargeType',
              'IFNULL(price,  0) * IFNULL(quantity,0) amount',
              'IFNULL(price,  0) * IFNULL(quantity,0) subtotal',
              'IFNULL(charge_type.tax_rate,0) tax_rate' ,
              'IFNULL(charge_type.tax_retention,0) tax_retention' ,
              'IFNULL(price,0)   * IFNULL(quantity,0) * IFNULL(charge_type.tax_rate,0) taxAmount',
              'IFNULL(price,  0) * IFNULL(quantity,0) * IFNULL(charge_type.tax_retention,0) retentionAmount',
              '(IFNULL(price, 0) * IFNULL(quantity,0) * IFNULL(charge_type.tax_rate,0) + IFNULL(price, 0) * IFNULL(quantity,0) ) -
               (IFNULL(price, 0) * IFNULL(quantity,0) * IFNULL(charge_type.tax_retention,0) )

               totalAmount'
          ])
          ->joinWith('type')
          ->Where(['transaction' => $transaction ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'charge_id' => $this->charge_id,
            'transaction' => $this->transaction,
            'type' => $this->type,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'price' => $this->price,
        ]);

        $query->andFilterWhere(['like', 'tax_code', $this->tax_code])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
