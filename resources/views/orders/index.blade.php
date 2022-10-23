@extends('templates.app')
@section('title') Orders @endsection
@section('datatable')
<link rel="stylesheet" href="{{ asset('simple-datatables/style.css') }}">
<link rel="stylesheet" href="{{ asset('simple-datatables/simple-datatables.css') }}">
@endsection
@section('content')
<div class="container mt-5">
    <div class="card">
        <h3 class="card-header bg-info text-white text-center p-3">Order List</h3>
        <div class="card-body text-white">
            <div class="row">
                <div class="col-md-3">
                    <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#addOrderModal">Add New Order</button>
                </div>
            </div>
            <div class="row mt-3">
                <div id="loadingDiv" class="d-flex justify-content-center d-none">
                    <div class="spinner-grow text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-secondary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-danger" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-warning" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-info" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-light" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-dark" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <table id="orderTables" class="table table-striped table-responsive">

                </table>
            </div>
        </div>
    </div>
</div>
<div class="toast-container position-fixed p-3 top-0 start-50 translate-middle-x">
    <div id="toastAlert" class="toast border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body text-light">

            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Add Food Modal -->
<div class="modal fade text-left" id="addOrderModal" tabindex="-1" aria-labelledby="addOrderModal" role="dialog">
    <div class="modal-dialog modal-dialog-top modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title text-light">Add Order</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="addOrderForm">
                    @csrf
                    <div class="form-group">
                        <label>Foods</label>
                        <select class="form-select" id="food_order">
                            <option selected>-Choose food--</option>
                            @foreach($foods as $f)
                            <option value="{{ $f->id }}" data-price="{{$f->price}}">{{ $f->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="food_order_feedback">

                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <div type="text" class="form-control" id="selected_food">
                            <small id="foodPlaceholder">No food selected</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Total</label>
                        <input type="text" readonly class="form-control" id="order_total">
                    </div>
                    <div class="form-group">
                        <label>Table Number</label>
                        <select class="form-select" id="table_number" name="table_number">
                            <option selected disabled>-Choose table number--</option>
                            @foreach($tables as $t)
                            <option value="{{ $t->id }}">{{ $t->number }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="food_order_feedback">

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea class="form-control" name="order_notes" id="order_notes" rows="2"></textarea>
                        <div class="invalid-feedback" id="order_notes_feedback">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-info ml-1" id="btnSubmitOrder">
                            Create
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endsection
    @section('custom-script')
    <script src="{{ asset('simple-datatables/simple-datatables.js') }}"></script>
    <script>
        (function() {
            const navfoods = document.querySelector('.orders');
            navfoods.classList.add('active');

            // Loading Spinner for load data 
            const loadingDiv = document.querySelector("#loadingDiv");
            loadingDiv.classList.toggle('d-none');
            // END
            // Initialize Datatable
            let Orders = {
                headings: ["Id", "Number", "Table", "Total", "Status", "Created", "Action", "Foods"],
                data: []
            };
            let OrderDataTables = null;

            function initOrderTable() {
                const orderTables = document.querySelector('#orderTables');
                OrderDataTables = new simpleDatatables.DataTable(orderTables, {
                    data: Orders,
                    columns: [{
                            select: 4,
                            render: function(data) {
                                return data == 'Sudah Dibayar' ? `<span class="badge rounded-pill bg-success">${data}</span>` : `<span class="badge rounded-pill bg-danger">${data}</span>`
                            }
                        },
                        {
                            select: 7,
                            sortable: false,
                            hidden: true
                        },
                        {
                            select: 1,
                            sortable: false
                        },
                        {
                            select: 6,
                            sortable: false,
                            render: function(data, cell, row) {
                                return `
                            <button type="button" class="btn btn-info btn-sm detail" data-bs-toggle="modal" data-bs-target="#detailFoodModal" data-index="${row.dataIndex}" onclick="showDetail(${row.dataIndex})" >Detail</button>
                            <button type="button" class="btn btn-warning btn-sm edit" data-bs-toggle="modal" data-bs-target="#editFoodModal" data-index="${row.dataIndex}" data-id="${row.childNodes[0].textContent}" onclick="showEdit(${row.dataIndex})" >Edit</button>
                            <button type="button" class="btn btn-danger btn-sm delete" data-bs-toggle="modal" data-bs-target="#deleteFoodModal" data-index="${row.dataIndex}" onclick="showDeleteConfirm(${row.dataIndex})" >Delete</button>
                            `;
                            }
                        },
                        {
                            select: 3,
                            render: function(data) {
                                return formatRupiah(data, 'Rp. ');
                            }
                        },
                        {
                            select: 0,
                            sortable: false,
                            hidden: true
                        }
                    ],
                    perPage: 10,
                    perPageSelect: [10, 20, 30, 50]
                });
                const thead = document.querySelector("#orderTables > thead");
                thead.classList.add("table-dark");
            }
            // END
            /* ------- Get Orders ------- */
            document.addEventListener('DOMContentLoaded', function() {
                fetch("{{ url('admin/orders/get') }}")
                    .then(response => {
                        return response.json();
                    })
                    .then(res => {
                        if (res.status === 'success') {
                            loadingDiv.classList.toggle('d-none');
                            for (let i = 0; i < res.data.length; i++) {
                                Orders.data[i] = [];
                                Orders.data[i].push(res.data[i]['id']);
                                Orders.data[i].push(res.data[i]['number']);
                                Orders.data[i].push(res.data[i]['table_number']);
                                Orders.data[i].push(res.data[i]['total']);
                                Orders.data[i].push(res.data[i]['status']);
                                Orders.data[i].push(res.data[i]['created_at']);
                                Orders.data[i].push(res.data[i]['created_at']);
                                Orders.data[i].push(res.data[i]['foods']);
                            }
                            initOrderTable();
                            return;
                        }
                        loadingDiv.classList.toggle('d-none');
                        initOrderTable();
                        generateMessage(res.status, res.message);
                        throw new Error(res.message);
                    })
                    .catch(console.error);
            });


            /* ------- End Get Orders ------- */
            const order_data = [];
            let total_price = 0;
            const order_total = document.querySelector('#order_total');
            const food_order = document.querySelector('#food_order');
            const selected_food = document.querySelector('#selected_food');
            const foodPlaceholder = document.querySelector('#foodPlaceholder');
            food_order.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];

                selected_food.classList.remove('is-invalid');
                foodPlaceholder.textContent = '';
                const elem = document.createElement('small');
                elem.innerHTML = ` ${selected.textContent} <span class="badge rounded-pill bg-danger food-selected-${selected.value}" data-value="${selected.value}" data-content="${selected.textContent}" data-price="${parseFloat(selected.getAttribute('data-price'))}" style="cursor: pointer;" > x </span>`;
                selected_food.appendChild(elem);

                order_data.push(selected.value);
                total_price += parseFloat(selected.getAttribute('data-price'));
                order_total.value = formatRupiah(total_price.toString(), 'Rp.');
                this.remove(this.selectedIndex);
                initDom(`food-selected-${selected.value}`);
            });

            function initDom(className) {
                const foodSelected = document.querySelector('.' + className);
                foodSelected.addEventListener('click', function() {
                    const small = foodSelected.parentElement;
                    selected_food.removeChild(small);
                    if (selected_food.childElementCount <= 1) {
                        foodPlaceholder.textContent = 'No food selected';
                        selected_food.classList.add('is-invalid');
                    }

                    const opt = document.createElement('option');
                    opt.value = this.getAttribute('data-value');
                    opt.textContent = this.getAttribute('data-content');
                    opt.setAttribute('data-price', this.getAttribute('data-price'));
                    food_order.appendChild(opt);
                    total_price -= this.getAttribute('data-price');
                    order_total.value = formatRupiah(total_price.toString(), 'Rp.');
                    return order_data.splice(order_data.findIndex(i => i == this.getAttribute('data-value')));
                });

            }

            /* ------- Save Order ------- */
            // Initialize Var and DOM
            const addOrderModal = new bootstrap.Modal('#addOrderModal');
            const btnSubmitOrder = document.querySelector('#btnSubmitOrder');
            const addOrderForm = document.querySelector("#addOrderForm");
            // END
            // Validate form

            // End
            // Submit form
            addOrderForm.addEventListener("submit", function(e) {
                e.preventDefault();

            });
            // END
            /* ------- End Save Orders ------- */

            /* ------- Update Orders ------- */
            // Initialize var and DOM

            /* ------- End Update Orders ------- */
            /* ------- Delete Orders ------- */
            // Init var dan DOM

            /* ------- End Delete Orders ------- */

        })()


        // Generate toast for message alert
        function generateMessage(status, message) {
            const toastAlert = document.getElementById('toastAlert');
            const toast = new bootstrap.Toast(toastAlert);
            const toastBody = document.querySelector(".toast-body");
            if (status === 'success' || status === 'created') {
                toastAlert.classList.remove("bg-danger");
                toastAlert.classList.add("bg-info");
                toastBody.textContent = message;
            } else {
                toastAlert.classList.remove("bg-info");
                toastAlert.classList.add("bg-danger");
                toastBody.textContent = message;
                console.log(toastBody.textContent)
            }
            toast.show();
        }
        // END


        function resetAction(data = []) {}

        function formatRupiah(angka, prefix) {
            let number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }
    </script>
    @endsection