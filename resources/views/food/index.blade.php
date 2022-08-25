@extends('templates.app')
@section('title') Food List @endsection
@section('datatable')
<link rel="stylesheet" href="{{ asset('simple-datatables/style.css') }}">
<link rel="stylesheet" href="{{ asset('simple-datatables/simple-datatables.css') }}">
@endsection
@section('content')
<div class="container mt-5">
    <div class="card">
        <h3 class="card-header bg-primary text-white text-center p-3">Food List</h3>
        <div class="card-body text-white">
            <div class="row">
                <div class="col-md-3">
                    <button type="button" class="btn btn-success" id="addFoodBtn">Add New Food</button>
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

                <table id="foodTables" class="table table-striped table-responsive">

                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('custom-script')
<script src="{{ asset('simple-datatables/simple-datatables.js') }}"></script>
<script>
    const loadingDiv = document.querySelector("#loadingDiv");
    let foodTables = null;
    loadingDiv.classList.toggle('d-none');
    fetch("{{ asset('data/food.json') }}")
        .then(response => response.json())
        .then(data => {
            if (!data.length) {
                return
            }
            let obj = {
                headings: ["Name", "Price", "Status", "Action"],
                data: []
            };
            for (let i = 0; i < data.length; i++) {
                // data[i]['action'] = null;
                obj.data[i] = [];
                obj.data[i].push(data[i]['name'])
                obj.data[i].push(data[i]['price'])
                obj.data[i].push(data[i]['stock'])
                obj.data[i].push(data[i]['action'])
            }
            loadingDiv.classList.toggle('d-none');
            foodTables = new simpleDatatables.DataTable("#foodTables", {
                data: obj,
                columns: [{
                        select: 0,
                        render: function(data) {
                            return data
                        }
                    },
                    {
                        select: 1,
                        render: function(data) {
                            return data
                        }
                    },
                    {
                        select: 2,
                        render: function(data) {
                            return data
                        }
                    },
                    {
                        select: 3,
                        sortable: false,
                        render: function(data) {
                            return `
                            <button type="button" class="btn btn-info btn-sm delete">Detail</button>
                            <button type="button" class="btn btn-warning btn-sm delete">Edit</button>
                            <button type="button" class="btn btn-danger btn-sm delete">Delete</button>
                            `
                        }
                    },
                ]
            });

        });
    const addFoodBtn = document.querySelector("#addFoodBtn");
    addFoodBtn.addEventListener("click", () => {
        let newData = ["Indomie Goreng", "10000.00", "Tersedia", null];
        foodTables.rows().add(newData);
    });
</script>
@endsection