
    <meta charset="utf-8" />
    <title>Management System MA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Management System MA Development" name="description" />
    <meta content="IT Menara Agung" name="author" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('assets/images/logo-ma-small.png')}}">

    <!-- plugin css -->
    <link href="{{asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css" />

    <!-- datepicker css -->
    <link rel="stylesheet" href="{{asset('assets/libs/flatpickr/flatpickr.min.css')}}">

    <!-- preloader css -->
    <link rel="stylesheet" href="{{asset('assets/css/preloader.min.css')}}" type="text/css"/>

    <!-- Bootstrap Css -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />

    <!-- DataTables -->
    <link href="{{asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

     <!-- Sweet Alert-->
    <link href="{{asset('assets/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- twitter-bootstrap-wizard css -->
    <link rel="stylesheet" href="{{asset('assets/libs/twitter-bootstrap-wizard/prettify.css')}}">

     {{-- css select2 --}}
    <link rel="stylesheet" href="{{asset('assets/css/select2.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/select2-bootstrap-5-theme.min.css')}}" />

    <style>
        /* utk membuat bottom navigation */
        .bottom_nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            height: 55px;
            box-shadow: 0 0 3px white;
            background-color: #189dfb;
            display: flex;
            overflow-x: auto;
            z-index: 98;
        }

        .bottom_nav__link {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-grow: 1;
            min-width: 50px;
            overflow: hidden;
            white-space: nowrap;
            font-family: sans-serif;
            font-size: 20px;
            color: white;
            text-decoration: none;
            -webkit-tap-highlight-color: transparent;
            transition: background-color 0.1s ease-in-out;
        }

        .bottom_nav__text {
            font-size: 11px;
        }

        .bottom_nav__link:hover {
            background-color: green;
            color: #fff;
            text-decoration: none;
        }

        .aktif {
            background-color: green;
        }

        .bottom_nav__icon {
            font-size: 18px;
        }
    </style>
