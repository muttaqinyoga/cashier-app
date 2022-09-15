@extends('templates.app')
@section('title') Category List @endsection
@section('datatable')
<link rel="stylesheet" href="{{ asset('simple-datatables/style.css') }}">
<link rel="stylesheet" href="{{ asset('simple-datatables/simple-datatables.css') }}">
@endsection
@section('content')
<div class="container mt-5">
    <div class="card">
        <h4 class="card-header bg-primary text-white text-center p-2">Food List Category</h4>
        <div class="card-body text-white">
            <div class="row">
                <div class="col-md-3">
                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addCategory">
                        Add New Category
                    </button>
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
                <table id="foodTables" class="table table-striped table-responsive table-bordered">

                </table>

            </div>
        </div>
    </div>
</div>
<!-- Form Categories -->
<!-- Add -->
<div class="modal fade text-left" id="addCategory" tabindex="-1" aria-labelledby="addCategory" role="dialog">
    <div class="modal-dialog modal-dialog-top modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title text-light">Add Food Category</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                </button>
            </div>
            <form method="post" id="addCategoryForm">
                @csrf
                <div class="modal-body">
                    <label>Name</label>
                    <div class="form-group">
                        <input type="text" placeholder="Enter category food name..." class="form-control" name="category_name" id="category_name">
                        <div class="invalid-feedback" id="category_name_feedback">

                        </div>
                    </div>
                    <label>Image</label>
                    <div class="form-group">
                        <input class="form-control" type="file" name="category_image" id="category_image">
                        <div class="invalid-feedback" id="category_image_feedback">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Cancel</span>
                    </button>
                    <button type="submit" class="btn btn-success ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Save</span>
                    </button>
                </div>
            </form>
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
@endsection
@section('custom-script')
<script src="{{ asset('simple-datatables/simple-datatables.js') }}"></script>
<script>
    const loadingDiv = document.querySelector("#loadingDiv");
    let Foods = {
        headings: ["Id", "Slug", "Name", "Image", "Action"],
        data: []
    };
    let FoodDataTables = null;
    let category_name_value = null;
    loadingDiv.classList.toggle('d-none');
    const toastAlert = document.getElementById('toastAlert');
    const toast = new bootstrap.Toast(toastAlert);
    const toastBody = document.querySelector(".toast-body");

    function generateMessage(status, message) {
        if (status === 'success' || status === 'created') {
            toastAlert.classList.toggle("bg-danger");
            toastAlert.classList.toggle("bg-success");
            toastBody.textContent = message;
        } else {
            toastAlert.classList.toggle("bg-success");
            toastAlert.classList.toggle("bg-danger");
            toastBody.textContent = message;
        }
        toast.show();
    }
    // add Category
    const addCategoryForm = document.querySelector("#addCategoryForm");
    const category_name = document.getElementsByName('category_name')[0];
    const category_image = document.getElementsByName('category_image')[0];
    const category_image_feedback = document.querySelector("#category_image_feedback");
    const category_name_feedback = document.querySelector("#category_name_feedback");
    category_image.addEventListener('change', () => {
        category_image.classList.remove('is-invalid');
        validateImage(category_image.files[0]);
    });
    category_name.addEventListener("input", () => {
        category_name_value = category_name.value.trim();
        category_name.classList.remove('is-invalid');
        if (category_name_value == '') {
            category_name.value = '';
            category_name.classList.add('is-invalid');
            category_name_feedback.textContent = "Name can't be empty";
        }
    });
    addCategoryForm.addEventListener("submit", function(e) {
        e.preventDefault();
        if (category_name_value == '' || category_name_value == null) {
            category_name.classList.add('is-invalid');
            category_name_feedback.textContent = "Name can't be empty";

        } else {
            const payloadCategory = new FormData(addCategoryForm);
            fetch("{{ url('admin/categories/save') }}", {
                    method: "POST",
                    headers: {
                        accept: 'application/json'
                    },
                    credentials: "same-origin",
                    body: payloadCategory
                })
                .then(response => response.json())
                .then(res => {
                    if (res.status === 'failed') {
                        if (res.errors) {
                            Object.keys(res.errors).forEach((key, index) => {
                                const elemInput = document.getElementById(key);
                                const elemFeedBack = document.getElementById(key + "_feedback");

                                if (elemInput && elemFeedBack) {
                                    elemInput.classList.add('is-invalid');
                                    elemFeedBack.textContent = res.errors[key][0];
                                }
                            });
                            return;
                        }
                        generateMessage(res.status, res.message);
                    } else if (res.status === 'created') {
                        generateMessage(res.status, res.message);
                    }
                })
                .catch(err => console.error)
        }
    });

    function validateImage(image) {
        if (!['image/jpg', 'image/jpeg', 'image/png'].includes(image.type)) {
            category_image.classList.add('is-invalid');
            category_image_feedback.textContent = "Only.jpg, jpeg and.png image are allowed";
            category_image.value = '';
            return;
        }
        if (image.size > 100000) {
            category_image.classList.add('is-invalid');
            category_image_feedback.textContent = "Image size must be less than 100KB";
            category_image.value = '';
            return;
        }
    }


    fetch("{{ url('admin/categories/get') }}")
        .then(response => {
            return response.json();
        })
        .then(res => {
            if (res.status === 'success') {
                loadingDiv.classList.toggle('d-none');
                for (let i = 0; i < res.data.length; i++) {
                    // data[i]['action'] = null;
                    Foods.data[i] = [];
                    Foods.data[i].push(res.data[i]['id'])
                    Foods.data[i].push(res.data[i]['slug'])
                    Foods.data[i].push(res.data[i]['name'])
                    Foods.data[i].push(res.data[i]['image'])
                    Foods.data[i].push(res.data[i]['action'])
                }
                initFoodTable();
                return;
            }
            loadingDiv.classList.toggle('d-none');
            initFoodTable();
            generateMessage(res.status, res.message);
            throw new Error(res.message);
        })
        .catch(console.error);
    // const addFoodBtn = document.querySelector("#addFoodBtn");
    // addFoodBtn.addEventListener("click", function() {
    //     let newData = ["Indomie Goreng", "10000.00", "Tersedia", null];
    //     const currLength = Foods.data.length;

    //     Foods.data.push([]);
    //     Foods.data[currLength].push("Indomie Goreng");
    //     Foods.data[currLength].push("10000.00");
    //     Foods.data[currLength].push("Tersedia");
    //     Foods.data[currLength].push('');
    //     foodDataTables.destroy();
    //     initFoodTable();
    // });

    function initFoodTable() {
        const foodTables = document.querySelector('#foodTables');
        foodDataTables = new simpleDatatables.DataTable(foodTables, {
            data: Foods,
            columns: [{
                    select: 3,
                    sortable: false,
                    render: function(data) {
                        return `<img src="{{ asset('images/categories') }}/${data}" class="img-fluid mx-auto d-block" alt="food-categories" width="100">`
                    }
                },
                {
                    select: 4,
                    sortable: false,
                    render: function(data) {
                        return `
                            <button type="button" class="btn btn-warning btn-sm edit">Edit</button>
                            <button type="button" class="btn btn-danger btn-sm delete">Delete</button>
                            `;
                    }
                },
                {
                    select: 0,
                    sortable: false,
                    hidden: true
                },
                {
                    select: 1,
                    sortable: false,
                    hidden: true
                }
            ]
        });
        let deleteBtn = document.querySelectorAll('.delete');
        deleteBtn.forEach((el, i) => {
            el.addEventListener("click", function(e) {
                Foods.data.splice(i, 1);
                foodDataTables.destroy();
                initFoodTable();
            });
        });
        let thead = document.querySelector("#foodTables > thead");
        thead.classList.add("table-dark");
    }
</script>
@endsection