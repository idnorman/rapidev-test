<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Room extends BaseController
{
    use ResponseTrait;

    public function __construct()
    {
        
        $this->db = \Config\Database::connect();
    }

    public function reservation()
    {
        $roomId     = $this->request->uri->getSegment(2);
        $checkIn    = $this->request->getVar('check_in');
        $checkOut   = $this->request->getVar('check_out');
        $amount     = $this->request->getVar('amount');

        $promotionQuery = $this->db->table('room_promotion');
        $promotionQuery->select('promotions.id promotion_id, booking_start, booking_end,amount, max_amount');
        $promotionQuery->join('property_promotion', 'property_promotion.id = room_promotion.property_promo_id');
        $promotionQuery->join('promotions', 'promotions.id = property_promotion.promo_id');
        $promotionQuery->where('room_promotion.room_id',$roomId);
        $promotionQuery->where('promotions.booking_start >=', $checkIn);
        $promotionQuery->where('promotions.booking_end <', $checkOut);
        $promotions = $promotionQuery->get()->getResultArray();
        // dd($promotions);

        $roomRateQuery = $this->db->table('room_rates');
        $roomRateQuery->select('date, rate');
        $roomRateQuery->where('date >=', $checkIn);
        $roomRateQuery->where('date <', $checkOut);
        $roomRateQuery->where('room_id', $roomId);
        $roomRates = $roomRateQuery->get()->getResultArray();

        $totalDiscount = 0;
        $totalRate = 0;
        foreach($roomRates as $roomRate){
            foreach($promotions as $promotion){
                if(($roomRate['date'] >= $promotion['booking_start']) && ($roomRate['date'] < $promotion['booking_end'])){
                    // dd(($promotion['amount']/10)*$roomRate['rate']);
                    if((($promotion['amount']/10)*$roomRate['rate']) > $promotion['max_amount']){
                        $totalDiscount = $totalDiscount+$promotion['max_amount'];
                    }else{
                        $totalDiscount = $totalDiscount+($promotion['amount']/10)*$roomRate['rate'];
                    }
                }
            }
            $totalRate += $roomRate['rate'];
        }

        $data['result'] = [
            'original_total' => $totalRate,
            'discount_total' => $totalDiscount
        ];
        
        return $this->respond($data, 200);

    }
}
