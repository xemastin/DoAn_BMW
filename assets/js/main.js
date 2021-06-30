function Delete(idProduct){
    if (confirm(`Bạn có muốn xóa hàng hóa có id là ${idProduct} hay không ?`)) {
        $.ajax({
            url: window.location,
            type: "POST",
            data: {
                action: "delete",
                idProduct: idProduct,
            },
            success: function (data) {
                data = JSON.parse(data)
                if (data["action"] == "delete" && data["status"] == "OK"){
                    var row = document.getElementById("laptop" + idProduct)
                    row.parentElement.removeChild(row)
                }
                if (data["action"] == "delete" && data["status"] == "ERROR") {
                    alert("Đã có lỗi xảy ra khi xóa")
                }
            },
        });
    }
}