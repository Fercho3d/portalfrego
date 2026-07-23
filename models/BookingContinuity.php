<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "booking_continuity".
 *
 * @property int $cont_id
 * @property int|null $booking
 * @property string|null $pickup_date
 * @property int|null $modality
 * @property string|null $vacuum_maneuver
 * @property string|null $doc_cut_of
 * @property string|null $SI_date
 * @property string|null $draf_client
 * @property string|null $gated_IN
 * @property string|null $cleared
 * @property string|null $departure
 * @property string|null $bl_payment
 * @property string|null $swb
 *
 * @property Booking $booking0
 * @property Modality $modality0
 */
class BookingContinuity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'booking_continuity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['booking', 'modality'], 'integer'],
            [['vacuum_maneuver', 'bl_payment', 'gated_out', 'delivered','pickup_date', 'SI_date', 'doc_cut_of', 'draf_client', 'gated_IN', 'cleared', 'departure', 'swb', 'corrected_draft' ], 'safe'],
            [['booking'], 'exist', 'skipOnError' => true, 'targetClass' => Booking::className(), 'targetAttribute' => ['booking' => 'booking_id']],
            [['modality'], 'exist', 'skipOnError' => true, 'targetClass' => Modality::className(), 'targetAttribute' => ['modality' => 'modality_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */  
    public function attributeLabels()
    {
        return [
            'cont_id' => 'Cont ID',
            'booking' => 'Booking',
            'pickup_date' => 'PODF',
            'modality' => 'Modality',
            'vacuum_maneuver' => 'Empty pass',
            'doc_cut_of' => 'Close Day',
            'SI_date' => 'Si Date',
            'draf_client' => 'Draf customer',
            'gated_IN' => 'Gated In',
            'cleared' => 'Cleared',
            'departure' => 'Departure',
            'bl_payment' => 'Bl Payment',
            'swb' => 'Swb',
            'gated_out' => 'Gated Out',
            'delivered' => 'delivered',
        ];
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
     * Gets query for [[Modality0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModality0()
    {
        return $this->hasOne(Modality::className(), ['modality_id' => 'modality']);
    }

    public function beforeSave($insert){
            
            $this->pickup_date = empty($this->pickup_date)? null: date("Y-m-d h:i:s",  strtotime(str_replace('/', '-',$this->pickup_date)));
            $this->doc_cut_of = empty($this->doc_cut_of)? null: date("Y-m-d h:i:s",  strtotime(str_replace('/', '-',$this->doc_cut_of)));
            $this->SI_date = empty($this->SI_date)? null: date("Y-m-d h:i:s",  strtotime(str_replace('/', '-',$this->SI_date)));
            $this->draf_client= empty($this->draf_client)? null: date("Y-m-d h:i:s",  strtotime(str_replace('/', '-',$this->draf_client)));
            $this->gated_IN= empty($this->gated_IN)? null: date("Y-m-d h:i:s",  strtotime(str_replace('/', '-',$this->gated_IN)));
            $this->cleared= empty($this->cleared)? null: date("Y-m-d h:i:s",  strtotime(str_replace('/', '-',$this->cleared)));
            $this->departure= empty($this->departure) ? null: date("Y-m-d h:i:s",  strtotime(str_replace('/', '-',$this->departure)));
            $this->bl_payment = empty($this->bl_payment) ? null : date("Y-m-d h:i:s",  strtotime(str_replace('/', '-',$this->bl_payment)));
            $this->swb = empty($this->swb)? null: date("Y-m-d h:i:s",  strtotime(str_replace('/', '-',$this->swb)));
            $this->cleared = empty($this->cleared)? null: date("Y-m-d h:i:s",  strtotime(str_replace('/', '-',$this->cleared)));
            $this->delivered = empty($this->delivered)? null: date("Y-m-d h:i:s",  strtotime(str_replace('/', '-',$this->delivered)));

         return parent::beforeSave($insert);  
    }

}
