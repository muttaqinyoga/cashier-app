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
<!-- Edit food modal -->
<div class="modal fade text-left" id="editFoodModal" tabindex="-1" aria-labelledby="editFoodModal" role="dialog">
    <div class="modal-dialog modal-dialog-top modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h4 class="modal-title text-light">Edit Food</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                </button>
            </div>
            <form method="post" id="editFoodForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" name="edit_food_name" id="edit_food_name">
                        <div class="invalid-feedback" id="edit_food_name_feedback">

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select class="form-select" id="food_edit_categories">
                            <option selected>-Choose category--</option>
                            @foreach($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="food_edit_categories_feedback">

                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <div type="text" class="form-control" id="selected_edit_categories">
                            <small id="ctgEditPlaceholder">No category selected</small>
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
                        <textarea class="form-control" name="edit_food_description" id="edit_food_description" rows="3"></textarea>
                        <div class="invalid-feedback" id="edit_food_description_feedback">

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Image</label>
                        <div class="form-group">
                            <input class="form-control" type="file" name="edit_food_image" id="edit_food_image">
                            <div class="invalid-feedback" id="edit_food_image_feedback">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-warning ml-1" id="btnUpdateFood">
                            Update
                        </button>
                    </div>
            </form>
        </div>
    </div>
</div>
</div>
<!-- Add Food -->
<div class="modal fade text-left" id="addFoodModal" tabindex="-1" aria-labelledby="addFoodModal" role="dialog">
    <div class="modal-dialog modal-dialog-top modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title text-light">Add Food</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                </button>
            </div>
            <form method="post" id="addFoodForm">
                @csrf
                <div class="modal-body">
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
                            <button type="button" class="btn btn-warning btn-sm edit" data-bs-toggle="modal" data-bs-target="#editFoodModal" data-index="${row.dataIndex}" onclick="showEdit(${row.dataIndex})" >Edit</button>
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
        } else {
            if (!['image/jpg', 'image/jpeg', 'image/png'].includes(image.type)) {
                category_edit_image.classList.add('is-invalid');
                category_edit_image_feedback.textContent = "Only.jpg, jpeg and.png image are allowed";
                category_edit_image.value = '';
                return;
            }
            if (image.size > 100000) {
                category_edit_image.classList.add('is-invalid');
                category_edit_image_feedback.textContent = "Image size must be less than 100KB";
                category_edit_image.value = '';
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

    function removeSelected(id, name) {
        const el = document.querySelector('.category-selected-' + id);
        const small = el.parentElement;
        if (el && small) {
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
    // let category_edit_name_value = null;
    // let updated_index_category = null;
    // const editFoodModal = new bootstrap.Modal('#editFoodModal');
    // const btnEditSubmitCategory = document.querySelector('#btnEditSubmitCategory');
    // const editCategoryForm = document.querySelector('#editCategoryForm');
    // const category_edit_id = document.getElementsByName('category_edit_id')[0];
    // const category_edit_name = document.getElementsByName('category_edit_name')[0];
    // const category_edit_image = document.getElementsByName('category_edit_image')[0];
    // const category_edit_image_feedback = document.querySelector("#category_edit_image_feedback");
    // const category_edit_name_feedback = document.querySelector("#category_edit_name_feedback");
    // // End
    // function showEdit(dataIndex) {
    //     let editBtn = document.querySelectorAll('.edit');
    //     let valid = false;
    //     editBtn.forEach((el, i) => {
    //         if (el.getAttribute("data-index") == dataIndex) {
    //             category_edit_name.value = el.getAttribute("data-name");
    //             category_edit_name_value = category_edit_name.value;
    //             document.querySelector('#currentImage').setAttribute("src", el.getAttribute("data-image"));
    //             category_edit_id.value = el.getAttribute("data-id");
    //             valid = true;
    //             updated_index_category = dataIndex;
    //         }
    //     });
    //     if (!valid) {
    //         window.location.href = window.location.href;
    //     }
    // }
    // // Validate form
    // category_edit_image.addEventListener('change', () => {
    //     category_edit_image.classList.remove('is-invalid');
    //     validateImage(category_edit_image.files[0], 'edit');
    // });

    // category_edit_name.addEventListener("input", () => {
    //     category_edit_name_value = category_edit_name.value.trim();
    //     category_edit_name.classList.remove('is-invalid');
    //     if (category_edit_name_value == '') {
    //         category_edit_name.value = '';
    //         category_edit_name.classList.add('is-invalid');
    //         category_edit_name_feedback.textContent = "Name can't be empty";
    //     }
    // });
    // // End
    // // Submit form
    // editCategoryForm.addEventListener("submit", function(e) {
    //     e.preventDefault();
    //     if (category_edit_name_value == '' || category_edit_name_value == null) {
    //         category_edit_name.classList.add('is-invalid');
    //         category_edit_name_feedback.textContent = "Name can't be empty";

    //     } else {
    //         const payloadCategory = new FormData(editCategoryForm);

    //         btnEditSubmitCategory.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...`;
    //         btnEditSubmitCategory.setAttribute('disabled', 'disabled');

    //         fetch("{{ url('admin/categories/update') }}", {
    //                 method: "POST",
    //                 headers: {
    //                     accept: 'application/json'
    //                 },
    //                 credentials: "same-origin",
    //                 body: payloadCategory
    //             })
    //             .then(response => response.json())
    //             .then(res => {
    //                 if (res.status === 'failed') {
    //                     if (res.errors) {
    //                         Object.keys(res.errors).forEach((key, index) => {
    //                             const elemInput = document.getElementById(key);
    //                             const elemFeedBack = document.getElementById(key + "_feedback");
    //                             if (elemInput && elemFeedBack) {
    //                                 elemInput.classList.add('is-invalid');
    //                                 elemFeedBack.textContent = res.errors[key][0];
    //                                 btnEditSubmitCategory.innerHTML = 'Update';
    //                                 btnEditSubmitCategory.removeAttribute('disabled');
    //                             }
    //                         });
    //                         return;
    //                     }
    //                     editFoodModal.hide();
    //                     resetAction();
    //                     generateMessage(res.status, res.message);
    //                 } else if (res.status === 'success') {
    //                     editFoodModal.hide();
    //                     Foods.data[updated_index_category][0] = res.data.id;
    //                     Foods.data[updated_index_category][1] = res.data.slug;
    //                     Foods.data[updated_index_category][2] = res.data.name;
    //                     Foods.data[updated_index_category][3] = res.data.image;
    //                     Foods.data[updated_index_category][4] = res.data.updated_at;
    //                     Foods.data.sort((a, b) => {
    //                         return new Date(b[4]).getTime() - new Date(a[4]).getTime();
    //                     });
    //                     FoodDataTables.destroy();
    //                     initFoodTable();
    //                     resetAction();
    //                     generateMessage(res.status, res.message);

    //                 }
    //             })
    //             .catch(err => console.error);
    //     }
    // });
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
    /* ------- End Delete Categories ------- */

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


        // editCategoryForm.reset();

        // btnEditSubmitCategory.innerHTML = 'Update';
        // btnEditSubmitCategory.removeAttribute('disabled');

        // category_edit_name.classList.remove('is-invalid');
        // category_edit_image.classList.remove('is-invalid');
        // category_name.classList.remove('is-invalid');
        // category_image.classList.remove('is-invalid');

        // category_edit_name_value = null;

        // updated_index_category = null;
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