@extends('admin.layouts.app')
@section('content')
@php
$townshipsByDivision = \App\Models\Township::all()->groupBy('division_id');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Customer Management</title>

<!-- MDB UI Kit -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>

<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/a2e0f1f0e4.js" crossorigin="anonymous"></script>

<!-- jsPDF + AutoTable -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

<!-- SheetJS -->
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> -->

</head>
<body class="bg-light ">

<div class="container mt-2">
  <h3 class="text-center text-primary mt-0 p-0"><i class="fas fa-users me-2"></i>Customer Management</h3>
            <button class="btn btn-success mb-1" id="addCustomerBtn"><i class="fas fa-plus me-2"></i>Add Customer</button>
   
  <div class="btn-group mb-1">
  
            <button class="btn btn-primary" id="exportExcel"><i class="fas fa-file-excel me-1"></i>Excel</button>
            <button class="btn btn-danger" id="exportPDF"><i class="fas fa-file-pdf me-1"></i>PDF</button>
        </div>
  <div class="card shadow-1">
    <div class="card-body">

      <!-- Toolbar -->
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div class="w-50">
          
         </div>            
        <div class="btn-group">
            <!-- <button class="btn btn-success" id="addCustomerBtn"><i class="fas fa-plus me-2"></i>Add Customer</button>
  
            <button class="btn btn-primary" id="exportExcel"><i class="fas fa-file-excel me-1"></i>Excel</button>
            <button class="btn btn-danger" id="exportPDF"><i class="fas fa-file-pdf me-1"></i>PDF</button> -->
        </div>
       
        <div class="d-flex gap-2 flex-wrap w-100 w-md-auto mt-0">
          <input type="text" id="search" class="form-control" placeholder="🔍 Search name or phone">
           
        </div>
      </div>

     <!-- ✅ Desktop / Tablet view (md and up) -->
<!-- Desktop / Tablet Table (md and up) -->
<div class="table-responsive d-none d-md-block">
  <table class="table table-striped table-hover align-middle" id="customer-table">
    <thead class="table-primary">
      <tr>
        <th>#</th>
        <th>Customer Code</th>
        <th>Customer Type</th>
        <th>Customer Name</th>
        <th>Phone</th>
        <!-- <th>Email</th> -->
        <!-- <th>Division</th>
        <th>Township</th>
        <th>Address</th> -->
        <th>Actions</th>
      </tr>
    </thead>
    <tbody id="customerTableBody">
      @foreach ($customers as $customer)
      <tr data-id="{{ $customer->id }}">
        <td>{{ ($customers->currentPage() - 1) * $customers->perPage() + $loop->iteration }}</td>
        <td>{{ $customer->customer_code }}</td>
        <td>{{ $customer->customer_type }}</td>
        <td>{{ $customer->name }}</td>
        <td>{{ $customer->phone }}</td>
        <!-- <td>{{ $customer->email }}</td> -->
        <!-- <td>{{ $customer->division_name ?? '-' }}</td>
        <td>{{ $customer->township_name ?? '-' }}</td>
        <td>{{ $customer->address }}</td> -->
        <td>
          <button class="btn btn-sm btn-warning edit-btn"
                data-id="{{ $customer->id }}"
                data-name="{{ $customer->name }}"
                data-phone="{{ $customer->phone }}"
                data-email="{{ $customer->email }}"
                data-customer_type_id="{{ $customer->customer_type_id }}"
                data-division_id="{{ $customer->division_id }}"
                data-township_id="{{ $customer->township_id }}"
                data-address="{{ $customer->address }}"
                data-points="{{ $customer->points }}"
                data-member_card_id="{{ $customer->member_card_id }}">
            <i class="fas fa-edit"></i>
          </button>
          <button class="btn btn-danger btn-sm delete-btn"><i class="fas fa-trash"></i></button>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<!-- Mobile Card View (below md) -->
<div class="d-block d-md-none" id="customer-card-view">
  @foreach ($customers as $customer)
  <div class="card mb-3 shadow-sm" data-id="{{ $customer->id }}">
    <div class="card-body">
      <!-- Header: Name + Code + Type -->
      <h5 class="card-title">
        {{ $customer->name }}
        <small class="text-muted">({{ $customer->customer_code }} | {{ $customer->customer_type }})</small>
      </h5>

      <!-- All other fields -->
      <p class="mb-1"><strong>Phone:</strong> {{ $customer->phone }}</p>
      <p class="mb-1"><strong>Email:</strong> {{ $customer->email ?? '-' }}</p>
      <!-- <p class="mb-1"><strong>Division:</strong> {{ $customer->division_name ?? '-' }}</p>
      <p class="mb-1"><strong>Township:</strong> {{ $customer->township_name ?? '-' }}</p>
      <p class="mb-2"><strong>Address:</strong> {{ $customer->address ?? '-' }}</p> -->

      <!-- Actions -->
      <div class="d-flex gap-2">
        <button class="btn btn-warning btn-sm edit-btn"><i class="fas fa-edit"></i></button>
        <button class="btn btn-danger btn-sm delete-btn"><i class="fas fa-trash"></i></button>
      </div>
    </div>
  </div>
  @endforeach
</div>
      <div class="d-flex justify-content-center mt-3">
        {!! $customers->links('pagination::bootstrap-5') !!}
      </div>
    </div>
  </div>
</div>
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1060;">
    <div id="mdbToast" class="toast align-items-center text-white border-0" role="alert" data-mdb-delay="3000">
      <div class="d-flex">
        <div class="toast-body fw-semibold" id="toastMessage"></div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-mdb-dismiss="toast"></button>
      </div>
    </div>
  </div>
<!-- Add/Edit Modal -->
<div class="modal mt-0" id="customerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <form id="customer-form" method="POST">     
      @csrf
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="modalTitle">Add Customer</h5>
          <button type="button" class="btn-close" data-mdb-dismiss="modal"></button>
        </div>
        <div class="modal-body row"> 
          <div class="col-md-6 mb-1">
            <label class="form-label fw-semibold">Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
    
          </div>
      
          <div class="col-md-6 mb-1">
            <label class="form-label fw-semibold">Phone</label>
            <input type="text" name="phone" id="phone" class="form-control">
          </div>
          <div class="col-md-6 mb-1">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" name="email" id="email" class="form-control">
          </div>
          <div class="col-md-6 mb-1">
            <label class="form-label fw-semibold">Customer Type</label>
            <select id="customer_type_id" name="customer_type_id" class="form-select" required>
              <option value="">Choose...</option>
              @foreach($customerTypes as $t)
                <option value="{{ $t->id }}">{{ $t->name }}</option>
              @endforeach
            </select>
          </div>
          
          <!-- Division / Township -->
          
         
           <div class="col-md-6 mb-1" id="point-fields" style="display:none;">
            <label class="form-label fw-semibold">Collection Points</label>
            <input type="number" name="points" id="points" class="form-control">
           </div>
  
         
           <div class="col-md-6 mb-1" id="member-fields" style="display:none;">
            <label class="form-label fw-semibold">Member Card</label>
            <select id="member_card_id" name="member_card_id" class="form-select" required>
              <option value="">Choose...</option>
              @foreach($mercards as $m)
                <option value="{{ $m->id }}">{{ $m->card_number }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6 mb-1">
             <label for="division" class="form-label fw-semibold" >Division</label>
              <select name="division_id" id="division" class="form-select">
                  <option value="">Select Division</option>
                  @foreach($divisions as $division)
                  <option value="{{ $division->id }}">{{ $division->name }}</option>
                  @endforeach
              </select>
          </div>
           <div class="col-md-6 mb-1">
            <label for="township" class="form-label fw-semibold">Township</label>
            <select name="township_id" id="township" class="form-select" disabled>
                <option value="">Select Township</option>
            </select>
          </div>
          <div class="col-md-12 mb-1">
            <label class="form-label fw-semibold">Address</label>
            <textarea class="form-control" name="address" id="address"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-rounded" data-mdb-dismiss="modal">Cancel</button>
          <button type="submit" id="save-btn" class="btn text-white shadow-3 btn-rounded btn-primary">
              <i class="fas fa-save me-2"></i> Save
          </button> 
          </div>
        </div>
      </form>
    </div>
    </div>
<!-- ✅ Edit Customer Modal -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <form id="edit-customer-form" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header bg-warning text-white">
          <h5 class="modal-title">Edit Customer</h5>
          <button type="button" class="btn-close" data-mdb-dismiss="modal"></button>
        </div>

        <div class="modal-body row">

          <input type="hidden" id="edit_id" name="id">

          <div class="col-md-6 mb-2">
           <label class="form-label fw-semibold">Name</label>
           <input type="text" name="name" id="edit_name" class="form-control" required>
          </div>

          <div class="col-md-6 mb-2">
            <label class="form-label fw-semibold">Phone</label>
            <input type="text" name="phone" id="edit_phone" class="form-control">
          </div>

          <div class="col-md-6 mb-2">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" name="email" id="edit_email" class="form-control">
          </div>

          <div class="col-md-6 mb-2">
            <label class="form-label fw-semibold">Customer Type</label>
            <select id="edit_customer_type_id" name="customer_type_id" class="form-select" required>
              <option value="">Choose...</option>
              @foreach($customerTypes as $t)
                <option value="{{ $t->id }}">{{ $t->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-6 mb-2">
            <label class="form-label fw-semibold">Division</label>
            <select id="edit_division_id" name="division_id" class="form-select">
              <option value="">Select Division</option>
              @foreach($divisions as $division)
                <option value="{{ $division->id }}">{{ $division->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-6 mb-2">
            <label class="form-label fw-semibold">Township</label>
            <select id="edit_township_id" name="township_id" class="form-select" disabled>
              <option value="">Select Township</option>
            </select>
          </div>

          <div class="col-md-12 mb-2">
            <label class="form-label fw-semibold">Address</label>
            <textarea name="address" id="edit_address" class="form-control"></textarea>
          </div>

          <div class="col-md-6 mb-2" id="edit_point_fields" style="display:none;">
            <label class="form-label fw-semibold">Collection Points</label>
            <input type="number" name="points" id="edit_points" class="form-control">
          </div>

          <div class="col-md-6 mb-2" id="edit_member_fields" style="display:none;">
            <label class="form-label fw-semibold">Member Card</label>
            <select id="edit_member_card_id" name="member_card_id" class="form-select">
              <option value="">Choose...</option>
              @foreach($mercards as $m)
                <option value="{{ $m->id }}">{{ $m->card_number }}</option>
              @endforeach
            </select>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-rounded" data-mdb-dismiss="modal">
            Cancel
          </button>
          <button type="submit" class="btn btn-warning text-white shadow-3 btn-rounded">
            <i class="fas fa-save me-2"></i> Update
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="delete-form" method="POST">
      @csrf @method('DELETE')
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">Delete Customer</h5>
          <button type="button" class="btn-close" data-mdb-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete <strong id="delete-name"></strong>?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- ✅ Toast Notification -->
<script>
  function showToast(message, type='success') {
      const toastEl = document.getElementById('mdbToast');
      const toastBody = document.getElementById('toastMessage');

      // Remove old classes
      toastEl.classList.remove('bg-success','bg-danger');

      // Add class based on type
      toastEl.classList.add(type==='success' ? 'bg-success' : 'bg-danger');

      // Set message
      toastBody.textContent = message;

      // Show toast
      const toastInstance = new mdb.Toast(toastEl);
      toastInstance.show();
    }
// Initialize modals
const customerModal = new mdb.Modal(document.getElementById('customerModal'));
const deleteModal = new mdb.Modal(document.getElementById('deleteModal'));

// Autofocus
document.getElementById('customerModal').addEventListener('shown.mdb.modal', ()=> document.getElementById('name').focus());

// Add Customer
document.getElementById('addCustomerBtn').addEventListener('click', ()=>{
  const form = document.getElementById('customer-form');
  const saveBtn = document.getElementById('save-btn');
  // saveBtn.disabled = true;
  saveBtn.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i> Saving...`;

  form.reset();
  ///form.action = "{{ route('customers.store') }}"; 
  form.querySelectorAll('input[name="_method"]').forEach(e=>e.remove());
  document.getElementById('modalTitle').textContent = "Add Customer";
  document.getElementById('save-btn').textContent = "Save";
  customerModal.show(); 
});

document.getElementById('customer-form').addEventListener('submit', async function(e){
    e.preventDefault();

    const saveBtn = document.getElementById('save-btn');
    saveBtn.disabled = true;
    saveBtn.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i> Saving...`;

    saveBtn.disabled = true;

    const form = e.target;
    const formData = new FormData(form);

    try {
      const response = await fetch('{{ route("customers.store") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: formData
      });

      if (!response.ok) throw new Error('Failed to save customer');

      showToast('Customer saved successfully!', 'success');
      form.reset();
       setTimeout(() => {
          window.location.href = 'customers'; // Home page URL
      }, 1500);

    } catch (err) {
      console.error(err);
      showToast(err.message || 'Error saving customer', 'error');
    }
  });

$('#division').on('change', function() {
    let divisionId = $(this).val();
    $('#township').empty().append('<option value="">Select Township</option>');
    if (divisionId) {
      $.get('/get-townships/' + divisionId, function(data) {
        data.forEach(function(township) {
          $('#township').append('<option value="' + township.id + '">' + township.name + '</option>');
        });
        $('#township').prop('disabled', false);
      });
    } else {
      $('#township').prop('disabled', true);
    }
  });


  // 🔹 EDIT FORM: When Division changes
  $('#edit_division_id').on('change', function() {
    let divisionId = $(this).val();
    $('#edit_township_id').empty().append('<option value="">Select Township</option>');
    if (divisionId) {
      $.get('/get-townships/' + divisionId, function(data) {
        data.forEach(function(township) {
          $('#edit_township_id').append('<option value="' + township.id + '">' + township.name + '</option>');
        });
        $('#edit_township_id').prop('disabled', false);
      });
    } else {
      $('#edit_township_id').prop('disabled', true);
    }
  });


  // 🔹 WHEN EDIT BUTTON CLICKED
  $(document).on('click', '.edit-btn', function() {
  
    let id               = $(this).data('id');
    let name             = $(this).data('name');
    let phone            = $(this).data('phone');
    let email            = $(this).data('email');
    let customer_type_id = $(this).data('customer_type_id');
    let division_id      = $(this).data('division_id');
    let township_id      = $(this).data('township_id');
    let address          = $(this).data('address');
    let points           = $(this).data('points');
    let member_card_id   = $(this).data('member_card_id');

    // Fill data

    $('#edit_name').val(name);
    $('#edit_phone').val(phone);
    $('#edit_email').val(email);
    $('#edit_address').val(address);
    $('#edit_points').val(points);
    $('#edit_member_card_id').val(member_card_id);
    

    $('#edit_customer_type_id').val(customer_type_id).trigger('change');

    // Set division and trigger change to load townships
    $('#edit_division_id').val(division_id).trigger('change');

     if(township_id) {
        setTimeout(function(){
            $('#edit_township_id').val(township_id).trigger('change');
        }, 200); // adjust delay if necessary
    }

    // Show modal
    $('#editCustomerModal').modal('show');
    
    // Load townships for selected division and set selected township
  });
  // Reset modal to Add Mode when closed
  $('#customerModal').on('hidden.bs.modal', function () {
    $('#modalTitle').text('Add Customer');
    $('#save-btn').html('<i class="fas fa-save me-2"></i> Save');
    $('#customer-form').attr('action', '/customers');
    $('input[name="_method"]').remove();
    $('#customer-form')[0].reset();
  });
// Delete
document.querySelectorAll('.delete-btn').forEach(btn=>{
  btn.addEventListener('click', e=>{
    const tr = e.target.closest('[data-id]');
    const id = tr.dataset.id;
    const name = tr.querySelector('td:nth-child(2)')?.textContent || tr.querySelector('.card-title')?.textContent;
    document.getElementById('delete-name').textContent = name;
    document.getElementById('delete-form').action = '/customers/' + id;
    deleteModal.show();
  });
});

// Search
document.getElementById('search').addEventListener('input', e=>{
  const query = e.target.value.toLowerCase();
  document.querySelectorAll('#customer-table tbody tr').forEach(tr=>{
    tr.style.display = tr.textContent.toLowerCase().includes(query) ? '' : 'none';
  });
  document.querySelectorAll('#customer-card-view .card').forEach(card=>{
    card.style.display = card.textContent.toLowerCase().includes(query) ? '' : 'none';
  });
});

// Excel Export
document.getElementById('exportExcel').addEventListener('click', ()=>{
  const wb = XLSX.utils.table_to_book(document.getElementById('customer-table'), {sheet: "Customers"});
  XLSX.writeFile(wb, 'customers.xlsx');
});

// PDF Export
document.getElementById('exportPDF').addEventListener('click', ()=>{
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();
  doc.autoTable({ html: '#customer-table', startY: 10 });
  doc.save('customers.pdf');
});

var townshipsByDivision = @json($townshipsByDivision);

    $(document).ready(function() {
        var $division = $('#division');
        var $township = $('#township');

        function populateTownship(divisionId, selectedTownshipId = null) {
            if (divisionId && townshipsByDivision[divisionId]) {
                $township.prop('disabled', false).empty().append('<option value="">Select Township</option>');
                townshipsByDivision[divisionId].forEach(function(t) {
                    var selected = selectedTownshipId == t.id ? 'selected' : '';
                    $township.append('<option value="'+t.id+'" '+selected+'>'+t.name+'</option>');
                });
            } else {
                $township.empty().append('<option value="">Select Township</option>').prop('disabled', true);
            }
        }

        // Division change
        $division.on('change', function() {
            populateTownship($(this).val());
        });
    });
 // Toggle credit fields
function toggleCreditFields() {

    if ($('#customer_type_id').val() === '3') {   
        $('#point-fields').show();
        $('#member-fields').show();
    } else {
        $('#point-fields').hide();
        $('#member-fields').val('');
    }
    $('#customer_type_id').on('change', toggleCreditFields);

  }


toggleCreditFields();
</script>

</body>
</html>
@endsection
