<table class="table table-responsive table-striped table-condensed" width="100%">
    <thead>
		<th align="center">#</th>
		<th align="center" width="10%">AP No</th>
		<th align="center" width="12%">DO No</th>
		<th align="center" width="11%">DO Date</th>
        <th align="center" width="10%">Container Size</th>
        <th align="center" width="10%">Container No</th>
        <th align="center" width="8%">Police No</th>
        <th align="center" width="10%">Qty (Pcs/Kgs)</th>
        <th align="center" width="12%">Unit Price (Rp)</th>
        <th align="center" width="12%">Price (Rp)</th>
    </thead>
    <tbody>
        <?php
        $i=1;
        $total_qty = 0;
        $total_price = 0;
        $total_amount = 0;
        $wholesale = 0;
        
        if(!empty($data_do)){
            $jumlah_data = count($data_do);
            
            foreach($data_do as $do){
                $cek_do = $this->invoice_model->get_data_ap_by_ap_id($do->rowID);
                
                $row = $do; //$this->invoice_model->get_data_ap_do_by_row_id($do->rowID);
                                
                $price = 0;
                $wholesale = $row->wholesale;
                
                if($row->jo_type == 1){
                    $container_size = '';
                }
                else{
                    if($row->container_size > 0){
                        $container_size = '1 x '.$row->container_size.' Feet';
                    }
                    else{
                        $container_size = '';
                    }                        
                }
                                             
                $biaya = 0;
                if($row->container_size == 20){
                    $biaya = $row->price_20ft;
                }
                elseif($row->container_size == 40){
                    $biaya = $row->price_40ft;
                }
                elseif($row->container_size == 45){
                    $biaya = $row->price_45ft;
                }

                if($row->jo_type == 1){
                    $qty = $row->received_weight;
                    $price = $row->price_amount;
                }
                else if($row->jo_type == 2){
                    $qty = 1;
                    if($row->wholesale == 1){
                        $price = $row->price_amount;
                    }
                    else{
                        $price = $biaya;
                    }
                }
                else{
                    $qty = 1;
                    if($row->wholesale == 1){
                        $price = $row->price_amount;
                    }
                    else{
                        $price = $biaya;
                    }
                }
                
                $price = $cek_do->price;

                if($row->wholesale == 1){
                    $price_amount = $price;
                }
                else{
                    $price_amount = $qty * $price;
                }  
                                
                $total_qty += $qty;
        		$total_price = $price;
                $total_amount += $price_amount;
                    
        ?>
        		<tr id="baris_<?=$i?>">	
                    <td align="center">
                        <span class="btn btn-sm red" id="delete_do_<?=$i?>" title="Delete" onclick="delete_do('baris_<?=$i?>')"><i class="fa fa-times"></i></span>
                        <input type="hidden" name="qty[]" id="qty_<?=$i?>" value="<?=$qty?>" />                        
                        <input type="hidden" name="price[]" id="price_<?=$i?>" value="<?=$price?>" />                        
                        <input type="hidden" name="price_amount[]" id="price_amount_<?=$i?>" value="<?=$price_amount?>" />                        
                        <input type="hidden" name="ap_dtl_id[]" id="ap_dtl_id_<?=$i?>" value="<?=$row->rowID?>" />                        
                        <input type="hidden" name="ap_no[]" id="ap_no_<?=$i?>" value="<?=$row->trx_no?>" />                        
                    </td>						
        			<td align="left"><?=strtoupper($row->trx_no)?></td>
        			<td align="left"><?=strtoupper($row->do_no)?></td>
        			<td align="left"><?=date("d F Y",strtotime($row->do_date))?></td>
        			<td align="left"><?= $container_size ?></td>
        			<td align="left"><?= $row->container_no?></td>
        			<td align="left"><?= $row->police_no?></td>
        			<td align="left"><?= number_format($qty,2,',','.')?></td>	
        			<td align="right"><?= number_format($price,2,',','.')?></td>	
        			<td align="right"><?= number_format($price_amount,2,',','.')?></td>	
        		</tr>
        <?php 
                $i++;
                
            }
            
            if($i == 1){
                echo '<tr><td align="center" colspan="10">Data delivery order not available in Account Payable</td></tr>';
            }
        }
        else{
            echo '<tr><td align="center" colspan="10">Data delivery order not available in Account Payable</td></tr>';
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th>&nbsp;</th>
            <th style="text-align: right;">
                Total DO
            </th>
            <th>
                <input type="text" class="form-control" name="total_do" id="total_do" value="<?=count($data_do)?>" style="width: 50%;text-align:center" readonly="" />
            </th>
            <th style="text-align: right;" colspan="4">
                Total
                <input type="hidden" name="count_data_do" id="count_data_do" value="<?=count($data_do)?>" />
                <input type="hidden" name="wholesale" id="wholesale" value="<?=$wholesale?>" />
                
            </th>
    		<th style="text-align: center;">
                <input type="text" class="form-control input-sm text-center" name="total_qty" id="total_qty" value="<?=number_format($total_qty,2,',','.')?>" readonly="" />      
            </th>
    		<th style="text-align: center;">
              <input type="text" class="form-control input-sm text-right" name="total_price" id="total_price" value="<?=number_format($total_price,2,',','.')?>" readonly="" />
            </th>
    		<th style="text-align: center;">
              <input type="text" class="form-control input-sm text-right" name="total_price_amount" id="total_price_amount" value="<?=number_format($total_amount,2,',','.')?>" readonly="" />
            </th>
        </tr>
    </tfoot>
</table>
