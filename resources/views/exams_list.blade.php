<!DOCTYPE html>
<html>

<head>
  <title>Exam management</title>

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">


  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>



</head>

<body>

  <div class="container mt-4">

    <div class="col-md-12 mt-1 mb-2"><button type="button" id="addNewQuestion" class="btn btn-primary">Add Question</button></div>

    <div class="card">

      <div class="card-header text-center font-weight-bold">
        <h2>Exam management</h2>
      </div>

      <div class="card-body">

        <table class="table table-bordered" id="datatable-ajax-crud">
          <thead>
            <tr>
              <th>Id</th>
              <th>category</th>
              <th>question</th>
              <th>option1</th>
              <th>option2 </th>
              <th>option3</th>
              <th>option4</th>
              <th>Action</th>
            </tr>
          </thead>
        </table>

      </div>

    </div>
    <!-- boostrap add and edit book model -->
    <div class="modal fade" id="ajax-exam-model" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="ajaxExamModel"></h4>
          </div>
          <div class="modal-body">
            <form action="javascript:void(0)" id="addEditExamForm" name="addEditExamForm" class="form-horizontal" method="POST">

              <input type="hidden" name="id" id="id">
              <div class="form-group">
                <label for="category" class="col-sm-2 control-label">Category</label>
                <div class="col-sm-12">
                  <select class="form-control" id="category" required name="category">
                    <option value="technical">Technical</option>
                    <option value="aptitude">Aptitude</option>
                    <option value="logical">Logical</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="question" class="col-sm-2 control-label">Question</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" id="question" required placeholder="Exam Question" name="question" v-model="newExam.question">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Option 1</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" id="option1" name="option1" placeholder="option1" required="">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Option 2</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" id="option2" name="option2" placeholder="option2" required="">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Option 3</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" id="option3" name="option3" placeholder="option3" required="">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Option 4 </label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" id="option4" name="option4" placeholder="option4" required="">
                </div>
              </div>

              <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary" id="btn-save" value="addNewQuestion">Save changes
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


        $('#datatable-ajax-crud').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('home') }}",
          columns: [{
              data: 'id',
              name: 'id',

            },
            {
              data: 'category',
              name: 'category',
            },
            {
              data: 'question',
              name: 'question'
            },
            {
              data: 'option1',
              name: 'option1'
            },
            {
              data: 'option2',
              name: 'option2'
            },
            {
              data: 'option3',
              name: 'option3'
            },
            {
              data: 'option4',
              name: 'option4'
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


        $('#addNewQuestion').click(function() {
          $('#addEditExamForm').trigger("reset");
          $('#ajaxExamModel').html("Add Question");
          $('#ajax-exam-model').modal('show');
          $('#id').val('');


        });

        $('body').on('click', '.edit', function() {

          var id = $(this).data('id');

          // ajax
          $.ajax({
            type: "POST",
            url: "{{ url('edit-question') }}",
            data: {
              id: id
            },
            dataType: 'json',
            success: function(res) {
              $('#ajaxExamModel').html("Edit Book");
              $('#ajax-exam-model').modal('show');
              $('#id').val(res.id);
              $('#category').val(res.category);
              $('#question').val(res.question);
              $('#option1').val(res.option1);
              $('#option2').val(res.option2);
              $('#option3').val(res.option3);
              $('#option4').val(res.option4);

            }
          });

        });

    

        $('#addEditExamForm').submit(function(e) {

          e.preventDefault();

          var formData = new FormData(this);

          $.ajax({
            type: 'POST',
            url: "{{ url('add-update-question')}}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
              $("#ajax-exam-model").modal('hide');
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