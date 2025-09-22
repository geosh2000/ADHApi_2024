<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $DBGroup = 'production';

    /**
     * Get discount codes with hotel information.
     *
     * @return array
     */
    public function getDiscountCodes()
    {
        $sql = "SELECT a.id, category_name, a.code, discount_percentage, Hotel, currency
                FROM cycoasis_adh.discount_codes a
                LEFT JOIN catalog_hoteles b ON a.hotel_id=b.hotelId";
        $query = $this->db->query($sql);
        return $query->getResultArray();
    }

    /**
     * Update the code for a given discount code id.
     *
     * @param int $id
     * @param string $newCode
     * @return bool
     */
    public function updateDiscountCode($id, $newCode)
    {
        $sql = "UPDATE discount_codes SET code = ? WHERE id = ?";
        return $this->db->query($sql, [$newCode, $id]);
    }
}