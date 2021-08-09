<!-- /.content-wrapper -->
<footer class="main-footer text-left">
    (: CopyLeft &copy; 2021 <a href="https://hadboard.ir">  HadBoard </a>
</footer>

</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="public/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="public/plugins/morris/morris.min.js"></script>
<!-- Sparkline -->
<script src="public/plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="public/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="public/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="public/plugins/knob/jquery.knob.js"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
<script src="public/plugins/daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="public/plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="public/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="public/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="public/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="public/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="public/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="public/js/demo.js"></script>
<!-- DataTables -->
<script src="public/plugins/datatables/jquery.dataTables.js"></script>
<script src="public/plugins/datatables/dataTables.bootstrap4.js"></script>


<!-- jQuery -->
<script src="public/plugins/jquery/jquery.min.js"></script>
<!-- DataTables -->
<script src="public/plugins/datatables/jquery.dataTables.js"></script>
<script src="public/plugins/datatables/dataTables.bootstrap4.js"></script>

<script>
    $(function () {
        $("#example").DataTable({
            dom: 'Bfrtip',
            "language": {
                "lengthMenu": "Display _MENU_ records per page",
                "zeroRecords": "اطلاعاتی یافت نشد",
                "info": "نمایش صفحه _PAGE_ از _PAGES_",
                "infoEmpty": "اطلاعاتی یافت نشد",
                "infoFiltered": "(فیلتر شده از _MAX_ مجموع رکوردها)",
                "paginate": {
                    "first":      "اولین",
                    "last":       "آخرین",
                    "next":       "بعد",
                    "previous":   "قبل"
                },
                "search":"جستجو: "
            },
            buttons: [
                'csv', 'excel', 'pdf', 'print'
            ]
        });
        $('#example1').DataTable({
            "language": {
                "paginate": {
                    "next": "بعدی",
                    "previous" : "قبلی"
                }
            },
            "info" : false,
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "autoWidth": false
        });
    });
</script>

</body>
</html>
