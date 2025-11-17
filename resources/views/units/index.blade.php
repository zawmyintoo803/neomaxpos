@extends('admin.layouts.app')

@section('title','User Management')

@section('content')
<div class="container mt-4">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-primary">Units</h3>
        <button class="btn btn-primary btn-sm" data-mdb-toggle="modal" data-mdb-target="#userModal">
            <i class="fas fa-plus me-1"></i> Add Unit
        </button>
    </div>

    <!-- Users Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped mb-0 table-sm">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>#</th>
                        <th>Unit Name</th>                       
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    @foreach($units as $index => $unit)
                        <tr id="userRow{{ $unit->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $unit->unit_name }}</td>                            
                            <td>
                                <button class="btn btn-warning btn-sm editBtn"
                                    data-id="{{ $unit->id }}"
                                    data-unit_name="{{ $unit->unit_name }}"                              
                                    data-mdb-toggle="modal" data-mdb-target="#userModal">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $unit->id }}">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- User Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="userForm">
      <input type="hidden" id="userId" name="userId">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="userModalLabel">Add Unit</h5>
          <button type="button" class="btn-close" data-mdb-dismiss="modal"></button>
        </div>
        <div class="modal-body">

            <!-- Loading Spinner -->
            <div id="loadingSpinner" class="text-center my-3 d-none">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Saving Unit, please wait...</p>
            </div>

            <!-- Form Content -->
            <div id="formContent">
                <div class="mb-2">
                    <label>Unit Name &nbsp;<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="unit_name" id="unit_name" required>
                </div>
            
            </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function(){

    // Open modal for Add/Edit
    $('#userModal').on('show.mdb.modal', function(event){
        var button = $(event.relatedTarget);
        var modal = $(this);

        if(button.hasClass('editBtn')){
            modal.find('#userModalLabel').text('Edit User');
            modal.find('#userId').val(button.data('id'));
            modal.find('#unit_name').val(button.data('unit_name'));
        } else {
            modal.find('#userModalLabel').text('Add Unit');
            modal.find('#userForm')[0].reset();
            modal.find('#userId').val('');
        }
    });

    // Submit form (AJAX GET)
    $('#userForm').on('submit', function(e){
        e.preventDefault();
        var userId = $('#userId').val();
        var actionUrl = userId ? '/units/' + userId + '/update' : '/units/store';

        $('#formContent').addClass('d-none');
        $('#loadingSpinner').removeClass('d-none');

        $.ajax({
            url: actionUrl,
            method: 'GET',
            data: $(this).serialize(),
            success: function(response){
                var unit = response.unit;
                var row = `
                <tr id="userRow${unit.id}">
                    <td>#</td>
                    <td>${unit.unit_name}</td>
                   
                    <td>
                        <button class="btn btn-warning btn-sm editBtn" 
                            data-id="${unit.id}" 
                            data-name="${unit.unit_name}" 
                            data-mdb-toggle="modal" data-mdb-target="#userModal">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-danger btn-sm deleteBtn" data-id="${unit.id}">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                </tr>`;

                if(userId){ 
                    $('#userRow'+userId).replaceWith(row);
                } else {
                    $('#userTableBody').append(row);
                }

                $('#userModal').modal('hide');
                $('#formContent').removeClass('d-none');
                $('#loadingSpinner').addClass('d-none');

                Swal.fire({
                    icon: 'success',
                    title: response.message,
                    timer: 1500,
                    showConfirmButton: false
                });
            },
            error: function(xhr){
                $('#formContent').removeClass('d-none');
                $('#loadingSpinner').addClass('d-none');

                if(xhr.status === 422){
                    let errors = xhr.responseJSON.errors;
                    let messages = Object.values(errors).map(e => e.join('\n')).join('\n');
                    Swal.fire({ icon: 'error', title: 'Validation Error', text: messages });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Unable to save user.' });
                }
            }
        });
    });

    // Delete user
    $(document).on('click', '.deleteBtn', function(){
        var id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this unit?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if(result.isConfirmed){
                $.ajax({
                    url: '/units/' + id + '/delete',
                    method: 'GET',
                    success: function(response){
                        $('#userRow'+id).remove();
                        Swal.fire({ icon: 'success', title: response.message, timer: 1200, showConfirmButton: false });
                    },
                    error: function(){
                        Swal.fire({ icon: 'error', title: 'Error deleting user!' });
                    }
                });
            }
        });
    });

    // Toggle status
    $(document).on('change', '.status-toggle', function(){
        var userId = $(this).data('id');
        $.ajax({
            url: '/units/' + userId + '/toggle-status',
            method: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(response){
                Swal.fire({
                    icon: 'success',
                    title: response.message,
                    timer: 1000,
                    showConfirmButton: false
                });
            },
            error: function(){
                Swal.fire({ icon: 'error', title: 'Error updating status!' });
            }
        });
    });
});
</script>
@endpush
