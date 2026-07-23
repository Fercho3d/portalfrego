<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "check_list".
 *
 * @property int $check_id
 * @property int|null $booking
 * @property string|null $booking_number_chk_date
 * @property int|null $booking_number_chk_by
 * @property string|null $pickup_date_chk_date
 * @property int|null $pickup_date_chk_by
 * @property string|null $modality_chk_date
 * @property int|null $modality_chk_by
 * @property string|null $doc_cut_of_chk_date
 * @property int|null $doc_cut_of_chk_by
 * @property string|null $SI_date_chk_date
 * @property int|null $SI_date_chk_by
 * @property string|null $draf_client_chk_date
 * @property int|null $draf_client_chk_by
 * @property string|null $gated_IN_chk_date
 * @property int|null $gated_IN_chk_by
 * @property string|null $cleared_chk_date
 * @property int|null $cleared_chk_by
 * @property string|null $departure_chk_date
 * @property int|null $departure_chk_by
 * @property string|null $bl_payment_chk_date
 * @property int|null $bl_payment_chk_by
 * @property string|null $swb_chk_date
 * @property int|null $swb_chk_by
 * @property string|null $vessel_chk_date
 * @property int|null $vessel_chk_by
 * @property string|null $number_chk_date
 * @property int|null $number_chk_by
 * @property string|null $client_chk_date
 * @property int|null $client_chk_by
 * @property string|null $loading_port_chk_date
 * @property int|null $loading_port_chk_by
 * @property string|null $loading_ETD_chk_date
 * @property int|null $loading_EDT_chk_by
 * @property string|null $dicharge_port_chk_date
 * @property int|null $dicharge_port_chk_by
 * @property string|null $container_type_chk_date
 * @property int|null $container_type_chk_by
 * @property string|null $commodity_chk_date
 * @property int|null $commodity_chk_by
 * @property string|null $set_point_chk_date
 * @property int|null $set_point_chk_by
 *
 * @property Users $sIDateChkBy
 * @property Users $blPaymentChkBy
 * @property Booking $booking0
 * @property Users $bookingChkBy
 * @property Users $loadingPortChkBy
 * @property Users $clearedChkBy
 * @property Users $clientChkBy
 * @property Users $departureChkBy
 * @property Users $docCutOfChkBy
 * @property Users $drafClientCheckBy
 * @property Users $gatedINCheckBy
 * @property Users $loadingPortChkBy0
 * @property Users $modalityChkBy
 * @property Users $numberChkBy
 * @property Users $pickupChkBy
 * @property Users $swbChkBy
 * @property Users $vesselChkBy
 */
class CheckList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'check_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['booking', 'booking_number_chk_by', 'pickup_date_chk_by', 'modality_chk_by', 'doc_cut_of_chk_by', 'SI_date_chk_by', 'draf_client_chk_by', 'gated_IN_chk_by', 'cleared_chk_by', 'departure_chk_by', 'bl_payment_chk_by', 'swb_chk_by', 'vessel_chk_by', 'number_chk_by', 'client_chk_by', 'loading_port_chk_by', 'loading_EDT_chk_by', 'dicharge_port_chk_by', 'container_type_chk_by', 'commodity_chk_by', 'set_point_chk_by'], 'integer'],
            [['booking_chk_date', 'pickup_date_chk_date', 'modality_chk_date', 'doc_cut_of_chk_date', 'SI_date_chk_date', 'draf_client_chk_date', 'gated_IN_chk_date', 'cleared_chk_date', 'departure_chk_date', 'bl_payment_chk_date', 'swb_chk_date', 'vessel_chk_date', 'number_chk_date', 'client_chk_date', 'loading_port_chk_date', 'loading_ETD_chk_date', 'dicharge_port_chk_date', 'container_type_chk_date', 'commodity_chk_date', 'set_point_chk_date'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'check_id' => 'Check ID',
            'booking' => 'Booking',
            'booking_number_chk_date' => 'Booking Chk Date',
            'booking_number_chk_by' => 'Booking Chk By',
            'pickup_date_chk_date' => 'Pickup Chk Date',
            'pickup_date_chk_by' => 'Pickup Chk By',
            'modality_chk_date' => 'Modality Chk Date',
            'modality_chk_by' => 'Modality Chk By',
            'doc_cut_of_chk_date' => 'Doc Cut Of Chk Date',
            'doc_cut_of_chk_by' => 'Doc Cut Of Chk By',
            'SI_date_chk_date' => 'Si Date Chk Date',
            'SI_date_chk_by' => 'Si Date Chk By',
            'draf_client_chk_date' => 'Draf Client Check Date',
            'draf_client_chk_by' => 'Draf Client Check By',
            'gated_IN_chk_date' => 'Gated In Check Date',
            'gated_IN_chk_by' => 'Gated In Check By',
            'cleared_chk_date' => 'Cleared Chk Date',
            'cleared_chk_by' => 'Cleared Chk By',
            'departure_chk_date' => 'Departure Chk Date',
            'departure_chk_by' => 'Departure Chk By',
            'bl_payment_chk_date' => 'Bl Payment Chk Date',
            'bl_payment_chk_by' => 'Bl Payment Chk By',
            'swb_chk_date' => 'Swb Chk Date',
            'swb_chk_by' => 'Swb Chk By',
            'vessel_chk_date' => 'Vessel Chk Date',
            'vessel_chk_by' => 'Vessel Chk By',
            'number_chk_date' => 'Number Chk Date',
            'number_chk_by' => 'Number Chk By',
            'client_chk_date' => 'Client Chk Date',
            'client_chk_by' => 'Client Chk By',
            'loading_port_chk_date' => 'Loading Port Chk Date',
            'loading_port_chk_by' => 'Loading Port Chk By',
            'loading_ETD_chk_date' => 'Loading Etd Chk Date',
            'loading_EDT_chk_by' => 'Loading Etd Chk By',
            'dicharge_port_chk_date' => 'Dicharge Port Chk Date',
            'dicharge_port_chk_by' => 'Dicharge Port Chk By',
            'container_type_chk_date' => 'Container Type Chk Date',
            'container_type_chk_by' => 'Container Type Chk By',
            'commodity_chk_date' => 'Commodity Chk Date',
            'commodity_chk_by' => 'Commodity Chk By',
            'set_point_chk_date' => 'Set Point Check Date',
            'set_point_chk_by' => 'Set Point Check By',
        ];
    }

    /**
     * Gets query for [[SIDateChkBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSIDateChkBy()
    {
        return $this->hasOne(User::className(), ['usr_id' => 'SI_date_chk_by']);
    }

    /**
     * Gets query for [[BlPaymentChkBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBlPaymentChkBy()
    {
        return $this->hasOne(User::className(), ['usr_id' => 'bl_payment_chk_by']);
    }

    /**
     * Gets query for [[Booking0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooking0()
    {
        return $this->hasOne(Booking::className(), ['booking_id' => 'booking']);
    }

    /**
     * Gets query for [[BookingChkBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookingChkBy()
    {
        return $this->hasOne(User::className(), ['usr_id' => 'booking_number_chk_by']);
    }

    /**
     * Gets query for [[LoadingPortChkBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLoadingPortChkBy()
    {
        return $this->hasOne(User::className(), ['usr_id' => 'loading_port_chk_by']);
    }

    /**
     * Gets query for [[ClearedChkBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClearedChkBy()
    {
        return $this->hasOne(User::className(), ['usr_id' => 'cleared_chk_by']);
    }

    /**
     * Gets query for [[ClientChkBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClientChkBy()
    {
        return $this->hasOne(User::className(), ['usr_id' => 'client_chk_by']);
    }

    /**
     * Gets query for [[DepartureChkBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepartureChkBy()
    {
        return $this->hasOne(User::className(), ['usr_id' => 'departure_chk_by']);
    }

    /**
     * Gets query for [[DocCutOfChkBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocCutOfChkBy()
    {
        return $this->hasOne(User::className(), ['usr_id' => 'doc_cut_of_chk_by']);
    }

    /**
     * Gets query for [[DrafClientCheckBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDrafClientCheckBy()
    {
        return $this->hasOne(User::className(), ['usr_id' => 'draf_client_chk_by']);
    }

    /**
     * Gets query for [[GatedINCheckBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGatedINCheckBy()
    {
        return $this->hasOne(User::className(), ['usr_id' => 'gated_IN_chk_by']);
    }

    /**
     * Gets query for [[LoadingPortChkBy0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLoadingPortChkBy0()
    {
        return $this->hasOne(User::className(), ['usr_id' => 'loading_port_chk_by']);
    }

    /**
     * Gets query for [[ModalityChkBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModalityChkBy()
    {
        return $this->hasOne(User::className(), ['usr_id' => 'modality_chk_by']);
    }

    /**
     * Gets query for [[NumberChkBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNumberChkBy()
    {
        return $this->hasOne(User::className(), ['usr_id' => 'number_chk_by']);
    }

    /**
     * Gets query for [[PickupChkBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPickupChkBy()
    {
        return $this->hasOne(User::className(), ['usr_id' => 'pickup_date_chk_by']);
    }

    /**
     * Gets query for [[SwbChkBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSwbChkBy()
    {
        return $this->hasOne(User::className(), ['usr_id' => 'swb_chk_by']);
    }

    /**
     * Gets query for [[VesselChkBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVesselChkBy()
    {
        return $this->hasOne(User::className(), ['usr_id' => 'vessel_chk_by']);
    }

    public function calcDeliveryTime($estimatedDate,$deliveryDate){
      
        if(empty($estimatedDate) || empty($deliveryDate) ){
            return '';
        }

            $estimatedTime = date_create($estimatedDate);
            $deliveryTime =  date_create($deliveryDate);
            $interval = $estimatedTime->diff($deliveryTime);
            $minutes =  (int) $interval->format('%R%a')* 24 * 60; ; 
            $minutes = $minutes + (int) $interval->format('%h') * 60 ; 
            $minutes = $minutes +  (int) $interval->format('%m') ; 
            $class = 'alert alert-success';
            $entrega =' a tiempo ';
            if(strtotime($estimatedDate) < strtotime($deliveryDate)){
                $entrega =' de retraso ';
                $class = 'alert alert-danger';
            }

            return  '<span  class="deivery '. $class .'" >'.$interval->format('%R%a días, %h horas y %m minutos') . $entrega . '</span>';


    }

}
