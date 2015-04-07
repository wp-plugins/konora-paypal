/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
 * 
 * Attenzione la chiamata funziona solo sul primo file in elenco nella tabella!!
 * 
 * 
 */

jQuery(document).ready(function ($) {

    $("#delete").click(function () {
//        alert("File da cancellare");
        var clickBtnValue = $(this).val();
//        alert(wnm_custom.template_url);
        var ajaxurl = wnm_custom.template_url,
                data = {'action': clickBtnValue};

        $.post(ajaxurl)
                .done(function (data) {
                    console.log(data);
                });
//        $.post(ajaxurl, data, function (select) {
//            // Response div goes here.
//            alert("action performed successfully");
//        });
    });
    function copyToClipboard(text) {
        window.prompt("Copia il link: Ctrl+C, Enter", text);
    }
    // Use JQuery

    $('#test').click(function () {
        var div = document.getElementById("test");
        var myData = div.textContent;
        copyToClipboard(myData)
    });
});