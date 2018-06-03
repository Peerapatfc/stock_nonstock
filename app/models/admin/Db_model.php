<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Db_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getLatestSales()
    {
        if ($this->Settings->restrict_user && !$this->Owner && !$this->Admin) {
            $this->db->where('created_by', $this->session->userdata('user_id'));
        }
        $this->db->order_by('id', 'desc');
        $q = $this->db->get("sales", 5);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getLastestQuotes()
    {
        if ($this->Settings->restrict_user && !$this->Owner && !$this->Admin) {
            $this->db->where('created_by', $this->session->userdata('user_id'));
        }
        $this->db->order_by('id', 'desc');
        $q = $this->db->get("quotes", 5);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getLatestPurchases()
    {
        if ($this->Settings->restrict_user && !$this->Owner && !$this->Admin) {
            $this->db->where('created_by', $this->session->userdata('user_id'));
        }
        $this->db->order_by('id', 'desc');
        $q = $this->db->get("purchases", 5);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getLatestTransfers()
    {
        if ($this->Settings->restrict_user && !$this->Owner && !$this->Admin) {
            $this->db->where('created_by', $this->session->userdata('user_id'));
        }
        $this->db->order_by('id', 'desc');
        $q = $this->db->get("transfers", 5);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getLatestCustomers()
    {
        $this->db->order_by('id', 'desc');
        if (!$this->Owner) {
			$this->db->where('user_id', $this->session->userdata('user_id'));
        }
        $q = $this->db->get_where("companies", array('group_name' => 'customer'), 5);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getLatestSuppliers()
    {
        $this->db->order_by('id', 'desc');
        $q = $this->db->get_where("companies", array('group_name' => 'supplier'), 5);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getChartData()
    {
        if (!$this->Owner) {
            $user_id = "&& created_by =".$this->session->userdata('user_id');
        }
        $myQuery = "SELECT S.month,
        COALESCE(S.sales, 0) as sales,
        COALESCE( P.purchases, 0 ) as purchases,
        COALESCE(S.tax1, 0) as tax1,
        COALESCE(S.tax2, 0) as tax2,
        COALESCE( P.ptax, 0 ) as ptax
        FROM (  SELECT  date_format(date, '%Y-%m') Month,
                SUM(total) Sales,
                SUM(product_tax) tax1,
                SUM(order_tax) tax2
                FROM " . $this->db->dbprefix('sales') . "

                WHERE date >= date_sub( now( ) , INTERVAL 12 MONTH )".$user_id."

                GROUP BY date_format(date, '%Y-%m')) S
            LEFT JOIN ( SELECT  date_format(date, '%Y-%m') Month,
                        SUM(product_tax) ptax,
                        SUM(order_tax) otax,
                        SUM(total) purchases
                        FROM " . $this->db->dbprefix('purchases') . "
                        GROUP BY date_format(date, '%Y-%m')) P
            ON S.Month = P.Month
            ORDER BY S.Month";
			
			
			
        $q = $this->db->query($myQuery);

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getStockValue()
    {
        $q = $this->db->query("SELECT SUM(qty*price) as stock_by_price, SUM(qty*cost) as stock_by_cost
        FROM (
            Select sum(COALESCE(" . $this->db->dbprefix('warehouses_products') . ".quantity, 0)) as qty, price, cost
            FROM " . $this->db->dbprefix('products') . "
            JOIN " . $this->db->dbprefix('warehouses_products') . " ON " . $this->db->dbprefix('warehouses_products') . ".product_id=" . $this->db->dbprefix('products') . ".id
            GROUP BY " . $this->db->dbprefix('warehouses_products') . ".id ) a");
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getBestSeller($start_date = NULL, $end_date = NULL)
    {
        if (!$start_date) {
            $start_date = date('Y-m-d', strtotime('first day of this month')) . ' 00:00:00';
        }
        if (!$end_date) {
            $end_date = date('Y-m-d', strtotime('last day of this month')) . ' 23:59:59';
        }

        $this->db
            ->select("product_name, product_code")
            ->select_sum('quantity')
            ->from('sale_items')
            ->join('sales', 'sales.id = sale_items.sale_id', 'left')
            ->where('sales.date >=', $start_date)
            ->where('sales.date <', $end_date)
            ->group_by('product_name, product_code')
            ->order_by('sum(quantity)', 'desc')
            ->limit(10);

        if (!$this->Owner) {
            $this->db->where('created_by', $this->session->userdata('user_id'));
        }
			
			
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	
	
	//01/09/2017
    public function getEarnings($dateCurStart, $dateCurEnd)
    {
        $this->db->select('sales.id, sales.date, sales.total, sales.grand_total, sales.created_by');
        $this->db->where('sales.date >=', $dateCurStart);
        $this->db->where('sales.date <=', $dateCurEnd);
        if (!$this->Owner) {
            $this->db->where('sales.created_by', $this->session->userdata('user_id'));
        }
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
    public function getShipments($dateCurStart, $dateCurEnd)
    {
        $this->db->select('deliveries.id as id, sales.created_by, deliveries.tracking AS tracking,  deliveries.date')
            ->join('sales', 'deliveries.sale_id=sales.id', 'left')
			->where('deliveries.date >=', $dateCurStart)->where('deliveries.date <=', $dateCurEnd)
			->where("({$this->db->dbprefix('deliveries')}.tracking IS NOT NULL)", NULL)
            ->order_by('id', 'asc');

        if (!$this->Owner) {
            $this->db->where('sales.created_by', $this->session->userdata('user_id'));
        }
        $q = $this->db->get('deliveries');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
    public function customerTotal()
    {
        $this->db->select('companies.*');
		$this->db->where('companies.group_name', 'customer');
        if (!$this->Owner) {
            $this->db->where('companies.user_id', $this->session->userdata('user_id'));
        }
        $q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
    public function customerToday()
    {
		$dateCurStart = date("Y-m-d 00:00:00");
		$dateCurEnd = date("Y-m-d 23:59:59");
        $this->db->select('companies.*')
			->where('companies.date >=', $dateCurStart)->where('companies.date <=', $dateCurEnd)
			->where("({$this->db->dbprefix('companies')}.date IS NOT NULL)", NULL);
		$this->db->where('companies.group_name', 'customer');
        if (!$this->Owner) {
            $this->db->where('companies.user_id', $this->session->userdata('user_id'));
        }
		
        $q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
    public function AverageOrders()
    {
        $this->db->select('sales.id, sales.date, sales.total, sales.grand_total, sales.created_by');
        if (!$this->Owner) {
            $this->db->where('sales.created_by', $this->session->userdata('user_id'));
        }
		
        $q = $this->db->get('sales');
		$_sum = 0; $AverageOrders=0;
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row->grand_total;
            }
			$_sum = array_sum($data);
			$count = sizeof($data); 
			$AverageOrders = $_sum / $count;
			
            return $AverageOrders;
        }
        return FALSE;
	}
	
	
    public function AverageOrdersSeller()
    {
		$start_date = date('Y-m-d', strtotime('first day of this month')) . ' 00:00:00';
		$end_date = date('Y-m-d', strtotime('last day of this month')) . ' 23:59:59';

        $this->db
            ->select("sum(grand_total) as grand_total")
            ->from('purchases')
            ->where('purchases.date >=', $start_date)
            ->where('purchases.date <', $end_date);
            // ->group_by('grand_total');

			
        // if (!$this->Owner) {
            // $this->db->where('created_by', $this->session->userdata('user_id'));
        // }
			
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
		
		
	}
	

	
}