@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold text-primary">Supplier Management</h4>
        <button class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#supplierModal">
            <i class="fas fa-plus-circle"></i> Add Supplier
        </button>
    </div>

    <!-- Controls -->
    <div class="row mb-3">
        <div class="col-md-3">
            <label class="me-2">Show</label>
            <select id="entriesSelect" class="form-select d-inline-block w-auto">
                <option value="5">5</option>
                <option value="10" selected>10</option>
                <option value="25">25</option>
            </select>
            <label class="ms-2">entries</label>
        </div>
        <div class="col-md-5">
               <input type="text" id="searchBox" class="form-control w-100 " placeholder="Search...">
     
        </div>
        <div class="col-md-4 text-end">
          
          <div class="btn-group">
            <button id="exportExcel" class="btn btn-success btn-sm rounded-pill me-2">
                <i class="fas fa-file-excel"></i> Excel
            </button>
            <button id="exportPDF" class="btn btn-danger btn-sm rounded-pill me-2">
                <i class="fas fa-file-pdf"></i> PDF
            </button>
            <button id="printTable" class="btn btn-info btn-sm rounded-pill text-white">
                <i class="fas fa-print"></i> Print
            </button>
          </div>  

        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table id="supplierTable" class="table table-md table-bordered table-hover align-middle mb-0">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Phone</th>    
                    <th>Email</th>                
                    <th>Type</th>                    
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- JSON Data -->
<script id="supplierDataJson" type="application/json">
    {!! json_encode($suppliers->items()) !!}
</script>

<!-- Modal -->
<div class="modal fade" id="supplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-rounded">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add / Edit Supplier</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="supplierForm">
                @csrf
                <input type="hidden" id="supplier_id" name="supplier_id">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label>Name</label>
                            <input type="text" id="name" name="name" class="form-control supplier_name" required autofocus>
                        </div>
                        <div class="col-md-6">
                            <label>Contact Person</label>
                            <input type="text" id="contact_person" name="contact_person" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Phone</label>
                            <input type="text" id="phone" name="phone" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Email</label>
                            <input type="email" id="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Supplier Type</label>
                            <select id="supplier_type_id" name="supplier_type_id" class="form-select">
                                <option value="">Select</option>
                                @foreach ($supplierTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Division</label>
                            <select id="division_id" name="division_id" class="form-select">
                                <option value="">Select</option>
                                @foreach ($divisions as $d)
                                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Township</label>
                            <select id="township_id" name="township_id" class="form-select">
                                <option value="">Select</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label>Address</label>
                            <textarea id="address" name="address" class="form-control" rows="2" required></textarea>
                        </div>
                    </div>
                </div>
                 <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    <!-- Submit button with spinner -->
                    <button type="submit" class="btn btn-primary" id="saveBtn">
                      <span class="spinner-border spinner-border-sm d-none" id="saveSpinner" role="status" aria-hidden="true"></span>
                      <span id="saveText">Save</span>
                    </button>
                  </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(function () {
    let allSuppliers = JSON.parse($('#supplierDataJson').text());
    let entriesPerPage = 10;
    
    function renderTable() {
        const search = $('#searchBox').val().toLowerCase();
        const filtered = allSuppliers.filter(s =>
            s.name.toLowerCase().includes(search) ||
            (s.contact_person ?? '').toLowerCase().includes(search) ||
            (s.phone ?? '').toLowerCase().includes(search)
        );

        const display = filtered.slice(0, entriesPerPage);
        let html = display.length ? display.map((s, i) => `
            <tr>
                <td>${i+1}</td>
                <td>${s.name}</td>
                <td>${s.contact_person ?? ''}</td>
                <td>${s.phone ?? ''}</td>
                <td>${s.email ?? ''}</td>
                <td>${s.supplier_type?.name ?? ''}</td>             
                <td>
                  <div class="btn-group">
                    <button class="btn btn-sm btn-primary editBtn" data-id="${s.id}"><i class="fa fa-edit"></i> </button>
                    <button class="btn btn-sm btn-danger deleteBtn" data-id="${s.id}"><i class="fa fa-trash"></i></button>
                  </div>
                </td>
            </tr>
        `).join('') : `<tr><td colspan="10" class="text-center text-muted">No records found</td></tr>`;
        $('#supplierTable tbody').html(html);
    }

    renderTable();

    $('#searchBox').on('keyup', renderTable);
    $('#entriesSelect').on('change', function () {
        entriesPerPage = parseInt($(this).val());
        renderTable();
    });

    $('#division_id').on('change', function () {
        const id = $(this).val();
        if (!id) return $('#township_id').html('<option value="">Select</option>');
        $.get(`/get-townships/${id}`, res => {
            $('#township_id').html('<option value="">Select</option>');
            res.forEach(t => $('#township_id').append(`<option value="${t.id}">${t.name}</option>`));
        });
    });
    const supplierModal = document.getElementById('supplierModal');
       supplierModal.addEventListener('shown.bs.modal', function () {
       document.getElementById('name').focus();
  });
    // Save / Update
    $('#supplierForm').on('submit', function (e) {
    e.preventDefault();

    // Elements
    const spinner = document.getElementById('saveSpinner');
    const saveText = document.getElementById('saveText');
    const saveBtn  = document.getElementById('saveBtn');

    // Show spinner and disable button
    spinner.classList.remove('d-none');
    saveText.textContent = 'Saving...';
    saveBtn.disabled = true;

    // Prepare request
    const id = $('#supplier_id').val();
    const method = id ? 'PUT' : 'POST';
    const url = id ? `/suppliers/${id}` : '/suppliers';

    // AJAX request
    $.ajax({
        url,
        method,
        data: $(this).serialize(),
    })
    .done(res => {
        $('#supplierModal').modal('hide');
        showToast(res.message);

        // Reload supplier list
        $.get('/suppliers', data => {
            const tmp = $('<div>').html(data);
            allSuppliers = JSON.parse(tmp.find('#supplierDataJson').text());
            renderTable();
        });
    })
    .fail(() => {
        alert('Error saving supplier');
    })
    .always(() => {
        // Reset button after complete (success or fail)
        spinner.classList.add('d-none');
        saveText.textContent = 'Save';
        saveBtn.disabled = false;
    });
});


    // Edit
    $(document).on('click', '.editBtn', function () {
        let id = $(this).data('id');
        let s = allSuppliers.find(x => x.id == id);

        if (!s) return;

        $('#supplier_id').val(s.id);
        $('#name').val(s.name);
        $('#contact_person').val(s.contact_person);
        $('#phone').val(s.phone);
        $('#email').val(s.email);
        $('#address').val(s.address);
        $('#supplier_type_id').val(s.supplier_type_id);
        $('#division_id').val(s.division_id).trigger('change');

        // 🔧 Fix township load when editing
        $.get(`/get-townships/${s.division_id}`, function(townships) {
            $('#township_id').html('<option value="">Select Township</option>');
            $.each(townships, function(i, t) {
                $('#township_id').append(`<option value="${t.id}">${t.name}</option>`);
            });
            $('#township_id').val(s.township_id);
        });

        $('#supplierModal').modal('show');
        

    });

    // Delete
     $(document).on('click', '.deleteBtn', function () {
    const btn = $(this); // clicked button
    const id = btn.data('id');
    const s = allSuppliers.find(x => x.id == id);

    Swal.fire({
        title: `Delete "${s.name}"?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes",
        cancelButtonText: "No"
    }).then(res => {
        if (res.isConfirmed) {

            // Save original button content
            const originalHtml = btn.html();

            // Show spinner in button
            btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...');
            btn.prop('disabled', true);

            $.ajax({
                url: `/suppliers/${id}`,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content') // CSRF token
                },
            })
            .done(r => {
                allSuppliers = allSuppliers.filter(x => x.id != id);
                renderTable();
                showToast(r.message);
            })
            .fail(() => {
                showToast('Error deleting supplier', 'error');
            })
            .always(() => {
                // Restore original button content
                btn.html(originalHtml);
                btn.prop('disabled', false);
            });
        }
    });
});



    // Toast
     function showToast(msg, type = 'success') {
    const bgClass = type === 'error' ? 'bg-danger' : 'bg-success'; // background
    const headerHeight = $('header').outerHeight() || $('.navbar').outerHeight() || 60; // header offset

    // Create toast
    const t = $(`
        <div class="toast ${bgClass} text-white position-fixed top-0 end-0" 
             style="margin-top: ${headerHeight + 10}px; z-index: 9999;" 
             role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-body">${msg}</div>
        </div>
    `);

    $('body').append(t);

    const toast = new bootstrap.Toast(t[0], { delay: 3000 });
    toast.show();

    t.on('hidden.bs.toast', () => t.remove());
}

      

    // Excel Export
    $('#exportExcel').on('click', function(){
        const ws = XLSX.utils.json_to_sheet(allSuppliers.map(s => ({
            Name:s.name,Contact:s.contact_person,Phone:s.phone,Email:s.email,
            Type:s.supplier_type?.name,Division:s.division?.name,Township:s.township?.name,Address:s.address
        })));
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Suppliers");
        XLSX.writeFile(wb, "suppliers.xlsx");
    });

    // PDF Export
    $('#exportPDF').on('click', function(){
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        doc.text("Supplier List", 14, 15);
        doc.autoTable({startY:20, head:[["Name","Contact","Phone","Email","Type","Division","Township","Address"]],
            body: allSuppliers.map(s=>[
                s.name,s.contact_person,s.phone,s.email,
                s.supplier_type?.name,s.division?.name,s.township?.name,s.address
            ])
        });
        doc.save("suppliers.pdf");
    });

    // Print
    $('#printTable').on('click', function(){
        const table = $('#supplierTable')[0].outerHTML;
        const win = window.open("");
        win.document.write(`<html><head><title>Print</title></head><body>${table}</body></html>`);
        win.print();
        win.close();
    });
});
</script>
@endpush
