<?php
class Assetsellreportinfo extends CI_Model
{
    public function Getasset_name(){
        $this->db->select('*');
        $this->db->from('tbl_asset');
        $this->db->where('status',1);
        

        return $respond=$this->db->get();
    }

    public function selldetailreport()
    {

        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $asset_ID = $this->input->post('asset_name');

        $this->db->select('u.tbl_asset_idtbl_asset, u.date, u.reason, u.amount, ua.asset_name');
        $this->db->from('tbl_asset_sell AS u');
        $this->db->join('tbl_asset AS ua','ua.idtbl_asset = u.tbl_asset_idtbl_asset','left');
        $this->db->where('u.date >=', $from_date);
        $this->db->where('u.date <=', $to_date);
        $this->db->where('u.tbl_asset_idtbl_asset', $asset_ID);

        $respond = $this->db->get();

        $html = '';
        $total_amount = 0;
        $count = 0;

        foreach ($respond->result() as $row) {
            $count++;
            $html .= '<tr>
        <th scope="row">' . $count . '</th>
        <td scope="row">' . $row->asset_name . '</td>
        <td scope="row">' . $row->date . '</td>
        <td scope="row">' . $row->reason . '</td>
        <td scope="row" class="text-right">' . number_format($row->amount, 2) . '</td>
        </tr>';

            $total_amount += $row->amount;
        }

        $html .= '<tr>
    <td colspan="4" class="text-right font-weight-bold">Total Amount</td>
    <td class="text-right font-weight-bold">' . number_format($total_amount, 2) . '</td>
    </tr>';

        return $html;
    }
}


