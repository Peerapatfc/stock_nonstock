<script type="text/javascript">
	$(document).ready(function(){
		$("#example").dataTable({
			'bProcessing': true,
			'bServerSide': true,
			'sAjaxSource': "<?php echo admin_url('sales/get_payment_list'); ?>",
			"aoColumns": [
 				{ mData: 'id' } ,
                { mData: 'biller' },
                { mData: 'bank_to' },
                { mData: 'date_cf_payment' },
                { mData: 'total_cf_payment' },
                { mData: 'attachment' },
            ]
		});
	});
</script>
 <table id="example" class="table table-bordered table-hover table-striped" width="100%" cellspacing="0">
        <thead>
            <tr>
            	<!-- <th></th> -->
                <th>id</th>
                <th>biller</th>
 				<th>bank_to</th>
 				<th>date_cf_payment</th>
 				<th>total_cf_payment</th>
 				<th>attachment</th>
            </tr>
        </thead>
 
        <tfoot>
            <tr>
            	<!-- <th></th> -->
                <th>id</th>
				<th>biller</th>
 				<th>bank_to</th>
 				<th>date_cf_payment</th>
 				<th>total_cf_payment</th>
 				<th>attachment</th>
            </tr>
        </tfoot>
    </table>
<!-- <table id="exam-table" class="table table-bordered table-hover table-striped">
	<thead>
		<tr>
			<th>ID</th>
			<th>OrderNumber</th>
		</tr>
	</thead>
	<tfoof>
		<tr>
			<th>ID</th>
			<th>OrderNumber</th>
		</tr>
	</tfoof>
</table> -->