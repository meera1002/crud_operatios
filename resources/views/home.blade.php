@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
             <div class="card">
                <div class="card-header">Activities</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="loading"></div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th width="300px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($userActivities) && $userActivities->count())
                                @foreach($userActivities as $key => $value)
                                    <tr>
                                        <td>{{ $value->activity_name }}</td>
                                        <td>
                                            {{--<button class="btn btn-danger">Edit</button>--}}
                                            <a href="" id="editActivity" class="btn btn-primary" data-toggle="modal" data-target='#edit-modal' data-id="{{ $value->id }}">Edit</a>
                                        </td>
                                     </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="10">No data found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    {!! $userActivities->links() !!}
                    <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label" aria-hidden="true">
                      <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="edit-modal-label">Edit Activity</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body" id="attachment-body-content">
                            <form id="edit-form" class="form-horizontal" method="POST" action="">
                              <input type="hidden" id="id" name="id" value="">
                              <div class="card mb-0">
                                <div class="card-body">
                                  <!-- name -->
                                  <div class="form-group">
                                    <label class="col-form-label" for="activity_name">Activity</label>
                                    <input type="text" name="activity_name" class="form-control" id="activity_name" required autofocus>
                                  </div>
                                  <!-- /name -->
                                </div>
                              </div>
                            </form>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="submit" data-dismiss="modal">Edit Activity</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                @if(!in_array(Auth::user()->type,['diy','music']))
                    <button id="btn-more" data-type="{{ Auth::user()->type }}" class="btn btn-primary">Load More</button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script>
    $(document).ready(function () {

    $.ajaxSetup({
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });

    $('body').on('click', '#submit', function (event) {
        event.preventDefault()
        var id = $("#id").val();
        var name = $("#activity_name").val();

        $.ajax({
          url: 'activity/' + id,
          type: "POST",
          data: {
            id: id,
            activity_name: name
          },
          dataType: 'json',
          success: function (data) {
              $('#edit-form').trigger("reset");
              $('#edit-modal').modal('hide');
              alert('Updated');
              window.location.reload(true);
          }
      });
    });

    $('body').on('click', '#editActivity', function (event) {

        event.preventDefault();
        var id = $(this).data('id');
        $.get('activity/' + id + '/edit', function (data) {
             $('#edit-modal').modal('show');
             $('#id').val(data.data.id);
             $('#activity_name').val(data.data.activity_name);
         })
    });
    });

    $(document).on('click','#btn-more',function(){
    var type = $(this).data('type');
        $('.loading').html('<div class="alert alert-success" role="alert">Loading...! Please wait<div>');
    $.ajax({
    url : 'fetch-activity',
    method : "POST",
    data : {type:type},
    dataType : "json",
    success : function (data)
    {
        if(data.success){
            $('.loading').html('');
            alert('New activity added');
            window.location.reload(true);
        }
        else {
            $('.loading').html('');
            alert('Exceeds the limit of new activity');
        }
    }
    });
    });

    </script>
@endpush
