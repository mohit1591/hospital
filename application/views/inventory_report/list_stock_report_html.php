<?php $this->load->model('item_manage/item_manage_model', 'item_manage');?>
<html><head><title>Inventory Report</title><?php if ($print_status == 1): ?><script type="text/javascript">window.print();window.onfocus = function () {window.close();}</script><?php endif; ?></head><body><table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td style="text-align:center;font-size:18px;"><span style="border-bottom:2px solid #111;">Inventory Report</span></td>
			</tr>
			<tr>
				<td style="text-align:center;font-size:13px;padding:1em;">
					<strong>From</strong>
					<span><?php echo $get['start_date']; ?></span>
					<strong>To</strong>
					<span><?php echo $get['end_date']; ?></span>
				</td>
				 <!-- <td><input type="button" name="button_print" value="Print" id="print" onClick="return my_function();"/></td> -->
			</tr>
		</table><table width="100%" cellpadding="0" cellspacing="0" border="0" style="font:13px Arial;"><tr style="background:black;color:white"><th>Sr.No.</th><th>Category</th><th>Item Name</th><th>MRP</th><th>Price</th><th>Stock</th><th>Issue</th><th>Purchase</th></tr><?php if (!empty($branch_inventory_list)): ?><?php $i = 1; ?><?php foreach ($branch_inventory_list as $item_manage): ?><?php
                            $this->db->select('SUM(CASE WHEN path_stock_item.type = 1 THEN  path_stock_item.debit ELSE 0 END) AS purchase_qty,
                        SUM(CASE WHEN path_stock_item.type = 6 THEN  path_stock_item.credit ELSE 0 END) AS issue_qty')
                                ->from('path_stock_item')
                                ->where('path_stock_item.item_id', $item_manage->item_id)
                                ->group_by('item_id');
                                if(!empty($get['start_date']))
                                {
                                    $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";
                                    // echo $start_date;
                                    $this->db->where('path_stock_item.created_date >= "'.$start_date.'"');
                                }
                
                                if(!empty($get['end_date']))
                                {
                                    $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                                    $this->db->where('path_stock_item.created_date <= "'.$end_date.'"');
                                }
                                $mydata = $this->db->limit(1)
                                ->get();
                            $issueQty = 0;
                            $purchaseQty = 0;
                            if ($mydata->num_rows() > 0) {
                                $row = $mydata->row();
                                $issueQty = $row->issue_qty;
                                $purchaseQty = $row->purchase_qty;
                            }
                            ?><tr style="text-align:center"><td><?php echo $i; ?>.</td><td><?php echo $item_manage->category; ?></td><td><?php echo $item_manage->item; ?></td><td><?= $item_manage->mrp ?></td><td><?php echo $item_manage->price; ?></td><td><?php
                                    $qty_data = get_item_quantity($item_manage->id, $item_manage->category_id);
                                    $medicine_total_qty = $qty_data['total_qty'];
                                    echo $medicine_total_qty;
                                    ?></td><td><?= $issueQty ?></td><td><?= $purchaseQty ?></td></tr><?php $i++; ?><?php endforeach; ?><?php endif; ?></tbody></table></body></html>
