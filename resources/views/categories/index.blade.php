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
                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
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
<div class="modal fade text-left" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModal" role="dialog">
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
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-success ml-1" id="btnSubmitCategory">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Delete Category Modal -->
<div class="modal fade text-left" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModal" role="dialog">
    <div class="modal-dialog modal-dialog-top modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title text-light">Delete Food Category</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                </button>
            </div>
            <form method="post" id="deleteCategoryForm">
                @csrf
                @method('DELETE')
                <input type="hidden" name="category_delete_id" id="category_delete_id">
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger ml-1" id="btnDeleteCategory">
                        Yes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Delete Category Modal -->
<!-- Edit Categories -->
<div class="modal fade text-left" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModal" role="dialog">
    <div class="modal-dialog modal-dialog-top modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h4 class="modal-title text-light">Edit Food Category</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                </button>
            </div>
            <form method="post" id="editCategoryForm">
                @csrf
                <input type="hidden" name="category_edit_id" id="category_edit_id">
                <div class="modal-body">
                    <label>Name</label>
                    <div class="form-group">
                        <input type="text" placeholder="Enter category food name..." class="form-control" name="category_edit_name" id="category_edit_name">
                        <div class="invalid-feedback" id="category_edit_name_feedback">

                        </div>
                    </div>
                    <label>Image</label>
                    <div class="form-group mb-2">
                        <img src="" class="img-fluid d-block" alt="food-categories" width="100" id="currentImage">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="file" name="category_edit_image" id="category_edit_image">
                        <div class="invalid-feedback" id="category_edit_image_feedback">

                        </div>
                    </div>
                    <small class="text-info">* Let it blank if image would not change</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-warning ml-1" id="btnEditSubmitCategory">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End edit categories -->
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
    const navCategories = document.querySelector('.categories');
    navCategories.classList.add('active');
    // Generate toast for message alert
    function generateMessage(status, message) {
        const toastAlert = document.getElementById('toastAlert');
        const toast = new bootstrap.Toast(toastAlert);
        const toastBody = document.querySelector(".toast-body");
        if (status === 'success' || status === 'created') {
            toastAlert.classList.remove("bg-danger");
            toastAlert.classList.add("bg-success");
            toastBody.textContent = message;
        } else {
            toastAlert.classList.remove("bg-success");
            toastAlert.classList.add("bg-danger");
            toastBody.textContent = message;
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
        headings: ["Id", "Slug", "Name", "Image", "Action"],
        data: []
    };
    let FoodDataTables = null;

    function initFoodTable() {
        const foodTables = document.querySelector('#foodTables');
        FoodDataTables = new simpleDatatables.DataTable(foodTables, {
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
                    render: function(data, cell, row) {
                        return `
                            <button type="button" class="btn btn-warning btn-sm edit" data-id="${row.childNodes[0].textContent}" data-name="${row.childNodes[2].textContent}" data-image="${row.childNodes[3].childNodes[0].src}" data-bs-toggle="modal" data-bs-target="#editCategoryModal" data-index="${row.dataIndex}" onclick="showEdit(${row.dataIndex})" >Edit</button>
                            <button type="button" class="btn btn-danger btn-sm delete" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal" data-index="${row.dataIndex}" onclick="showDeleteConfirm(${row.dataIndex})" >Delete</button>
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
    /* ------- Get Categories ------- */
    fetch("{{ url('admin/categories/get') }}")
        .then(response => {
            return response.json();
        })
        .then(res => {
            if (res.status === 'success') {
                loadingDiv.classList.toggle('d-none');
                for (let i = 0; i < res.data.length; i++) {
                    Foods.data[i] = [];
                    Foods.data[i].push(res.data[i]['id'])
                    Foods.data[i].push(res.data[i]['slug'])
                    Foods.data[i].push(res.data[i]['name'])
                    Foods.data[i].push(res.data[i]['image'])
                    Foods.data[i].push(res.data[i]['created_at'])
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
    /* ------- End Get Categories ------- */

    /* ------- Save Categories ------- */
    // Initialize Var and DOM
    let category_name_value = null;
    const addCategoryModal = new bootstrap.Modal('#addCategoryModal');
    const btnSubmitCategory = document.querySelector('#btnSubmitCategory');
    const addCategoryForm = document.querySelector("#addCategoryForm");
    const category_name = document.getElementsByName('category_name')[0];
    const category_image = document.getElementsByName('category_image')[0];
    const category_name_feedback = document.querySelector("#category_name_feedback");
    const category_image_feedback = document.querySelector("#category_image_feedback");
    // END
    // Validate form
    category_name.addEventListener("input", () => {
        category_name_value = category_name.value.trim();
        category_name.classList.remove('is-invalid');
        if (category_name_value == '') {
            category_name.value = '';
            category_name.classList.add('is-invalid');
            category_name_feedback.textContent = "Name can't be empty";
        }
    });
    category_image.addEventListener('change', () => {
        category_image.classList.remove('is-invalid');
        validateImage(category_image.files[0], 'add');
    });
    // End
    // Submit form
    addCategoryForm.addEventListener("submit", function(e) {
        e.preventDefault();
        if (category_name_value == '' || category_name_value == null) {
            category_name.classList.add('is-invalid');
            category_name_feedback.textContent = "Name can't be empty";

        } else {
            const payloadCategory = new FormData(addCategoryForm);
            btnSubmitCategory.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...`;
            btnSubmitCategory.setAttribute('disabled', 'disabled');

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
                                    btnSubmitCategory.innerHTML = 'Save';
                                    btnSubmitCategory.removeAttribute('disabled');
                                }
                            });
                            return;
                        }
                        addCategoryModal.hide();
                        resetAction();
                        generateMessage(res.status, res.message);
                    } else if (res.status === 'created') {
                        resetAction();
                        addCategoryModal.hide();
                        const currLength = Foods.data.length;
                        Foods.data.push([]);
                        Foods.data[currLength].push(res.data.id);
                        Foods.data[currLength].push(res.data.slug);
                        Foods.data[currLength].push(res.data.name);
                        Foods.data[currLength].push(res.data.image);
                        Foods.data[currLength].push(res.data.created_at);
                        Foods.data.sort((a, b) => {
                            return new Date(b[4]).getTime() - new Date(a[4]).getTime();
                        });
                        FoodDataTables.destroy();
                        initFoodTable();
                        generateMessage(res.status, res.message);
                    }
                })
                .catch(err => console.error);
        }
    });
    // END
    /* ------- End Save Categories ------- */

    /* ------- Update Categories ------- */
    // Initialize var and DOM
    let category_edit_name_value = null;
    let updated_index_category = null;
    const editCategoryModal = new bootstrap.Modal('#editCategoryModal');
    const btnEditSubmitCategory = document.querySelector('#btnEditSubmitCategory');
    const editCategoryForm = document.querySelector('#editCategoryForm');
    const category_edit_id = document.getElementsByName('category_edit_id')[0];
    const category_edit_name = document.getElementsByName('category_edit_name')[0];
    const category_edit_image = document.getElementsByName('category_edit_image')[0];
    const category_edit_image_feedback = document.querySelector("#category_edit_image_feedback");
    const category_edit_name_feedback = document.querySelector("#category_edit_name_feedback");
    // End
    function showEdit(dataIndex) {
        let editBtn = document.querySelectorAll('.edit');
        let valid = false;
        editBtn.forEach((el, i) => {
            if (el.getAttribute("data-index") == dataIndex) {
                category_edit_name.value = el.getAttribute("data-name");
                category_edit_name_value = category_edit_name.value;
                document.querySelector('#currentImage').setAttribute("src", el.getAttribute("data-image"));
                category_edit_id.value = el.getAttribute("data-id");
                valid = true;
                updated_index_category = dataIndex;
            }
        });
        if (!valid) {
            window.location.href = window.location.href;
        }
    }
    // Validate form
    category_edit_image.addEventListener('change', () => {
        category_edit_image.classList.remove('is-invalid');
        validateImage(category_edit_image.files[0], 'edit');
    });

    category_edit_name.addEventListener("input", () => {
        category_edit_name_value = category_edit_name.value.trim();
        category_edit_name.classList.remove('is-invalid');
        if (category_edit_name_value == '') {
            category_edit_name.value = '';
            category_edit_name.classList.add('is-invalid');
            category_edit_name_feedback.textContent = "Name can't be empty";
        }
    });
    // End
    // Submit form
    editCategoryForm.addEventListener("submit", function(e) {
        e.preventDefault();
        if (category_edit_name_value == '' || category_edit_name_value == null) {
            category_edit_name.classList.add('is-invalid');
            category_edit_name_feedback.textContent = "Name can't be empty";

        } else {
            const payloadCategory = new FormData(editCategoryForm);

            btnEditSubmitCategory.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...`;
            btnEditSubmitCategory.setAttribute('disabled', 'disabled');

            fetch("{{ url('admin/categories/update') }}", {
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
                                    btnEditSubmitCategory.innerHTML = 'Update';
                                    btnEditSubmitCategory.removeAttribute('disabled');
                                }
                            });
                            return;
                        }
                        editCategoryModal.hide();
                        resetAction();
                        generateMessage(res.status, res.message);
                    } else if (res.status === 'success') {
                        editCategoryModal.hide();
                        Foods.data[updated_index_category][0] = res.data.id;
                        Foods.data[updated_index_category][1] = res.data.slug;
                        Foods.data[updated_index_category][2] = res.data.name;
                        Foods.data[updated_index_category][3] = res.data.image;
                        Foods.data[updated_index_category][4] = res.data.updated_at;
                        Foods.data.sort((a, b) => {
                            return new Date(b[4]).getTime() - new Date(a[4]).getTime();
                        });
                        FoodDataTables.destroy();
                        initFoodTable();
                        resetAction();
                        generateMessage(res.status, res.message);

                    }
                })
                .catch(err => console.error);
        }
    });
    /* ------- End Update Categories ------- */
    /* ------- Delete Categories ------- */
    // Init var dan DOM
    let deleted_index_category = null;
    const textDeleteCategory = document.querySelector("#deleteCategoryForm > .modal-body");
    const deleteCategoryModal = new bootstrap.Modal('#deleteCategoryModal');
    const btnDeleteCategory = document.querySelector('#btnDeleteCategory');
    const deleteCategoryForm = document.querySelector('#deleteCategoryForm');
    const category_delete_id = document.getElementsByName('category_delete_id')[0];
    // END
    function showDeleteConfirm(dataIndex) {
        let deleteBtn = document.querySelectorAll('.delete');
        let valid = false;
        deleteBtn.forEach((el, i) => {
            if (el.getAttribute("data-index") == dataIndex) {
                const foodCategoryName = FoodDataTables.activeRows[dataIndex].firstElementChild.textContent;
                textDeleteCategory.innerHTML = `<p> Do you want to delete <strong>${foodCategoryName}</strong> from Food Category List ? </p>`;
                const category_id = FoodDataTables.data[dataIndex].firstElementChild.textContent;
                category_delete_id.value = category_id;
                valid = true;
                deleted_index_category = dataIndex;
            }
        });
        if (!valid) {
            window.location.href = window.location.href;
        }
    }
    // Submit form
    deleteCategoryForm.addEventListener("submit", function(e) {
        e.preventDefault();
        if (category_delete_id.value == '' || category_delete_id.value == null) {
            window.location.href = window.location.href;
        } else {
            if (deleted_index_category == 0) {
                fetchDelete();
            } else if (deleted_index_category == null || deleted_index_category == '') {
                window.location.href = window.location.href;
            } else {
                fetchDelete();
            }

        }
    });

    function fetchDelete() {
        const payloadDeleteCategory = {
            _token: document.getElementsByName("_token")[0].getAttribute("value"),
            _method: document.getElementsByName("_method")[0].getAttribute("value"),
            category_delete_id: document.getElementsByName("category_delete_id")[0].getAttribute("value")
        }
        btnDeleteCategory.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...`;
        btnDeleteCategory.setAttribute('disabled', 'disabled');

        fetch("{{ url('admin/categories/delete') }}", {
                method: "DELETE",
                headers: {
                    accept: 'application/json',
                    'Content-type': 'application/json; charset=UTF-8',
                    'X-CSRF-TOKEN': document.getElementsByName("csrf-token")[0].getAttribute("content")
                },
                credentials: "same-origin",
                body: JSON.stringify(payloadDeleteCategory)
            })
            .then(response => response.json())
            .then(res => {
                if (res.status === 'failed') {

                    deleteCategoryModal.hide();
                    resetAction();
                    generateMessage(res.status, res.message);
                } else if (res.status === 'success') {

                    deleteCategoryModal.hide();
                    Foods.data.splice(deleted_index_category, 1);
                    FoodDataTables.destroy();
                    initFoodTable();
                    resetAction();
                    generateMessage(res.status, res.message);
                }
            })
            .catch(err => console.error);
    }
    /* ------- End Delete Categories ------- */

    function resetAction() {
        addCategoryForm.reset();
        deleteCategoryForm.reset();
        editCategoryForm.reset();
        btnSubmitCategory.innerHTML = 'Save';
        btnSubmitCategory.removeAttribute('disabled');
        btnEditSubmitCategory.innerHTML = 'Update';
        btnEditSubmitCategory.removeAttribute('disabled');
        btnDeleteCategory.innerHTML = 'Yes';
        btnDeleteCategory.removeAttribute('disabled');
        category_edit_name.classList.remove('is-invalid');
        category_edit_image.classList.remove('is-invalid');
        category_name.classList.remove('is-invalid');
        category_image.classList.remove('is-invalid');
        category_name_value = null;
        category_edit_name_value = null;
        deleted_index_category = null;
        updated_index_category = null;
    }
</script>
@endsection