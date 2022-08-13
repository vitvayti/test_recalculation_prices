BX.ready(function(){
    var object = BX('iblock_recalculation');
    var url = 'recalculation_ajax.php';

    BX.bind(object, 'change',function(){
        var postData = {
            'action': 'iblock',
            'value': this.value
        };

        BX.ajax({
            timeout: 30,
            method: 'POST',
            dataType: 'html',
            url: url,
            data: postData,

            onsuccess: function (result) {
                if (result) {
                    document.getElementById("section_recalculation").innerHTML = result;
                } else if (result && result.ERROR) {
                    BX.debug("Error receiving restriction params html: " + result.ERROR);
                } else {
                    BX.debug("Error receiving restriction params html!");
                }
            },

            onfailure: function () {
                BX.debug("Error adding restriction!");
            }
        });
    })
})