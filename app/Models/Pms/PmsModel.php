<?php

namespace App\Models\Pms;

use CodeIgniter\Model;

class PmsModel extends Model
{
    protected $DBGroup = 'new_adh_crs';
    // Cambia el nombre de la tabla a la que corresponde tu modelo

    public function rsvDetail( $folio )
    {
        $query = "Declare @Checkin AS DATE ='20250630',

        @CheckOut AS DATE ='20250630',

        @HotelId AS INT  = 13    --1 ATPM / 13 OCP
 
SELECT 
                    ri.Id,
                    ri.ReservationItemCode,
                    htl.Name as Hotel,
                    CASE 
                        WHEN htl.Name LIKE '%leo%' THEN 'hotel_olcp'
                        WHEN htl.Name LIKE '%atelier playa%' THEN 'hotel_atpm'
                        ELSE 'hotel_otro'
                    END as HotelField,
                    (CONCAT(c.LastName,' ', c.Name)) AS NameCustomer,
                    ISNULL((
                        SELECT STRING_AGG(cc.Code + '|' + cc.Name, ', ')
                        FROM CustomerCategories ccs
                        INNER JOIN CustomerCategory cc ON cc.Id = ccs.CustomerCategoryId
                        WHERE ccs.CustomerId = c.Id
                    ), '') AS CustomerCategories,
                    rtc.RoomCode,
                    rtc.RoomTypeName,
                    r.Number AS RoomNumber,
                    hr.CheckIn,
                    hr.CheckOut,
                    COALESCE(cc.TotalCustomerCount, 0) AS Guests,
                    ri.ReservationStatus,
                    CASE 
                        WHEN ri.ReservationStatus = 2 THEN 'InHouse'
                        WHEN ri.ReservationStatus = 3 THEN 'Reserved'
                        WHEN ri.ReservationStatus = 4 THEN 'Check-Out'
                        WHEN ri.ReservationStatus = 5 THEN 'Cancelled'
                        WHEN ri.ReservationStatus = 6 THEN 'To be Cancelled'
                    END as status,
                    ag.Name AS NameAgency,
                    ri.ReservationDate,
                    ag.AgencyPaymentMethod,
                    hr.Amount as MontoReserva,
                    COALESCE((select sum(amount) 
                    from Sale 
                    where ReferenceItemCode = ri.ReservationItemCode AND conceptId=579 AND status=1 and SaleTypeId=2),0) AS MontoPagado,  
                    COALESCE((select sum(amount) 
                    from Sale 
                    where ReferenceItemCode = ri.ReservationItemCode AND conceptId=603 AND status=1 and SaleTypeId=2),0) AS ExtrasPagados,  
                    hr.Amount-COALESCE((select sum(amount) 
                    from Sale 
                    where ReferenceItemCode = ri.ReservationItemCode AND conceptId=579 AND status=1 and SaleTypeId=2),0) AS BalanceHabitacion,  
                    LOWER(cur.CurrencyCode) as Currency,
                    (SELECT COUNT(*) FROM ReservationCustomer rc2 
                        INNER JOIN Customer cust ON rc2.CustomerId = cust.Id 
                        WHERE rc2.ReservationItemId = ri.Id AND cust.Type = 1) AS Adults,
                    (SELECT COUNT(*) FROM ReservationCustomer rc2 
                        INNER JOIN Customer cust ON rc2.CustomerId = cust.Id 
                        WHERE rc2.ReservationItemId = ri.Id AND cust.Type = 2) AS Teens,
                    (SELECT COUNT(*) FROM ReservationCustomer rc2 
                        INNER JOIN Customer cust ON rc2.CustomerId = cust.Id 
                        WHERE rc2.ReservationItemId = ri.Id AND cust.Type = 5) AS Senior,
                    (SELECT COUNT(*) FROM ReservationCustomer rc2 
                        INNER JOIN Customer cust ON rc2.CustomerId = cust.Id 
                        WHERE rc2.ReservationItemId = ri.Id AND cust.Type = 3) AS Children,
                    (SELECT COUNT(*) FROM ReservationCustomer rc2 
                        INNER JOIN Customer cust ON rc2.CustomerId = cust.Id 
                        WHERE rc2.ReservationItemId = ri.Id AND cust.Type = 4) AS Infants,
                    ri.ExternalReservationNumber as f2gConf,
                    AgencyReservationId,
                    CASE 
                        WHEN ag.id IN (
                            477, -- Presidencia
                            952, -- Mayorista
                            816, -- DescuentoMotor
                            1087, -- MOTOR USD
                            1088 -- MOTOR MXN
                            ) THEN 'tipo_b2c' 
                        ELSE 
                        CASE 
                            WHEN ag.id = 1036 THEN 'tipo_b2b'
                            WHEN ag.id IN (29,392,1040,1043,1044,1032,1034,1039,1038,1041,1033,1125,1047,1037,1035,1042,1045,939) THEN 'tipo_b2c'
                            ELSE 'tipo_b2b' 
                        END 
                    END as TipoCliente,
                    CASE 
                        WHEN ag.id IN (
                            477, -- Presidencia
                            952, -- Mayorista
                            816, -- DescuentoMotor
                            1087, -- MOTOR USD
                            1088 -- MOTOR MXN
                            ) THEN 'rsv_chan_direct' 
                        ELSE 
                        CASE 
                            WHEN ag.id = 1036 THEN 'rsv_chan_belong'
                            WHEN ag.id IN (29,392,1040,1043,1044,1032,1034,1039,1038,1041,1033,1125,1047,1037,1035,1042,1045,939) THEN 'Cortesia'
                            ELSE 'rsv_chan_other' 
                        END 
                    END as Channel,
                    CASE 
                        WHEN ag.id IN (
                            477, -- Presidencia
                            952, -- Mayorista
                            816, -- DescuentoMotor
                            1087, -- MOTOR USD
                            1088 -- MOTOR MXN
                            ) THEN 'show_amount' 
                        ELSE 
                        CASE 
                            WHEN ag.id = 1036 THEN 'hide_amount'
                            WHEN ag.id IN (29,392,1040,1043,1044,1032,1034,1039,1038,1041,1033,1125,1047,1037,1035,1042,1045,939) THEN 'hide_amount'
                            ELSE 'hide_amount' 
                        END 
                    END as showMontos,
                    hr.Notes, hr.InternalNotes,
                    hr.Notes as PolicyCustom, 'policy_otro' as politica, 'show_balance' as showBalance
                FROM
                    ReservationItem ri
                INNER JOIN
                    HotelReservationDetail hr ON ri.HotelReservationDetailId = hr.Id
                LEFT JOIN   
                    Hotel htl ON hr.HotelId=htl.id
                LEFT JOIN
                    Currency cur ON cur.Id = hr.CurrencyId
                LEFT JOIN
                    RoomReservation rr ON hr.Id = rr.HotelReservationDetailId
                LEFT JOIN
                    Room r ON rr.RoomId = r.Id
                INNER JOIN
                    Reservation res ON res.Id = ri.ReservationId
                LEFT JOIN
                    ReservationCustomer rc ON rc.ReservationItemId = ri.Id  AND rc.IsPrimary = 1
                LEFT JOIN
                    Customer c ON c.Id = rc.CustomerId
                LEFT JOIN 
                    Agency ag on ag.Id = ri.AgencyId
                LEFT JOIN 
                    GroupReservation gr ON gr.Id = res.GroupReservationId
                LEFT JOIN (
                    SELECT 
                        ReservationItemId, 
                        COUNT(*) AS TotalCustomerCount
                    FROM 
                        ReservationCustomer
                    GROUP BY 
                        ReservationItemId
                ) cc ON cc.ReservationItemId = ri.Id
                OUTER APPLY (   SELECT RoomTypeCode.Id as RoomTypeCodeId, RoomType.Name AS RoomTypeName,  RoomTypeCode.RoomCode  AS RoomCode FROM RoomType 
							    INNER JOIN RoomTypeCode ON RoomType.Id = RoomTypeCode.RoomTypeId 
								WHERE RoomTypeCode.RoomTypeId = ISNULL(r.RoomTypeCodeId,hr.RoomTypeId) ) rtc
                WHERE   ri.IsDeleted = 0
                AND  
                (ReservationItemCode='$folio' OR ri.ExternalReservationNumber='$folio' OR AgencyReservationId='$folio')";
        
        try {
            $result = $this->db->query($query);
            return $result->getResultArray();
        } catch (\Exception $e) {
            // Manejo de excepciones
            throw new \RuntimeException('Error al obtener los detalles de la reserva: ' . $e->getMessage());
        }
    }

}