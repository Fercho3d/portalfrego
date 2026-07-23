<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Units;

/**
 * UnitsSearch represents the model behind the search form of `common\models\units`.
 */
class UnitsSearch extends units
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_unit', 'created_by', 'assinged_to'], 'integer'],
            [['number', 'created_at'], 'safe'],
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
        $query = units::find()
        ->select(['id_unit', 'number' ,'username' ])
        ->innerJoinWith('user', false);
        
        //echo $query->createCommand()->sql;
       // exit;

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
            'id_unit' => $this->id_unit,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'assinged_to' => $this->assinged_to,
        ]);

        $query->andFilterWhere(['like', 'number', $this->number]);

        return $dataProvider;
    }

    
}
