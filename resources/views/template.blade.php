<!DOCTYPE html>
<html lang="en" data-bs-theme="">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kneat</title>

    <link rel="Shortcut icon" href = "{{ asset('images/logo.jpg') }}"alt="">
    <link rel="stylesheet" crossorigin href="{{ asset('compiled/css/app.css') }}">
    <link rel="stylesheet" crossorigin href="{{ asset('compiled/css/app-dark.css') }}">
    <link rel="stylesheet" crossorigin href="{{ asset('compiled/css/iconly.css') }}">
    <link rel="stylesheet" href="{{ asset('extensions/simple-datatables/style.css') }}">
    <link rel="stylesheet" crossorigin href="{{ asset('compiled/css/table-datatable.css') }}">
    <style>
        .drop-area {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            cursor: pointer;
        }

        .drop-area.hover {
            background-color: #f0f0f0;
            border-color: #000;
        }

        .drop-area p {
            margin: 0;
            font-size: 16px;
        }

        .preview-container {
            margin-top: 15px;
        }

        .img-fluid {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <script src="{{ asset('static/js/initTheme.js') }}"></script>
    <div id="app">
        <div id="sidebar">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="logo">
                            <a href="{{ url('dashboard') }}"><img src="{{ asset('images/logo.jpg') }}"
                                    alt="Logo Perusahaan" style="width: 80px; height: auto;">
                            </a>
                        </div>

                        <div class="sidebar-toggler  x">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i
                                    class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>
                        <li class="sidebar-item">
                            <a href="{{ url('dashboard') }}" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ url('inventory') }}" class='sidebar-link'>
                                <i class="bi bi-box2-fill"></i>
                                <span>Inventory</span>
                            </a>
                        </li>
                        <li class="sidebar-item has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-building-fill"></i>
                                <span>Manufacturing</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item  ">
                                    <a href="{{ url('produk') }}" class="submenu-link">Produk</a>
                                </li>
                                <li class="submenu-item  ">
                                    <a href="{{ url('bahan_baku') }}" class="submenu-link">Bahan Baku</a>
                                </li>
                                <li class="submenu-item  ">
                                    <a href="{{ url('bom') }}" class="submenu-link">Bill Of Materials</a>
                                </li>
                                <li class="submenu-item  ">
                                    <a href="{{ url('manufacturing_orders') }}" class="submenu-link">Manufacturing
                                        Orders</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-cash"></i>
                                <span>Purchasing</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item  ">
                                    <a href="{{ url('vendors') }}" class="submenu-link">Vendor</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="main" class="layout-navbar navbar-fixed">
            <header>
                <nav class="navbar navbar-expand navbar-light navbar-top">
                    <div class="container-fluid">
                        <a href="#" class="burger-btn d-block">
                            <i class="bi bi-justify fs-3"></i>
                        </a>

                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mb-lg-0">
                                {{-- <li class="nav-item dropdown me-1">
                                    <a class="nav-link active dropdown-toggle text-gray-600" href="#"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-envelope bi-sub fs-4"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                        <li>
                                            <h6 class="dropdown-header">Mail</h6>
                                        </li>
                                        <li><a class="dropdown-item" href="#">No new mail</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown me-3">
                                    <a class="nav-link active dropdown-toggle text-gray-600" href="#"
                                        data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                        <i class="bi bi-bell bi-sub fs-4"></i>
                                        <span class="badge badge-notification bg-danger">7</span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown"
                                        aria-labelledby="dropdownMenuButton">
                                        <li class="dropdown-header">
                                            <h6>Notifications</h6>
                                        </li>
                                        <li class="dropdown-item notification-item">
                                            <a class="d-flex align-items-center" href="#">
                                                <div class="notification-icon bg-primary">
                                                    <i class="bi bi-cart-check"></i>
                                                </div>
                                                <div class="notification-text ms-4">
                                                    <p class="notification-title font-bold">
                                                        Successfully check out
                                                    </p>
                                                    <p class="notification-subtitle font-thin text-sm">
                                                        Order ID #256
                                                    </p>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="dropdown-item notification-item">
                                            <a class="d-flex align-items-center" href="#">
                                                <div class="notification-icon bg-success">
                                                    <i class="bi bi-file-earmark-check"></i>
                                                </div>
                                                <div class="notification-text ms-4">
                                                    <p class="notification-title font-bold">
                                                        Homework submitted
                                                    </p>
                                                    <p class="notification-subtitle font-thin text-sm">
                                                        Algebra math homework
                                                    </p>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <p class="text-center py-2 mb-0">
                                                <a href="#">See all notification</a>
                                            </p>
                                        </li>
                                    </ul>
                                </li> --}}
                            </ul>
                            <div class="dropdown">
                                <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-menu d-flex">
                                        <div class="user-name text-end me-3">
                                            <h6 class="mb-0 text-gray-600">
                                                {{-- {{ session('userdata')->nama }} --}} Admin
                                            </h6>
                                            {{-- @if (Auth::check())
                                                @if (Auth::user()->role == 'kepala_sekolah')
                                                    <p class="mb-0 text-sm text-gray-600">
                                                        Kepala Sekolah
                                                    </p>
                                                @elseif (Auth::user()->role == 'petugas')
                                                    <p class="mb-0 text-sm text-gray-600">
                                                        Petugas
                                                    </p>
                                                @elseif (Auth::user()->role == 'siswa')
                                                    <p class="mb-0 text-sm text-gray-600">
                                                        Siswa
                                                    </p>
                                                @endif
                                            @endif --}}
                                        </div>
                                        <div class="user-img d-flex align-items-center">
                                            <div class="avatar avatar-md">
                                                {{-- @if (Auth::check())
                                                    @if (Auth::user()->role == 'kepala_sekolah')
                                                        <img
                                                            src="{{ url('storage/foto-kepsek/' . session('userdata')->foto) }}" />
                                                    @elseif (Auth::user()->role == 'petugas')
                                                        <img
                                                            src="{{ url('storage/foto-petugas/' . session('userdata')->foto) }}" />
                                                    @elseif (Auth::user()->role == 'siswa')
                                                        <img
                                                            src="{{ url('storage/foto-siswa/' . session('userdata')->foto) }}" />
                                                    @endif
                                                @endif --}}

                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton"
                                    style="min-width: 11rem">
                                    <li>
                                        <h6 class="dropdown-header">Hello, Admin!</h6>
                                    </li>
                                    {{-- <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="icon-mid bi bi-person me-2"></i>
                                            My Profile
                                        </a>
                                    </li> --}}
                                    <li>
                                        <hr class="dropdown-divider" />
                                    </li>
                                    <li>
                                        {{-- @if (Auth::user()->role == 'siswa')
                                            <a class="dropdown-item"
                                                href="{{ url('siswa/edit/profile/' . session('userdata')->id_siswa) }}">
                                                <i class="icon-mid bi bi-pencil me-2"></i>
                                                Edit Profile
                                            </a>
                                        @endif --}}
                                        {{-- <a class="dropdown-item" href="{{ route('logout') }}">
                                            <i class="icon-mid bi bi-box-arrow-left me-2"></i>
                                            Logout
                                        </a> --}}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>
            <div id="main-content">
                @yield('content')
            </div>
        </div>

    </div>

    <script src="{{ asset('extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('compiled/js/app.js') }}"></script>

    @include('sweetalert::alert')

    <!-- Need: Apexcharts -->
    <script src="{{ asset('extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script></script>

    {{-- <script src="{{ asset('static/js/pages/dashboard.js') }}"></script> --}}

    <script src="{{ asset('extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
    <script src="{{ asset('static/js/pages/simple-datatables.js') }}"></script>
    <script src="{{ asset('static/js/components/dark.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('bom_detail_form')

    <script script src="https://cdn.tiny.cloud/1/1n3f7wnxsqlud0ga3vqsndjt3zhzvf7skeun894b43byqkwk/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            tinymce.init({
                selector: '#txtarea',
                height: 300,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount'
                ],
                toolbar: 'undo redo | formatselect | bold italic backcolor | \
                                                                  alignleft aligncenter alignright alignjustify | \
                                                                  bullist numlist outdent indent | removeformat | help',
            });
        });
    </script>


    <script>
        var dropArea = document.getElementById('drop-area');
        var fileInput = document.getElementById('file-upload');
        var previewContainer = document.getElementById('preview-container');
        var previewImage = document.getElementById('file-preview');

        // Prevent default behavior (Prevent file from being opened)
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Highlight drop area when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, () => {
                dropArea.classList.add('hover');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, () => {
                dropArea.classList.remove('hover');
            }, false);
        });

        // Handle dropped files
        dropArea.addEventListener('drop', (e) => {
            var files = e.dataTransfer.files;
            if (files.length) {
                fileInput.files = files; // Assign files to the input
                handleFiles(files); // Preview the file
            }
        });

        // Allow clicking on the drop area to select files
        dropArea.addEventListener('click', () => {
            fileInput.click();
        });

        // Handle file input change
        fileInput.addEventListener('change', (e) => {
            handleFiles(fileInput.files); // Preview the file
        });

        // Function to handle file input and show the preview
        function handleFiles(files) {
            var file = files[0];
            if (file && file.type.startsWith('image/')) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var currentUrl = window.location.href;

            var sidebarItems = document.querySelectorAll('.sidebar-item a');
            sidebarItems.forEach(function(item) {
                var href = item.getAttribute('href');

                if (currentUrl.includes(href)) {
                    item.closest('.sidebar-item').classList.add('active');

                    var parentSubmenu = item.closest('.submenu-item');
                    if (parentSubmenu) {
                        parentSubmenu.classList.add('active');
                    }
                }
            });
        });
    </script>


</body>

</html>
