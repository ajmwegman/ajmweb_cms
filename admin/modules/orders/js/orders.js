$(function(){
    function load(page){
        $("#orderlist").load("/admin/modules/orders/bin/summary.php?page="+page);
    }
    load(1);

    $("#orderlist").on("click", ".pagination a", function(e){
        e.preventDefault();
        var page = $(this).data("page");
        load(page);
    });

    $("#orderlist").on("change", ".status-select", function(){
        var id = $(this).data("id");
        var status = $(this).val();
        $.post("/admin/modules/orders/bin/update_status.php", {id:id, status:status});
    });

    $("#export_postnl").on("click", function(){
        window.location = "/admin/modules/orders/bin/export.php?carrier=postnl";
    });

    $("#export_dhl").on("click", function(){
        window.location = "/admin/modules/orders/bin/export.php?carrier=dhl";
    });
});
