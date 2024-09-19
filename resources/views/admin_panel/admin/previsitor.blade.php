@extends('admin_panel.layouts.app')
@section('content')
<div class="pagetitle">
	<h1>Previsitor</h1>
	
  </div><!-- End Page Title -->
  <div class="card">
	<div class="card-body">
		<br>
		<h5 class="card-title">Bordered Table</h5>
	 	  <table class="table table-bordered border-primary data-table">
		<thead>
		  <tr>
			<th scope="col">Visitor_name</th>
			<th scope="col">Date</th>
			<th scope="col">FlatNo</th>
			<th scope="col">No of Person</th>
			<th scope="col">Status</th>
		  </tr>
		</thead>
		<tbody>
		</tbody>
	  </table>
	</div>
  </div>

<script type="text/javascript">
	
    $(function () 
    {
		
	
	  $.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
	  });
	  var table = $('.data-table').DataTable({
		  processing: true,
		  serverSide: true,
		  ajax: "{{ route('previsitor') }}",
		  columns: 
          [
			
			  {data: 'visitor_name', name: 'visitor_name'},
			  {data: 'date', name: 'date'},
			  {data: 'flat_no', name: 'flat_no'},
			  {data: 'contact_number', name: 'contact_number'},
              {
                data: 'status',
                name: 'status',
                render: function(data, type, row) 
                {
                    if (data == 0) 
                    {
                    return '<button class="btn btn-danger">notapproved</button>';
                    }
                    else if (data == 1) 
                    {
                    return '<button class="btn btn-success">Approved</button>';
                    }
                    return data; 
                }
              }

		   ]
	  });

	  $('#createNewProduct').click(function () 
      {
        $('#saveBtn').val("create-product");
        $('#product_id').val('');
        $('#productForm').trigger("reset");
        $('#modelHeading').html("Create New User");
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

	$('body').on('click', '.deleteProduct', function () 
    {

     

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