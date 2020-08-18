/**
 *
 * @type {{Created by Shahzaib 14 August,2020}}
 */
var app = {};

(function ($) {
    'use strict';

    $(document).ready(function () {
       //
    });

    //get random comment from DB
    app.sortRecords = function (order, field) {
        $.ajax({
            url: "lib/all.php",
            type: "get",
            data:{"order": order, "field": field},
            dataType: "json",
            success: function (response) {
                if(response.data instanceof Array){
                    var output = "";
                    var tableContent = document.getElementById("tableContents");
                    response.data.forEach(element => {
                        output += `<tr>
                        <td scope='col'>${element.id}</td>
                        <td scope='col'> ${element.name} </td>
                        <td scope='col'> ${element.price} </td>
                        <td scope='col'> ${element.quantity} </td>
                        <td scope='col' class='${(element.status === "active") ? "badge-success" : 'badge-secondary'} badge badge-pill m-1'> ${element.status} </td>
                        <td scope='col-auto'>
                        <a class='btn btn-sm btn-primary' href='/?edit=${element.id}'> Edit </a> 
                        <a class='btn btn-sm btn-dark' href='/?archive=${element.id}'> Archive </a> 
                        <a class='btn btn-sm btn-info' href='/?view=${element.id}'> View </a> 
                        <a class='btn btn-sm btn-danger' onclick='confirmAction('delete')' href='/?delete=${element.id}'> Delete </a> </td>
                        </tr>`;
                    });
                    tableContent.innerHTML = output;
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("error");
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    };
}(jQuery));