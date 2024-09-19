@extends('admin_panel.layouts.app')

@section('content')

<div class="pagetitle">
	<h1>Flat</h1>
	
  </div><!-- End Page Title -->
  <div class="card">
	<div class="card-body">
		<br>
		<a class="btn btn-primary float-end" href="javascript:void(0)" id="createNewProduct"> Create New </a>
		<h5 class="card-title">Bordered Table</h5>
		

	 	  <table class="table table-bordered border-primary data-table">
		<thead>
		  <tr>
			<th scope="col">FlatNumber</th>
			<th scope="col">FloorNumber</th>
			<th scope="col">BlockNumber</th>
			<th width="280px">Action</th>
		  </tr>
		</thead>
		<tbody>
		  
		</tbody>
	  </table>
	</div>

	<div class="modal fade" id="ajaxModel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="modelHeading"></h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="productForm" name="productForm" class="form-horizontal">
					   <input type="hidden" name="product_id" id="product_id">
						<div class="form-group">
							<label for="name" class="col-md-12">Flat Number</label>
							<div class="col-sm-12">
								<input type="text" class="form-control" id="name" name="flatnumber" placeholder="Enter Flat Number" value="" maxlength="50" required="">
							</div>
						</div>
						<div class="form-group">
							<label for="name" class="col-md-12">Floor Number</label>
							<div class="col-sm-12">
								<input type="text" class="form-control" id="name" name="floornumber" placeholder="Enter Floor Number" value=""  required="">
							</div>
						</div>
                        <div class="form-group">
							<label for="name" class="col-md-12">Block Number</label>
							<div class="col-sm-12">
								<input type="text" class="form-control" id="name" name="blocknumber" placeholder="Enter Block Number" value=""  required="">
							</div>
						</div>
        
					
				
						
			
						<div class="col-sm-offset-2 col-sm-10">
							
							<button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save User</button>
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

							
						 </button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	
  </div>


  <script type="text/javascript">
	$(function () {
		
	  /*------------------------------------------
	   --------------------------------------------
	   Pass Header Token
	   --------------------------------------------
	   --------------------------------------------*/ 
	  $.ajaxSetup({
			headers: {
		                                                                                                                                                                                                                                                                                                                                                                                                                		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
	  });
		
	  /*------------------------------------------
	  --------------------------------------------
	  Render DataTable
	  --------------------------------------------
	  --------------------------------------------*/
	  var table = $('.data-table').DataTable({
		  processing: true,
		  serverSide: true,
		  ajax: "{{ route('flate') }}",
		  columns: [
			
			  {data: 'flat_number', name: 'flat_number'},
			  {data: 'floor_number', name: 'floor_number'},
			  {data: 'type', name: 'type'},
			  {data: 'action', name: 'action', orderable: false, searchable: false},
			
		  ]
	  });
	  $('#createNewProduct').click(function () 
	  	{
        $('#saveBtn').val("create-product");
        $('#product_id').val('');
        $('#productForm').trigger("reset");
        $('#modelHeading').html("Create New Flat");
        $('#ajaxModel').modal('show');
    	});


	$('body').on('click', '.editProduct', function () {
      var product_id = $(this).data('id');
	  $.get("{{ route('products-ajax-crud.edit', ':id') }}".replace(':id', product_id), function (data) {
          $('#saveBtn').val("edit-user");
          $('#ajaxModel').modal('show');
          $('#product_id').val(data.id);
          $('#name').val(data.name);
          $('#name').val(data.name);
          $('#name').val(data.name);
      })
    });

	$('#saveBtn').click(function (e) 
    {
        e.preventDefault();
        $(this).html('Sending..');
        $.ajax({

          data: $('#productForm').serialize(),

          url: "{{ route('flatstore') }}",

          type: "POST",

          dataType: 'json',

          success: function (data) 
          {
              $('#productForm').trigger("reset");

              $('#ajaxModel').modal('hide');

              table.draw();

           

          },

          error: function (data) {

              console.log('Error:', data);

              $('#saveBtn').html('Save Changes');

          }

      });

    });

	$('body').on('click', '.deleteProduct', function () {

     

var product_id = $(this).data("id");

confirm("Are You sure want to delete !");



$.ajax({

	type: "DELETE",

	url:  "{{ route('userdelete', ':id') }}".replace(':id', product_id),

	success: function (data) {

		table.draw();

	},

	error: function (data) {

		console.log('Error:', data);

	}

});

});


















































	});
	</script>

@endsection