@extends('admin_panel.layouts.app')

@section('content')

<div class="pagetitle">
	<h1>Poll</h1>
	
  </div>
  <div class="card">
	<div class="card-body">
		<br>
		<a class="btn btn-primary float-end" href="javascript:void(0)" id="createNewProduct"> Create New </a>
		<h5 class="card-title">Bordered Table</h5>
	 	  <table class="table table-bordered border-primary data-table">
		<thead>
		  <tr>
			
			<th scope="col">Name</th>
			<th scope="col">Email</th>
			<th scope="col">MobileNo</th>
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
					<button type="button" class="btn btn-success float-end addfield">Add Option</button>
					<br>
					<form id="productForm" name="productForm" class="form-horizontal">
						<input type="hidden" name="product_id" id="product_id">
						
						<!-- Default Question -->
						<div class="question-group">
							<div class="form-group">
								<label for="question" class="col-sm-12 control-label">Question</label>
								<div class="col-sm-12">
									<input type="text" class="form-control" name="questions[]" placeholder="Enter Question" required>
								</div>
							</div>
							
							<!-- Default Options -->
							<div class="options-container">
								<div class="form-group">
									<label for="option1" class="col-sm-12 control-label">Option 1</label>
									<div class="col-sm-12">
										<input type="text" class="form-control" name="options1[]" placeholder="Enter Option 1" required>
									</div>
								</div>
								<div class="form-group">
									<label for="option2" class="col-sm-12 control-label">Option 2</label>
									<div class="col-sm-12">
										<input type="text" class="form-control" name="options2[]" placeholder="Enter Option 2" required>
									</div>
								</div>
							</div>
						</div>
						
						<!-- Container for additional options -->
						<div id="additionalOptions"></div>

						<div class="col-sm-offset-2 col-sm-10">
							
							<button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save Poll</button>
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
	  var table = $('.data-table').DataTable
	  ({
		  processing: true,
		  serverSide: true,
		  ajax: "{{ route('add-residenet') }}",
		  columns: 
		  	[
			  {data: 'name', name: 'name'},
			  {data: 'name', name: 'name'},
			  {data: 'name', name: 'name'},
			  {data: 'action', name: 'action', orderable: false, searchable: false},
		    ]
	  });

	  $('#createNewProduct').click(function () {
        $('#saveBtn').val("create-product");
        $('#product_id').val('');
        $('#productForm').trigger("reset");
        $('#modelHeading').html("Create New Poll");
        $('#ajaxModel').modal('show');
    });

	$(document).ready(function() {
    var optionIndex = 2; 

    // Add new option field
    $('.addfield').click(function(e) {
        e.preventDefault();
        optionIndex++;

        var newOption = `
        <div class="form-group option-group">
            <label for="option${optionIndex}" class="col-sm-12 control-label">Option ${optionIndex}</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" name="options[]" placeholder="Enter Option ${optionIndex}" required>
                 <br>
				<img src="{{asset('image/button-remove-512.webp')}}" height="30px" width="30px" alt="Remove" class="removefield" style="cursor: pointer; margin-left: 10px;">

            </div>
        </div>`;

        $('#additionalOptions').append(newOption);
    });

    $('#additionalOptions').on('click', '.removefield', function(e) {
        e.preventDefault();
        $(this).closest('.option-group').remove();
    });
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

          url: "{{ route('userstore') }}",

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