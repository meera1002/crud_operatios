@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Activities</div>

                <div class="card-body">
                    @if (session('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>User Name</th>
                                <th>Type</th>
                                <th>Activity</th>
                                <th colspan="2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($userActivities) && $userActivities->count())
                                @foreach($userActivities as $key => $value)
                                    <tr>
                                        <td>{{ $value->user->name }}</td>
                                        <td>{{ $value->user->type }}</td>
                                        <td>{{ $value->activity_name }}</td>
                                        <td>
                                            <a href="" id="viewActivity" class="btn btn-primary" data-toggle="modal" data-target='#view-modal' data-id="{{ $value->id }}">View</a>
                                        </td>
                                        <td>
                                            <form action="/admin/activity/delete/{{ $value->id}}" method="post">
                                              @csrf
                                              <button class="btn btn-danger" type="submit">Delete</button>
                                            </form>
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
                    <div class="modal fade" id="view-modal" tabindex="-1" role="dialog" aria-labelledby="view-modal-label" aria-hidden="true">
                      <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="edit-modal-label">View Activity</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body" id="attachment-body-content">
                              <input type="hidden" id="id" name="id" value="">
                              <div class="card mb-0">
                                <div class="card-body">

                                  <div class="form-group">
                                    <label class="col-form-label" for="user_name">User Name</label>
                                    <input type="text" name="user_name" class="form-control" id="user_name" readonly>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-form-label" for="type">Type</label>
                                    <input type="text" name="type" class="form-control" id="type" readonly>
                                  </div>
                                  <!-- name -->
                                  <div class="form-group">
                                    <label class="col-form-label" for="activity_name">Activity</label>
                                    <input type="text" name="activity_name" class="form-control" id="activity_name" readonly>
                                  </div>
                                  <!-- /name -->
                                </div>
                              </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
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

    $('body').on('click', '#viewActivity', function (event) {

        event.preventDefault();
        var id = $(this).data('id');
        $.get('admin/activity/' + id, function (data) {
             $('#view-modal').modal('show');
             $('#id').val(data.data.id);
             $('#activity_name').val(data.data.activity_name);
             $('#user_name').val(data.data.user.name)
             $('#type').val(data.data.user.type);
         })
    });
    });

    </script>
@endpush
