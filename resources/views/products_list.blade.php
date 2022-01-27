<!DOCTYPE html>
<html>

<head>
  <title>Products</title>

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">


  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>



</head>

<body>

  <div class="container mt-4">

    <div class="col-md-12 mt-1 mb-2"><button type="button" id="addNewBook" class="btn btn-primary">Add Product</button></div>

    <div class="card">

      <div class="card-header text-center font-weight-bold">
        <h2>Products</h2>
      </div>

      <div class="card-body">

        <table class="table table-bordered" id="datatable-ajax-crud">
          <thead>
            <tr>
              <th>Id</th>
              <th>Image</th>
              <th>Product Name</th>
              <th>Description</th>
              <th>Price </th>
              <th>Min Quantity</th>
              <th>Action</th>
            </tr>
          </thead>
        </table>

      </div>

    </div>
    <!-- boostrap add and edit book model -->
    <div class="modal fade" id="ajax-book-model" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="ajaxBookModel"></h4>
          </div>
          <div class="modal-body">
            <form action="javascript:void(0)" id="addEditBookForm" name="addEditBookForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
           
              <input type="hidden" name="id" id="id">
              <div class="form-group">
                <label for="name" class="col-sm-4 control-label">Product Name</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter Product Name" maxlength="50" required="">
                </div>
              </div>

              <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Description</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" id="description" name="description" placeholder="Enter Description" maxlength="50" required="">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label">product Image</label>
                <div class="col-sm-6 pull-left">
                  <input type="file" class="form-control" id="image" name="image" required="">
                </div>
                <div class="col-sm-6 pull-right">
                  <img id="preview-image" src="https://commercial.bunn.com/img/image-not-available.png" alt="preview image" style="max-height: 250px;">
                </div>
              </div>


              <div class="form-group">
                <label class="col-sm-2 control-label">Price</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" id="price" name="price" placeholder="Enter Price" required="">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label"> Min Quantity</label>
                <div class="col-sm-12">
                  <input type="number" class="form-control" id="min_qty" name="min_qty" placeholder="Enter  Min Quantity" required="">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Max Quantity</label>
                <div class="col-sm-12">
                  <input type="number" class="form-control" id="max_qty" name="max_qty" placeholder="Enter  Max Quantity" required="">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">Status</label>
                <div class="col-sm-12">
                  <select  class="form-control" name="status" aria-label="Default select example">
                    <option selected value="1">Active</option>
                    <option value="0">Inactive</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary" id="btn-save" value="addNewBook">Save changes
                </button>
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>
    <!-- end bootstrap model -->
   

    <script type="text/javascript">
      $(document).ready(function() {

        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $('#image').change(function() {

          let reader = new FileReader();

          reader.onload = (e) => {

            $('#preview-image').attr('src', e.target.result);
          }

          reader.readAsDataURL(this.files[0]);

        });

        $('#datatable-ajax-crud').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('home') }}",
          columns: [{
              data: 'id',
              name: 'id',
              'visible': false
            },
            {
              data: 'image',
              name: 'image',
              orderable: false
            },
            {
              data: 'product_name',
              name: 'product_name'
            },
            {
              data: 'description',
              name: 'description'
            },
            {
              data: 'price',
              name: 'price'
            },
            {
              data: 'min_qty',
              name: 'min_qty'
            },
            {
              data: 'action',
              name: 'action',
              orderable: false
            },
          ],
          order: [
            [0, 'desc']
          ]
        });


        $('#addNewBook').click(function() {
          $('#addEditBookForm').trigger("reset");
          $('#ajaxBookModel').html("Add Book");
          $('#ajax-book-model').modal('show');
          $("#image").attr("required", "true");
          $('#id').val('');
          $('#preview-image').attr('src', 'https://commercial.bunn.com/img/image-not-available.png');


        });

        $('body').on('click', '.edit', function() {

          var id = $(this).data('id');

          // ajax
          $.ajax({
            type: "POST",
            url: "{{ url('edit-book') }}",
            data: {
              id: id
            },
            dataType: 'json',
            success: function(res) {
              $('#ajaxBookModel').html("Edit Book");
              $('#ajax-book-model').modal('show');
              $('#id').val(res.id);
              $('#product_name').val(res.product_name);
              $('#description').val(res.description);
              $('#price').val(res.price);
              $('#min_qty').val(res.min_qty);
              $('#max_qty').val(res.max_qty);
              $('#status').val(res.status);
              $('#image').removeAttr('required');

            }
          });

        });

        $('body').on('click', '.delete', function() {

          if (confirm("Delete Record?") == true) {
            var id = $(this).data('id');

            // ajax
            $.ajax({
              type: "POST",
              url: "{{ url('delete-book') }}",
              data: {
                id: id
              },
              dataType: 'json',
              success: function(res) {

                var oTable = $('#datatable-ajax-crud').dataTable();
                oTable.fnDraw(false);
              }
            });
          }

        });

        $('#addEditBookForm').submit(function(e) {

          e.preventDefault();

          var formData = new FormData(this);

          $.ajax({
            type: 'POST',
            url: "{{ url('add-update-book')}}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
              $("#ajax-book-model").modal('hide');
              var oTable = $('#datatable-ajax-crud').dataTable();
              oTable.fnDraw(false);
              $("#btn-save").html('Submit');
              $("#btn-save").attr("disabled", false);
            },
            error: function(data) {
              console.log(data);
            }
          });
        });
      });
    </script>
  </div>
</body>

</html>