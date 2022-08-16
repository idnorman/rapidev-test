<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use PDO;

class Promotion extends BaseController
{

    public function __construct()
    {
        $this->session                  = \Config\Services::session();
        $this->db                       = \Config\Database::connect();
        $this->userModel                = new \App\Models\UserModel();
        $this->propertyModel            = new \App\Models\PropertyModel();
        $this->roomModel                = new \App\Models\RoomModel();
        $this->promotionModel           = new \App\Models\PromotionModel();
        $this->propertyPromotionModel   = new \App\Models\PropertyPromotionModel();
        $this->roomPromotionModel       = new \App\Models\RoomPromotionModel();

    }

    public function index()
    {
        $data['title']  = 'Data Promo';
        $data['promotions'] = $this->promotionModel->findAll();

        return view('promotion/index', $data);
    }

    public function create()
    {
        $data['title']  = 'Buat Promo';
        $data['hotels'] = $this->propertyModel->findAll();
        $data['rooms']  = $this->roomModel->findAll();

        return view('promotion/create', $data);
    }

    public function store()
    {
        $request        = $this->request->getPost();
        $publishDate    = explode(" - ", $request['publish_date']);
        $bookingDate    = explode(" - ", $request['booking_date']);
        $stayDate       = explode(" - ", $request['stay_date']);

        $hotelIds = array();
        $roomIds = array();
        $hotelArr = array();

        foreach($request['hotel_filter'] as $hotel){
            $temp = explode('-', $hotel);
            $hotelArr[$temp[0]][] = $temp[1];

            $hotelIds[] = $temp[0];
            $roomIds[] = $temp[1];
        }

        $hotelIds = array_unique($hotelIds);
        $roomIds  = array_unique($roomIds);

        $isAllHotel = (count($this->propertyModel->findAll()) == count($hotelIds));

        $promotion = [
            'author_id'         => session('user_id'),
            'name'              => $request['name'],
            'type'              => $request['type'],
            'amount'            => $request['amount']/10,
            'max_amount'        => $request['max_amount'],
            'publish_start'     => $publishDate[0],
            'publish_end'       => $publishDate[1],
            'booking_start'     => $bookingDate[0],
            'booking_end'       => $bookingDate[1],
            'stay_start'        => $stayDate[0],
            'stay_end'          => $stayDate[1],
            'is_all_properties' => $isAllHotel
        ];

        $this->promotionModel->insert($promotion);
        $promotionId = $this->promotionModel->insertID();

        foreach($hotelArr as $key => $hotel){
            $isAllRoom = ($this->db->table('rooms')->where('property_id', $key)->get()->getNumRows() == count($hotel));
            $propertyPromotion = [
                'promo_id'      => $promotionId,
                'property_id'   => $key,
                'is_all_rooms'  => $isAllRoom
            ];
            
            $this->propertyPromotionModel->insert($propertyPromotion);
            $propertyPromotionId = $this->propertyPromotionModel->insertID();

            foreach($hotel as $room){
                $roomPromotion = [
                    'property_promo_id' => $propertyPromotionId,
                    'room_id'           => $room
                ];

                $this->roomPromotionModel->insert($roomPromotion);
            }
 
        }

        $this->session->setFlashdata('success', 'Data berhasil dibuat');
        return redirect()->to('promotion');
        
    }

    public function edit($id){
        $data['title']  = 'Ubah Promo';
        $data['hotels'] = $this->propertyModel->findAll();
        $data['rooms']  = $this->roomModel->findAll();

        $builder = $this->db->table('promotions');
        $builder->select(
                    'promotions.id promotion_id, 
                    promotions.name promotion_name,
                    promotions.type promotion_type,
                    promotions.amount promotion_amount,
                    promotions.max_amount promotion_max_amount,
                    promotions.publish_start promotion_publish_start,
                    promotions.publish_end promotion_publish_end,
                    promotions.booking_start promotion_booking_start,
                    promotions.booking_end promotion_booking_end,
                    promotions.stay_start promotion_stay_start,
                    promotions.stay_end promotion_stay_end,
                    property_promotion.id property_promotion_id,
                    property_promotion.promo_id property_promotion_promo_id,
                    property_promotion.property_id property_promotion_property_id,
                    room_promotion.id room_promotion_id,
                    room_promotion.property_promo_id room_promotion_property_promo_id,
                    room_promotion.room_id room_promotion_room_id'
                );
        $builder->join('property_promotion', 'property_promotion.promo_id = promotions.id')
                ->join('room_promotion', 'room_promotion.property_promo_id = property_promotion.id');
        $builder->where('promotions.id', $id);
        $data['promotion'] = $builder->get()->getResultArray();
        
        $rooms = $this->db->table('promotions')->select('room_promotion.room_id')->join('property_promotion', 'property_promotion.promo_id = promotions.id')->join('room_promotion', 'room_promotion.property_promo_id = property_promotion.id')->where('promotions.id', $id)->get()->getResultArray();

        $roomIds = array();
        array_walk_recursive($rooms,function($v) use (&$roomIds){ $roomIds[] = $v; });

        $data['room_promotion'] = $roomIds;

        return view('promotion/edit', $data);
    }

    public function update(){
        $request        = $this->request->getPost();

        $roomPromotion = $this->db->table('promotions')->select('room_promotion.id')->join('property_promotion', 'property_promotion.promo_id = promotions.id')->join('room_promotion', 'room_promotion.property_promo_id = property_promotion.id')->where('promotions.id', $request['promotion_id'])->get()->getResultArray();
        $roomPromotionIds = array();
        array_walk_recursive($roomPromotion,function($v) use (&$roomPromotionIds){ $roomPromotionIds[] = $v; });

        $hotelPromotion = $this->db->table('promotions')->select('property_promotion.id')->join('property_promotion', 'property_promotion.promo_id = promotions.id')->where('promotions.id', $request['promotion_id'])->get()->getResultArray();
        $hotelPromotionIds = array();
        array_walk_recursive($hotelPromotion,function($v) use (&$hotelPromotionIds){ $hotelPromotionIds[] = $v; });

        $this->roomPromotionModel->delete($roomPromotionIds);
        $this->propertyPromotionModel->delete($hotelPromotionIds);

        $publishDate    = explode(" - ", $request['publish_date']);
        $bookingDate    = explode(" - ", $request['booking_date']);
        $stayDate       = explode(" - ", $request['stay_date']);

        $hotelIds = array();
        $roomIds = array();
        $hotelArr = array();

        foreach($request['hotel_filter'] as $hotel){
            $temp = explode('-', $hotel);
            $hotelArr[$temp[0]][] = $temp[1];

            $hotelIds[] = $temp[0];
            $roomIds[] = $temp[1];
        }

        $hotelIds = array_unique($hotelIds);
        $roomIds  = array_unique($roomIds);

        $isAllHotel = (count($this->propertyModel->findAll()) == count($hotelIds));

        $promotion = [
            'name'              => $request['name'],
            'type'              => $request['type'],
            'amount'            => $request['amount']/10,
            'max_amount'        => $request['max_amount'],
            'publish_start'     => $publishDate[0],
            'publish_end'       => $publishDate[1],
            'booking_start'     => $bookingDate[0],
            'booking_end'       => $bookingDate[1],
            'stay_start'        => $stayDate[0],
            'stay_end'          => $stayDate[1],
            'is_all_properties' => $isAllHotel
        ];


        foreach($hotelArr as $key => $hotel){
            $isAllRoom = ($this->db->table('rooms')->where('property_id', $key)->get()->getNumRows() == count($hotel));
            $propertyPromotion = [
                'promo_id'      => $request['promotion_id'],
                'property_id'   => $key,
                'is_all_rooms'  => $isAllRoom
            ];
            
            $this->propertyPromotionModel->insert($propertyPromotion);
            $propertyPromotionId = $this->propertyPromotionModel->insertID();

            foreach($hotel as $room){
                $roomPromotion = [
                    'property_promo_id' => $propertyPromotionId,
                    'room_id'           => $room
                ];

                $this->roomPromotionModel->insert($roomPromotion);
            }
 
        }

        $builder = $this->db->table('promotions');
        $builder->where('id', $request['promotion_id']);
        $builder->update($promotion);

        $this->session->setFlashdata('success', 'Data berhasil diubah');
        return redirect()->to('promotion');

    }

    public function delete($id){
        $roomPromotion = $this->db->table('promotions')->select('room_promotion.id')->join('property_promotion', 'property_promotion.promo_id = promotions.id')->join('room_promotion', 'room_promotion.property_promo_id = property_promotion.id')->where('promotions.id', $id)->get()->getResultArray();
        $roomPromotionIds = array();
        array_walk_recursive($roomPromotion,function($v) use (&$roomPromotionIds){ $roomPromotionIds[] = $v; });

        $hotelPromotion = $this->db->table('promotions')->select('property_promotion.id')->join('property_promotion', 'property_promotion.promo_id = promotions.id')->where('promotions.id', $id)->get()->getResultArray();
        $hotelPromotionIds = array();
        array_walk_recursive($hotelPromotion,function($v) use (&$hotelPromotionIds){ $hotelPromotionIds[] = $v; });

        $this->roomPromotionModel->delete($roomPromotionIds);
        $this->propertyPromotionModel->delete($hotelPromotionIds);
        $this->promotionModel->delete(['id' => $id]);

        $this->session->setFlashdata('success', 'Data berhasil dihapus');
        return redirect()->to('promotion');
    }

}
