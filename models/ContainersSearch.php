<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Containers;

/**
 * ContainersSearch represents the model behind the search form of `app\models\Containers`.
 */
class ContainersSearch extends Containers
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['container_ID', 'quantity', 'container_type', 'booking'], 'integer'],
            [['comodity', 'created_at', 'created_by', 'modified_by'], 'safe'],
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
    public function search($params,$booking)
    {
        $query = Containers::find()->where(['booking'=>$booking]);

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
            'container_ID' => $this->container_ID,
            'quantity' => $this->quantity,
            'container_type' => $this->container_type,
            'booking' => $this->booking,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'comodity', $this->comodity])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'modified_by', $this->modified_by]);

        return $dataProvider;
    }
}
