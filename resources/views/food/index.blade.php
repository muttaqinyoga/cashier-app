@extends('templates.app')
@section('title') Food List @endsection
@section('datatable')
<link rel="stylesheet" href="{{ asset('simple-datatables/style.css') }}">
<link rel="stylesheet" href="{{ asset('simple-datatables/simple-datatables.css') }}">
@endsection
@section('content')
<div class="container mt-5">
    <div class="card">
        <h3 class="card-header bg-success text-white text-center p-3">Food List</h3>
        <div class="card-body text-white">
            <div class="row">
                <div class="col-md-3">
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addFoodModal">Add New Food</button>
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
<div class="toast-container position-fixed p-3 top-0 start-50 translate-middle-x">
    <div id="toastAlert" class="toast border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body text-light">

            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
<!-- Delete Food Modal -->
<div class="modal fade text-left" id="deleteFoodModal" tabindex="-1" aria-labelledby="deleteFoodModal" role="dialog">
    <div class="modal-dialog modal-dialog-top modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title text-light">Delete Food</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                </button>
            </div>
            <form method="post" id="deleteFoodForm">
                @csrf
                @method('DELETE')
                <input type="hidden" name="food_delete_id" id="food_delete_id">
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger ml-1" id="btnDeleteFood">
                        Yes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Detail Food Modal -->
<div class="modal fade text-left" id="detailFoodModal" tabindex="-1" aria-labelledby="detailFoodModal" role="dialog">
    <div class="modal-dialog modal-dialog-top modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title text-light">Food Detail</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Name</label>
                    <div type="text" class="form-control" id="detail_food_name">

                    </div>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <div type="text" class="form-control" id="detail_food_categories">

                    </div>
                </div>
                <div class="form-group mt-2">
                    <label>Sell Price</label>
                    <div type="text" class="form-control" id="detail_food_price">

                    </div>
                </div>
                <div class="form-group">
                    <label>Status Stock</label>
                    <p id="detail_food_status">

                    </p>
                </div>
                <div class="form-group">
                    <label>Image</label>
                    <img src="" class="img-fluid d-block" alt="food_images" width="100" id="detail_food_image">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" readonly disabled name="detail_food_description" id="detail_food_description" rows="3"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Edit Food Modal -->
<div class="modal fade text-left" id="editFoodModal" tabindex="-1" aria-labelledby="editFoodModal" role="dialog">
    <div class="modal-dialog modal-dialog-top modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h4 class="modal-title text-light">Edit Food</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="editFoodForm">
                    @csrf
                    <input type="hidden" name="edit_food_id" id="edit_food_id">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" name="edit_food_name" id="edit_food_name">
                        <div class="invalid-feedback" id="edit_food_name_feedback">

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select class="form-select" id="edit_food_categories">
                            <option selected>-Choose category--</option>
                        </select>
                        <div class="invalid-feedback" id="edit_food_categories_feedback">

                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <div type="text" class="form-control" id="edit_selected_categories">
                            <small id="editCtgPlaceholder">No category selected</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Sell Price</label>
                        <input type="number" min="0" placeholder="ex. 10000" class="form-control" name="edit_food_price" id="edit_food_price">
                        <div class="invalid-feedback" id="edit_food_price_feedback">

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="edit_food_description" id="edit_food_description" rows="3">

                        </textarea>
                        <div class="invalid-feedback" id="edit_food_description_feedback">

                        </div>

                    </div>
                    <div class="form-group" id="edit_status_field">


                    </div>
                    <small class="text-danger" id="edit_food_status_feedback"></small>
                    <div class="form-group">
                        <label>Image</label>
                        <img src="" class="img-fluid d-block" alt="food_images" width="100" id="edit_current_image">
                        <div class="form-group mt-2">
                            <input class="form-control" type="file" name="edit_food_image" id="edit_food_image">
                            <div class="invalid-feedback" id="edit_food_image_feedback">

                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-warning ml-1" id="btnEditSubmitFood">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Add Food Modal -->
<div class="modal fade text-left" id="addFoodModal" tabindex="-1" aria-labelledby="addFoodModal" role="dialog">
    <div class="modal-dialog modal-dialog-top modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title text-light">Add Food</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="addFoodForm">
                    @csrf
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" placeholder="Enter food name..." class="form-control" name="food_name" id="food_name">
                        <div class="invalid-feedback" id="food_name_feedback">

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select class="form-select" id="food_categories">
                            <option selected>-Choose category--</option>
                            @foreach($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="food_categories_feedback">

                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <div type="text" class="form-control" id="selected_categories">
                            <small id="ctgPlaceholder">No category selected</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Sell Price</label>
                        <input type="number" min="0" placeholder="ex. 10000" class="form-control" name="food_price" id="food_price">
                        <div class="invalid-feedback" id="food_price_feedback">

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="food_description" id="food_description" rows="3"></textarea>
                        <div class="invalid-feedback" id="food_description_feedback">

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Image</label>
                        <div class="form-group">
                            <input class="form-control" type="file" name="food_image" id="food_image">
                            <div class="invalid-feedback" id="food_image_feedback">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary ml-1" id="btnSubmitFood">
                            Save
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
        const navfoods = document.querySelector('.foods');
        navfoods.classList.add('active');
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
        // Loading Spinner for load data 
        const loadingDiv = document.querySelector("#loadingDiv");
        loadingDiv.classList.toggle('d-none');
        // END
        // Initialize Datatable
        let Foods = {
            headings: ["Id", "Name", "Categories", "Price", "Status", "Action", "image", "desc"],
            data: []
        };
        let FoodDataTables = null;

        function initFoodTable() {
            const foodTables = document.querySelector('#foodTables');
            FoodDataTables = new simpleDatatables.DataTable(foodTables, {
                data: Foods,
                columns: [{
                        select: 4,
                        render: function(data) {
                            return data == 'Tersedia' ? `<span class="badge rounded-pill bg-primary">${data}</span>` : `<span class="badge rounded-pill bg-danger">${data}</span>`
                        }
                    },
                    {
                        select: 7,
                        sortable: false,
                        hidden: true
                    },
                    {
                        select: 6,
                        sortable: false,
                        hidden: true
                    },
                    {
                        select: 2,
                        sortable: false
                    },
                    {
                        select: 5,
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
                perPage: 4,
                perPageSelect: [4, 10, 20, 50]
            });
            const thead = document.querySelector("#foodTables > thead");
            thead.classList.add("table-dark");
        }
        // END
        // Validate Image
        function validateImage(image, act) {
            if (act == 'add') {
                if (!['image/jpg', 'image/jpeg', 'image/png'].includes(image.type)) {
                    food_image.classList.add('is-invalid');
                    food_image_feedback.textContent = "Only.jpg, jpeg and.png image are allowed";
                    food_image.value = '';
                    return;
                }
                if (image.size > 20000) {
                    food_image.classList.add('is-invalid');
                    food_image_feedback.textContent = "Image size must be less than 100KB";
                    food_image.value = '';
                    return;
                }
            } else if (act == 'edit') {
                if (!['image/jpg', 'image/jpeg', 'image/png'].includes(image.type)) {
                    edit_food_image.classList.add('is-invalid');
                    edit_food_image_feedback.textContent = "Only.jpg, jpeg and.png image are allowed";
                    edit_food_image.value = '';
                    return;
                }
                if (image.size > 100000) {
                    edit_food_image.classList.add('is-invalid');
                    edit_food_image_feedback.textContent = "Image size must be less than 100KB";
                    edit_food_image.value = '';
                    return;
                }
            }

        }
        // END
        const food_categories = document.querySelector('#food_categories');
        const categories = [];
        const selected_categories = document.querySelector('#selected_categories');

        const ctgPlaceholder = document.querySelector('#ctgPlaceholder');
        food_categories.addEventListener('change', function(e) {
            const selected = this.options[this.selectedIndex];
            if (selected_categories.childElementCount <= 1) {
                ctgPlaceholder.textContent = '';
            }
            selected_categories.classList.remove('is-invalid');
            const elem = document.createElement('small');
            elem.innerHTML = ` ${selected.textContent} <span class="badge rounded-pill bg-danger category-selected-${selected.value}" onclick="removeSelected('${selected.value}', '${selected.textContent}')" style="cursor: pointer;" > x </span>`;
            selected_categories.appendChild(elem);
            categories.push(selected.value);
            this.remove(this.selectedIndex);
        });

        function removeSelected(id, name, act = 'add') {
            if (act == 'edit') {
                console.log(name);
                const el = document.querySelector('.category-selected-' + id);
                const small = el.parentElement;
                edit_selected_categories.removeChild(small);
                const opt = document.createElement('option');
                opt.value = id;
                opt.textContent = name;
                edit_food_categories.appendChild(opt);
                if (edit_selected_categories.childElementCount <= 1) {
                    editCtgPlaceholder.textContent = 'No category selected';
                    edit_selected_categories.classList.add('is-invalid');
                }
                return categories.splice(categories.findIndex(i => i == id), 1);
            } else if (act == 'add') {
                console.log(name);
                const el = document.querySelector('.category-selected-' + id);
                const small = el.parentElement;
                selected_categories.removeChild(small);
                const opt = document.createElement('option');
                opt.value = id;
                opt.textContent = name;
                food_categories.appendChild(opt);
                if (selected_categories.childElementCount <= 1) {
                    ctgPlaceholder.textContent = 'No category selected';
                    selected_categories.classList.add('is-invalid');
                }
                return categories.splice(categories.findIndex(i => i == id), 1);
            }
            window.location.href = window.location.href;
        }
        /* ------- Get Categories ------- */
        document.addEventListener('DOMContentLoaded', function() {
            fetch("{{ url('admin/foods/get') }}")
                .then(response => {
                    return response.json();
                })
                .then(res => {
                    if (res.status === 'success') {
                        loadingDiv.classList.toggle('d-none');
                        for (let i = 0; i < res.data.length; i++) {
                            const categories = [];

                            for (let j = 0; j < res.data[i]['categories'].length; j++) {
                                categories.push(res.data[i]['categories'][j]['name']);
                            }
                            Foods.data[i] = [];
                            Foods.data[i].push(res.data[i]['id']);
                            Foods.data[i].push(res.data[i]['name']);
                            Foods.data[i].push(categories.join(", "));
                            Foods.data[i].push(res.data[i]['price']);
                            Foods.data[i].push(res.data[i]['status_stock']);
                            Foods.data[i].push(res.data[i]['created_at']);
                            Foods.data[i].push(res.data[i]['image']);
                            Foods.data[i].push(res.data[i]['description']);
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
        });

        /* ------- End Get Categories ------- */
        /* ------- Get Food Detail ------- */
        function showDetail(dataIndex) {
            let detailBtn = document.querySelectorAll('.detail');
            let valid = false;
            detailBtn.forEach((el, i) => {
                if (el.getAttribute("data-index") == dataIndex) {
                    document.querySelector('#detail_food_name').textContent = FoodDataTables.data[dataIndex].childNodes[1].data;
                    document.querySelector('#detail_food_categories').textContent = FoodDataTables.data[dataIndex].childNodes[2].data;
                    document.querySelector('#detail_food_price').textContent = formatRupiah(FoodDataTables.data[dataIndex].childNodes[3].data, 'Rp');
                    document.querySelector('#detail_food_status').innerHTML = `${ FoodDataTables.data[dataIndex].childNodes[4].data == 'Tersedia' ? '<span class="badge rounded-pill bg-primary">'+ FoodDataTables.data[dataIndex].childNodes[4].data +'</span>' : '<span class="badge rounded-pill bg-danger">'+ FoodDataTables.data[dataIndex].childNodes[4].data +'</span>' }`;
                    document.querySelector('#detail_food_image').setAttribute('src', `{{ url('images/foods') }}/${FoodDataTables.data[dataIndex].childNodes[6].data}`);
                    document.querySelector('#detail_food_description').textContent = FoodDataTables.data[dataIndex].childNodes[7].data;
                    valid = true;
                }

            });
            if (!valid) {
                window.location.href = window.location.href;
            }
        }
        /* ------- End Get Food ------- */
        /* ------- Save Categories ------- */
        // Initialize Var and DOM
        let food_name_value = null;
        let food_price_value = null;
        let food_description_value = '';
        const addFoodModal = new bootstrap.Modal('#addFoodModal');
        const btnSubmitFood = document.querySelector('#btnSubmitFood');
        const addFoodForm = document.querySelector("#addFoodForm");
        const food_name = document.getElementsByName('food_name')[0];
        const food_image = document.getElementsByName('food_image')[0];
        const food_price = document.getElementsByName('food_price')[0];
        const food_description = document.getElementsByName('food_description')[0];
        const food_name_feedback = document.querySelector("#food_name_feedback");
        const food_image_feedback = document.querySelector("#food_image_feedback");
        const food_price_feedback = document.querySelector("#food_price_feedback");
        const food_description_feedback = document.querySelector("#food_description_feedback");
        // END
        // Validate form
        food_name.addEventListener("input", () => {
            food_name_value = food_name.value.trim();
            food_name.classList.remove('is-invalid');
            if (food_name_value == '') {
                food_name.value = '';
                food_name.classList.add('is-invalid');
                food_name_feedback.textContent = "Name can't be empty";
            }
        });
        food_price.addEventListener("input", () => {
            food_price_value = food_price.value.trim();
            food_price.classList.remove('is-invalid');
            if (food_price_value == '' || food_price_value == null) {
                food_price.value = '';
                food_price.classList.add('is-invalid');
                food_price_feedback.textContent = "Sell price can't be empty";
            } else if (isNaN(parseFloat(food_price_value)) || parseFloat(food_price_value) < 0) {
                food_price.value = '';
                food_price.classList.add('is-invalid');
                food_price_feedback.textContent = "Please provide valid number";
            }
        });
        food_description.addEventListener("input", () => {
            food_description_value = food_description.value.trim();
            food_description.classList.remove('is-invalid');
            if (food_description.value.length > 100) {
                food_description.classList.add('is-invalid');
                food_description_feedback.textContent = "Characters must be less than 100 characters";
            }
        });
        food_image.addEventListener('change', () => {
            food_image.classList.remove('is-invalid');
            validateImage(food_image.files[0], 'add');
        });
        // End
        // Submit form
        addFoodForm.addEventListener("submit", function(e) {
            e.preventDefault();
            if (food_name_value == '' || food_name_value == null || food_price_value == '' || food_price_value == null) {
                food_name.classList.add('is-invalid');
                food_name_feedback.textContent = "Name can't be empty";
                food_price.classList.add('is-invalid');
                food_price_feedback.textContent = "Sell price can't be empty";
                selected_categories.classList.add('is-invalid');
            } else if (isNaN(parseFloat(food_price_value)) || parseFloat(food_price_value) < 0) {
                food_price.classList.add('is-invalid');
                food_price_feedback.textContent = "Please provide valid number";
            } else {
                const payloadFood = new FormData(addFoodForm);
                payloadFood.append('food_categories', categories);
                btnSubmitFood.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...`;
                btnSubmitFood.setAttribute('disabled', 'disabled');

                fetch("{{ url('admin/foods/save') }}", {
                        method: "POST",
                        headers: {
                            accept: 'application/json'
                        },
                        credentials: "same-origin",
                        body: payloadFood
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
                                        btnSubmitFood.innerHTML = 'Save';
                                        btnSubmitFood.removeAttribute('disabled');
                                    }
                                });
                                return;
                            }
                            addFoodModal.hide();
                            resetAction();
                            generateMessage(res.status, res.message);
                        } else if (res.status === 'created') {
                            addFoodModal.hide();
                            const resCategories = res.data.categories;
                            const currLength = Foods.data.length;
                            const resTblCategories = [];
                            resCategories.forEach(i => {
                                resTblCategories.push(i.name);
                            });

                            Foods.data.push([]);
                            Foods.data[currLength].push(res.data['id']);
                            Foods.data[currLength].push(res.data['name']);
                            Foods.data[currLength].push(resTblCategories.join(", "));
                            Foods.data[currLength].push(res.data['price']);
                            Foods.data[currLength].push(res.data['status_stock']);
                            Foods.data[currLength].push(res.data['created_at']);
                            Foods.data[currLength].push(res.data['image']);
                            Foods.data[currLength].push(res.data['description']);
                            Foods.data.sort((a, b) => {
                                return new Date(b[5]).getTime() - new Date(a[5]).getTime();
                            });
                            FoodDataTables.destroy();
                            initFoodTable();
                            generateMessage(res.status, res.message);
                            resetAction(resCategories);
                        }
                    })
                    .catch(err => console.error);
            }
        });
        // END
        /* ------- End Save Categories ------- */

        /* ------- Update Categories ------- */
        // Initialize var and DOM
        let edit_food_name_value = null;
        let edit_food_price_value = null;
        let edit_food_description_value = '';
        let updated_index_food = 0;
        const editFoodModal = new bootstrap.Modal('#editFoodModal');
        const btnEditSubmitFood = document.querySelector('#btnEditSubmitFood');
        const editFoodForm = document.querySelector('#editFoodForm');
        const edit_food_id = document.getElementsByName('edit_food_id')[0];
        const edit_food_name = document.getElementsByName('edit_food_name')[0];
        const edit_food_image = document.getElementsByName('edit_food_image')[0];
        const edit_current_image = document.querySelector('#edit_current_image');
        const edit_food_categories = document.querySelector('#edit_food_categories');
        const edit_selected_categories = document.querySelector('#edit_selected_categories');
        const editCtgPlaceholder = document.querySelector('#editCtgPlaceholder');
        const edit_food_price = document.querySelector('#edit_food_price');
        const edit_food_description = document.querySelector('#edit_food_description');
        const edit_food_image_feedback = document.querySelector("#edit_food_image_feedback");
        const edit_food_name_feedback = document.querySelector("#edit_food_name_feedback");
        const edit_food_categories_feedback = document.querySelector("#edit_food_categories_feedback");
        const edit_food_description_feedback = document.querySelector('#edit_food_description_feedback');
        const edit_status_field = document.querySelector('#edit_status_field');
        const edit_food_status_feedback = document.querySelector('#edit_food_status_feedback');

        // // End
        function showEdit(dataIndex) {
            resetAction();
            let editBtn = document.querySelectorAll('.edit');
            let valid = false;
            const categoryList = JSON.parse(`@php echo json_encode($categories) @endphp`);
            editBtn.forEach((el, i) => {
                if (el.getAttribute("data-index") == dataIndex) {
                    edit_food_id.value = el.getAttribute("data-id");
                    updated_index_food = dataIndex;
                    edit_food_name.value = FoodDataTables.data[dataIndex].childNodes[1].data;
                    edit_food_name_value = FoodDataTables.data[dataIndex].childNodes[1].data;
                    edit_current_image.setAttribute("src", `{{ url('images/foods') }}/${FoodDataTables.data[dataIndex].childNodes[6].data}`);
                    edit_food_price.value = FoodDataTables.data[dataIndex].childNodes[3].data;
                    edit_food_price_value = FoodDataTables.data[dataIndex].childNodes[3].data;
                    edit_food_description.value = FoodDataTables.data[dataIndex].childNodes[7].data;
                    edit_food_description_value = FoodDataTables.data[dataIndex].childNodes[7].data;
                    edit_food_description.textContent = FoodDataTables.data[dataIndex].childNodes[7].data;
                    const currCategoriesArr = FoodDataTables.data[dataIndex].childNodes[2].data.split(", ");
                    editCtgPlaceholder.textContent = '';
                    currCategoriesArr.forEach(i => {
                        categoryList.forEach((e, idx) => {
                            if (i == e.name) {
                                const elem = document.createElement('small');
                                elem.innerHTML = ` ${e.name} <span class="badge rounded-pill bg-danger category-selected-${e.id}" onclick="removeSelected('${e.id}', '${e.name}', 'edit')" style="cursor: pointer;" > x </span>`;
                                edit_selected_categories.appendChild(elem);
                                categories.push(e.id);
                                categoryList.splice(idx, 1);
                            }

                        });
                    });

                    categoryList.forEach(e => {
                        const opt = document.createElement("option");
                        opt.value = e.id;
                        opt.textContent = e.name;
                        edit_food_categories.appendChild(opt);
                    });
                    edit_status_field.innerHTML = `
                        <label>Stock Status</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="edit_food_status" id="edit_food_status1" value="Tersedia" ${ FoodDataTables.data[dataIndex].childNodes[4].data == 'Tersedia' ? 'checked' : ''}>
                            <label class="form-check-label" for="edit_food_status1" >Tersedia</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="edit_food_status" id="edit_food_status2" value="Tidak Tersedia" ${ FoodDataTables.data[dataIndex].childNodes[4].data == 'Tidak Tersedia' ? 'checked' : ''}>
                            <label class="form-check-label" for="edit_food_status2">Tidak Tersedia</label>
                        </div>`;
                    valid = true;
                }
            });
            if (!valid) {
                window.location.href = window.location.href;
            }
        }
        edit_food_categories.addEventListener('change', function(e) {
            const selected = this.options[this.selectedIndex];
            if (edit_selected_categories.childElementCount <= 1) {
                editCtgPlaceholder.textContent = '';
            }
            edit_selected_categories.classList.remove('is-invalid');
            const elem = document.createElement('small');
            elem.innerHTML = ` ${selected.textContent} <span class="badge rounded-pill bg-danger category-selected-${selected.value}" onclick="removeSelected('${selected.value}', '${selected.textContent}', 'edit')" style="cursor: pointer;" > x </span>`;
            edit_selected_categories.appendChild(elem);
            categories.push(selected.value);
            this.remove(this.selectedIndex);
        });
        // Validate form
        edit_food_name.addEventListener("input", () => {
            edit_food_name_value = edit_food_name.value.trim();
            edit_food_name.classList.remove('is-invalid');
            if (edit_food_name_value == '') {
                edit_food_name.value = '';
                edit_food_name.classList.add('is-invalid');
                edit_food_name_feedback.textContent = "Name can't be empty";
            }
        });
        edit_food_price.addEventListener("input", () => {
            edit_food_price_value = edit_food_price.value.trim();
            edit_food_price.classList.remove('is-invalid');
            if (edit_food_price_value == '' || edit_food_price_value == null) {
                edit_food_price.value = '';
                edit_food_price.classList.add('is-invalid');
                edit_food_price_feedback.textContent = "Sell price can't be empty";
            } else if (isNaN(parseFloat(edit_food_price_value)) || parseFloat(edit_food_price_value) < 0) {
                edit_food_price.value = '';
                edit_food_price.classList.add('is-invalid');
                edit_food_price_feedback.textContent = "Please provide valid number";
            }
        });
        edit_food_description.addEventListener("input", () => {
            edit_food_description_value = edit_food_description.value.trim();
            edit_food_description.classList.remove('is-invalid');
            if (edit_food_description.value.length > 100) {
                edit_food_description.classList.add('is-invalid');
                edit_food_description_feedback.textContent = "Characters must be less than 100 characters";
            }
        });
        edit_food_image.addEventListener('change', () => {
            edit_food_image.classList.remove('is-invalid');
            validateImage(edit_food_image.files[0], 'edit');
        });
        // End
        // Submit form
        editFoodForm.addEventListener("submit", function(e) {
            e.preventDefault();
            if (edit_food_name_value == '' || edit_food_name_value == null || edit_food_price_value == '' || edit_food_price_value == null) {
                edit_food_name.classList.add('is-invalid');
                edit_food_name_feedback.textContent = "Name can't be empty";
                edit_food_price.classList.add('is-invalid');
                edit_food_price_feedback.textContent = "Sell price can't be empty";
                selected_categories.classList.add('is-invalid');
            } else if (isNaN(parseFloat(edit_food_price_value)) || parseFloat(edit_food_price_value) < 0) {
                edit_food_price.classList.add('is-invalid');
                edit_food_price_feedback.textContent = "Please provide valid number";
            } else {
                const payloadFood = new FormData(editFoodForm);
                payloadFood.append('edit_food_categories', categories);
                btnEditSubmitFood.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...`;
                btnEditSubmitFood.setAttribute('disabled', 'disabled');

                fetch("{{ url('admin/foods/update') }}", {
                        method: "POST",
                        headers: {
                            accept: 'application/json'
                        },
                        credentials: "same-origin",
                        body: payloadFood
                    })
                    .then(response => response.json())
                    .then(res => {
                        if (res.status === 'failed') {
                            if (res.errors) {
                                Object.keys(res.errors).forEach((key, index) => {
                                    if (key == 'edit_food_status') {
                                        const elemFeedBack = document.getElementById(key + "_feedback");
                                        elemFeedBack.textContent = '*' + res.errors[key][0];
                                    }
                                    const elemInput = document.getElementById(key);
                                    const elemFeedBack = document.getElementById(key + "_feedback");
                                    if (elemInput && elemFeedBack) {
                                        elemInput.classList.add('is-invalid');
                                        elemFeedBack.textContent = res.errors[key][0];

                                    }
                                    btnEditSubmitFood.innerHTML = 'Update';
                                    btnEditSubmitFood.removeAttribute('disabled');
                                });
                                return;
                            }
                            editFoodModal.hide();
                            resetAction();
                            generateMessage(res.status, res.message);
                        } else if (res.status === 'success') {
                            editFoodModal.hide();
                            const resCategories = res.data.categories;
                            const resTblCategories = [];
                            resCategories.forEach(i => {
                                resTblCategories.push(i.name);
                            });
                            Foods.data[updated_index_food][0] = res.data.id;
                            Foods.data[updated_index_food][1] = res.data.name;
                            Foods.data[updated_index_food][2] = resTblCategories.join(", ");
                            Foods.data[updated_index_food][3] = res.data.price;
                            Foods.data[updated_index_food][4] = res.data.status_stock;
                            Foods.data[updated_index_food][5] = res.data.created_at;
                            Foods.data[updated_index_food][6] = res.data.image;
                            Foods.data[updated_index_food][7] = res.data.description;
                            Foods.data.sort((a, b) => {
                                return new Date(b[5]).getTime() - new Date(a[5]).getTime();
                            });
                            FoodDataTables.destroy();
                            initFoodTable();
                            generateMessage(res.status, res.message);
                            resetAction();
                        }
                    })
                    .catch(err => console.error);
            }
        });
        /* ------- End Update Categories ------- */
        /* ------- Delete Categories ------- */
        // Init var dan DOM
        let deleted_index_food = null;
        const textDeleteFood = document.querySelector("#deleteFoodForm > .modal-body");
        const deleteFoodModal = new bootstrap.Modal('#deleteFoodModal');
        const btnDeleteFood = document.querySelector('#btnDeleteFood');
        const deleteFoodForm = document.querySelector('#deleteFoodForm');
        const food_delete_id = document.getElementsByName('food_delete_id')[0];
        // END
        function showDeleteConfirm(dataIndex) {
            let deleteBtn = document.querySelectorAll('.delete');
            let valid = false;
            deleteBtn.forEach((el, i) => {
                if (el.getAttribute("data-index") == dataIndex) {
                    const foodName = FoodDataTables.activeRows[dataIndex].firstElementChild.textContent;
                    textDeleteFood.innerHTML = `<p> Do you want to delete <strong>${foodName}</strong> from Food List ? </p>`;
                    const food_id = FoodDataTables.data[dataIndex].firstElementChild.textContent;
                    food_delete_id.value = food_id;
                    valid = true;
                    deleted_index_food = dataIndex;
                }
            });
            if (!valid) {
                window.location.href = window.location.href;
            }
        }
        // Submit form
        deleteFoodForm.addEventListener("submit", function(e) {
            e.preventDefault();
            if (food_delete_id.value == '' || food_delete_id.value == null) {
                window.location.href = window.location.href;
            } else {
                if (deleted_index_food == 0) {
                    fetchDelete();
                } else if (deleted_index_food == null || deleted_index_food == '') {
                    window.location.href = window.location.href;
                } else {
                    fetchDelete();
                }

            }
        });

        function fetchDelete() {
            const payloadDeleteFood = {
                _token: document.getElementsByName("_token")[0].getAttribute("value"),
                _method: document.getElementsByName("_method")[0].getAttribute("value"),
                food_delete_id: document.getElementsByName("food_delete_id")[0].getAttribute("value")
            }
            btnDeleteFood.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...`;
            btnDeleteFood.setAttribute('disabled', 'disabled');

            fetch("{{ url('admin/food/delete') }}", {
                    method: "DELETE",
                    headers: {
                        accept: 'application/json',
                        'Content-type': 'application/json; charset=UTF-8',
                        'X-CSRF-TOKEN': document.getElementsByName("csrf-token")[0].getAttribute("content")
                    },
                    credentials: "same-origin",
                    body: JSON.stringify(payloadDeleteFood)
                })
                .then(response => response.json())
                .then(res => {
                    if (res.status === 'failed') {

                        deleteFoodModal.hide();
                        resetAction();
                        generateMessage(res.status, res.message);
                    } else if (res.status === 'success') {

                        deleteFoodModal.hide();
                        Foods.data.splice(deleted_index_food, 1);
                        FoodDataTables.destroy();
                        initFoodTable();
                        resetAction();
                        generateMessage(res.status, res.message);
                    }
                })
                .catch(err => console.error);
        }
        /* ------- End Delete Food ------- */

        function resetAction(data = []) {
            // reset after insert categories
            if (data.length > 0) {
                data.forEach(d => {
                    const el = document.querySelector('.category-selected-' + d.id);
                    const small = el.parentElement;
                    if (el && small) {
                        selected_categories.removeChild(small);
                        const opt = document.createElement('option');
                        opt.value = d.id;
                        opt.textContent = d.name;
                        food_categories.appendChild(opt);
                        if (selected_categories.childElementCount <= 1) {
                            ctgPlaceholder.textContent = 'No category selected';
                            selected_categories.classList.remove('is-invalid');
                        }
                    }
                });
            }

            btnSubmitFood.innerHTML = 'Save';
            btnSubmitFood.removeAttribute('disabled');
            food_name.classList.remove('is-invalid');
            food_image.classList.remove('is-invalid');
            food_price.classList.remove('is-invalid');
            food_description.classList.remove('is-invalid');
            selected_categories.classList.remove('is-invalid');
            addFoodForm.reset();
            food_name_value = null;
            food_price_value = null;
            food_description_value = '';
            categories.length = 0;

            // reset after delete
            btnDeleteFood.innerHTML = 'Yes';
            btnDeleteFood.removeAttribute('disabled');
            deleted_index_food = null;
            deleteFoodForm.reset();

            // reset after update
            btnEditSubmitFood.innerHTML = 'Update';
            btnEditSubmitFood.removeAttribute('disabled');
            edit_food_name.classList.remove('is-invalid');
            edit_food_image.classList.remove('is-invalid');
            edit_food_price.classList.remove('is-invalid');
            edit_food_description.classList.remove('is-invalid');
            edit_selected_categories.classList.remove('is-invalid');
            edit_status_field.innerHTML = '';
            edit_food_status_feedback.textContent = '';
            editFoodForm.reset();

            while (edit_food_categories.childElementCount > 1) {
                edit_food_categories.removeChild(edit_food_categories.lastElementChild);
            }
            while (edit_selected_categories.childElementCount > 1) {
                edit_selected_categories.removeChild(edit_selected_categories.lastElementChild);
            }
            edit_food_name_value = null;
            edit_food_price_value = null;
            edit_food_description_value = '';
            categories.length = 0;
            updated_index_food = 0;
        }

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