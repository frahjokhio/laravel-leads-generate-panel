<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Clients Management</title>

   @include('common.css')

</head>

<body id="page-top">

   <!-- Page Wrapper -->
  <div id="wrapper">

      @include('common.sidebar')

      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column">

         <!-- Main Content -->
         <div id="content">

            @include('common.header')

            @yield('content')

         </div>
         <!-- End of Main Content -->

         @include('common.footer')

       </div>
       <!-- End of Content Wrapper -->

   </div>
   <!-- End of Page Wrapper -->

     <!-- Scroll to Top Button-->
     <a class="scroll-to-top rounded" href="#page-top">
       <i class="fas fa-angle-up"></i>
     </a>


      @include('common.js')
      <!-- scripts -->
      @yield('scripts')
      <!-- endbuild -->

</body>

</html>
