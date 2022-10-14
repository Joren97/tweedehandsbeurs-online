<template>
  <div id="wrapper">
    <LayoutSidebar />

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <LayoutTopbar />

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <slot></slot>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- End of Main Content -->

      <LayoutFooter />
    </div>
    <!-- End of Content Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>
  </div>
</template>
