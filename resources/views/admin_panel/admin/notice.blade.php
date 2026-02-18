@extends('admin_panel.layouts.app')

@section('content')

<style>
	
        .hidden-fields {
            display: none;
        }
   
</style>

<div class="pagetitle">
	<h1>Notice</h1>
	
  </div><!-- End Page Title -->
  <div class="card">
	<div class="card-body">
		<br>
		<a class="btn btn-primary float-end" href="javascript:void(0)" id="createNewProduct"> Create New </a>
		<h5 class="card-title">Bordered Table</h5>
		

	 	  <table class="table table-bordered border-primary data-table">
		<thead>
		  <tr>
			
			<th scope="col">Title</th>
			<th scope="col">Description</th>
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

					<div class="d-flex justify-content-between align-items-center mb-3">
						<label class="form-check-label" for="toggle">Schedule Notice</label>
						<div class="form-check form-switch">
							<input class="form-check-input" type="checkbox" id="toggle">
						</div>
					</div>
					
					<form id="productForm" name="productForm" class="form-horizontal">
					   <input type="hidden" name="product_id" id="product_id">
						<div class="form-group">
							<label for="name" class="col-sm-2 control-label">Title</label>
							<div class="col-sm-12">
								<input type="text" class="form-control" id="name" name="title" placeholder="Enter Notice Title" value="" maxlength="50" required="">
							</div>
						</div>
						<div class="form-group">
							<label for="name" class="col-sm-12 control-label">Description</label>
							<div class="col-sm-12">
								<input type="text" class="form-control" id="name" name="description" placeholder="Enter Description" value=""  required="">
							</div>
						</div>
        

	<div class="form-group">
						<div id="moreFields" class="hidden-fields">
							<label for="start_date" class="col-sm-12 control-label">StartDate</label>
							<div class="col-sm-12">
								<input type="date" class="form-control" id="start_date" name="start_date" placeholder="Enter Date" value="" maxlength="50" required="">
							</div>
				
							<label for="time" class="col-sm-12 control-label">Time</label>
							<div class="col-sm-12">
								<input type="time" class="form-control" id="time" name="time" placeholder="Enter time" value="" maxlength="50" required="">
							</div>
						</div>
					</div>				
				    <div class="col-sm-offset-2 col-sm-10">
							
							<button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save Notice</button>
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

							
						 </button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

  </div>
  <script>
	document.getElementById('toggle').addEventListener('change', function() {
		var moreFields = document.getElementById('moreFields');
		if (this.checked) {
			moreFields.style.display = 'block';
		} else {
			moreFields.style.display = 'none';
		}
	});
</script>


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
		  ajax: "{{ route('notice') }}",
		  columns: [
			
			  {data: 'title', name: 'title'},
			  {data: 'description', name: 'description'},
			  
			  {data: 'action', name: 'action', orderable: false, searchable: false},
			
		  ]
	  });

	  $('#createNewProduct').click(function () {
        $('#saveBtn').val("create-product");
        $('#product_id').val('');
        $('#productForm').trigger("reset");
        $('#modelHeading').html("Create New Notice");
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

          url: "{{ route('noticestore') }}",

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