<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;

class Property extends BaseController
{
    use ResponseTrait;

    public function __construct()
    {
        
        $this->db = \Config\Database::connect();
    }

    public function getRoomRates()
    {
        $propertyId = $this->request->uri->getSegment(2);
        $checkIn    = $this->request->getVar('check_in');
        $checkOut   = $this->request->getVar('check_out');
        
        $rooms = $this->db->table('rooms')->select('rooms.id')->where('rooms.property_id', $propertyId)->get()->getResultArray();
        $roomIds = array();
        array_walk_recursive($rooms,function($v) use (&$roomIds){ $roomIds[] = $v; });

        $roomRates = $this->db->table('rooms')
                    ->select('rooms.id id,
                                rooms.name name,
                                room_rates.rate rate_per_night,
                                room_rates.no_promo has_promo
                            ')
                    ->join('room_rates', 'room_rates.room_id = rooms.id')
                    ->where('room_rates.date >=', $checkIn)
                    ->where('room_rates.date <', $checkOut)
                    ->where('rooms.property_id', $propertyId)
                    ->whereIn('rooms.id', $roomIds)
                    ->get()
                    ->getResultArray();

        foreach($roomRates as $key => $rr){
            $roomRates[$key]['has_promo'] = ($rr['has_promo'] ? false : true);
        }
        $data['result'] = $roomRates;

        return $this->respond($data, 200);

    }
}
