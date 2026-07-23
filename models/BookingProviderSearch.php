<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Booking;
use kartik\daterange\DateRangePicker;

/**
 * BookingSearch represents the model behind the search form of `app\models\Booking`.
 */
class BookingProviderSearch extends Booking
{
    public $dates;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['booking_id', 'booking_number', 'created_by', 'modified_by'], 'integer'],
            [[
             'vessel',
             'client',
             'loading_port',
             'loading_EDT',
             'dicharge_port',
             'dicharge_ETA',
             'container_type',
             'commodity',
             'set_point',
             'created_at',
             'modified_at',
             'vessel_name' ,
             'port_name' ,
             'container_name',
             'client_name',
             'pick_up_place',
             'pickup_date',
             'dates'
         ],  'safe'],  
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
        $query = Booking::find()->distinct()->select(
            ///container_types.container_name,
            '
            vessel.vessel_name, 
            booking_continuity.pickup_date, 
            client.fullName client_name, 
            loading_ports.port_name, 
            modifier.username as modifier, 
            users.username as creator, booking.*')
        ->joinWith('continuity')
        ->joinWith('creator')
        ->joinWith('loadingPort')
        ->joinWith('vessel')
        ->joinWith('customer')
        ->joinWith('transaction')
        //->joinWith('conainerType')
        ->innerJoin('users as modifier', 'users.usr_id = booking.created_by')
        ->where(['providers_by_booking.provider'=> \Yii::$app->user->identity->provider_id ])
        ->groupBy(['booking_id'])
        ;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
             'pagination' => [
                'pageSize' => 10,
              ],
        ]);

        $this->load($params);


        if(!empty($this->dates)){
            $dates = explode(' - ', $this->dates);
            $startArray = explode('/', $dates[0]);
            $endArray = explode('/', $dates[1]);
            $startDate = $startArray[2]."-".$startArray[1]."-".$startArray[0]; 
            $endDate =  $endArray[2]."-".$endArray[1]."-".$endArray[0];         
            $query->andfilterWhere(['between', 'pickup_date', $startDate, $endDate]);
        } 


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'booking_id' => $this->booking_id,
            'loading_EDT' => $this->loading_EDT,
            'dicharge_ETA' => $this->dicharge_ETA,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
        ]);
        
        $query
            ->andFilterWhere(['like', 'vessel', $this->vessel])
            ->andFilterWhere(['like', 'loading_port', $this->loading_port])
            ->andFilterWhere(['like', 'dicharge_port', $this->dicharge_port])
            ->andFilterWhere(['like', 'container_type', $this->container_type])
            ->andFilterWhere(['like', 'commodity', $this->commodity])
            ->andFilterWhere(['like', 'set_point', $this->set_point])
            ->andFilterWhere(['like', 'vessel.vessel_name', $this->vessel_name])
            ->andFilterWhere(['like', 'loading_ports.port_name', $this->port_name])
            ->andFilterWhere(['like', 'container_types.container_name', $this->port_name])
            ->andFilterWhere(['like', 'client.fullName', $this->client_name])
            ->andFilterWhere(['like', 'pick_up_place', $this->pick_up_place])
            ->andFilterWhere(['like', 'booking_number', $this->booking_number ]);
            
            return $dataProvider;
    }


}
