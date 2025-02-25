(function () {
    "use strict";

    const addInvoiceModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#add-invoice-modal"));
    const editInvoiceModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#edit-invoice-modal"));
    let isNonVatCheck = $("#nonVatInvoiceCheck").is(":checked");

    let rowCounter = 2;


    function updateInvoiceTotals() {
        let subtotal = 0;
        let vatTotal = 0;
        let grandTotal = 0;
    
        $(".invoiceItemsTable tbody tr").each(function () {
            let units = parseFloat($(this).find(".units").text().trim()) || 0;
            let price = parseFloat($(this).find(".price").text().trim().replace("$", "")) || 0;
    
            let lineTotal = units * price;
            let vat = 0;
    
            if (!isNonVatCheck) {
                let vatPercentage = parseFloat($(this).find(".vat").text().trim().replace("$", "")) || 0;
                vat = (lineTotal * vatPercentage) / 100;
            }
    
            let totalWithVat = lineTotal + vat;
    
            subtotal += lineTotal;
            vatTotal += vat;
            grandTotal += totalWithVat;
        });
    
        $(".subtotal_price").text(subtotal.toFixed(2));
        $(".vat_total_price").text(vatTotal.toFixed(2));
        $(".total_price").text(grandTotal.toFixed(2));
        $(".due_price").text(grandTotal.toFixed(2));
    }
    



    $("#addInvoiceModalShow").on("click", function () {
        if (isNonVatCheck) {
            $("#add-invoice-modal .addInvoiceVatField").addClass("hidden");
        } else {
            $("#add-invoice-modal .addInvoiceVatField").removeClass("hidden");
        }
        addInvoiceModal.show();
    });

    $(".addInvoiceModalHide").on("click", function () {
        addInvoiceModal.hide();
    });

    $(document).on("click", ".editInvoiceModal", function () {
        const row = $(this).closest("tr");
        const dataId = row.attr("data-id");
        const description = row.find("td.description .whitespace-nowrap").text().trim();
        const units = row.find("td.units").text().trim();
        const price = row.find("td .price").text().trim().replace("$", "");
        const vat = row.find("td .vat").text().trim().replace("$", "");
    
        $("#edit-invoice-modal textarea[name='edit_description']").val(description);
        $("#edit-invoice-modal input[name='edit_units']").val(units);
        $("#edit-invoice-modal input[name='edit_price']").val(price);
        $("#edit-invoice-modal input[name='edit_vat']").val(vat);

        if (isNonVatCheck) {
            $("#edit-invoice-modal .editInvoiceVatField").addClass("hidden");
        } else {
            $("#edit-invoice-modal .editInvoiceVatField").removeClass("hidden");
        }
    
        $("#edit-invoice-modal").attr("data-edit-id", dataId);
    
        editInvoiceModal.show();
    });
    

    $(".editInvoiceModalHide").on("click", function () {
        editInvoiceModal.hide();
    });

    $("#add-invoice-modal .AddInvoiceItemBtn").on("click", function () {
        const description = $("#add-invoice-modal textarea[name='add_description']").val().trim();
        const units = $("#add-invoice-modal input[name='add_units']").val().trim();
        const price = $("#add-invoice-modal input[name='add_price']").val().trim();
        const vat = isNonVatCheck ? "0" : $("#add-invoice-modal input[name='add_vat']").val().trim();

        if (!description || !units || !price) {
            alert("Description, Units, and Price are required.");
            return;
        }
        if (isNaN(units) || isNaN(price) || (!isNonVatCheck && isNaN(vat))) {
            alert("Units, Price, and VAT must be numeric values.");
            return;
        }

        const totalPrice = (parseFloat(units) * parseFloat(price)).toFixed(2);

        const grandTotal = (parseFloat(totalPrice) + (isNonVatCheck ? 0 : parseFloat(vat))).toFixed(2);

        let vatColumn = "";
        if (!isNonVatCheck) {
            vatColumn = `
                <td class="px-5 py-3 vatField w-32 border-b text-right font-medium dark:border-darkmode-400">
                    <span class="currency">$</span> <span class="vat">${vat}</span>
                </td>
            `;
        }else{
            vatColumn = `
                <td class="hidden px-5 py-3 vatField w-32 border-b text-right font-medium dark:border-darkmode-400">
                    <span class="currency">$</span> <span class="vat">${vat}</span>
                </td>
            `;
        }
        
        const newRow = `
            <tr class="editInvoiceModal" data-id="${rowCounter}">
                <td class="description px-5 py-3 border-b dark:border-darkmode-400 flex gap-2">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="grip-vertical" class="lucide lucide-grip-vertical stroke-1.5 w-5 h-5 mx-auto block"><circle cx="9" cy="12" r="1"></circle><circle cx="9" cy="5" r="1"></circle><circle cx="9" cy="19" r="1"></circle><circle cx="15" cy="12" r="1"></circle><circle cx="15" cy="5" r="1"></circle><circle cx="15" cy="19" r="1"></circle></svg>
                    </div>
                    <div class="whitespace-nowrap font-medium">${description}</div>
                </td>
                <td class="units px-5 py-3 w-32 border-b text-right dark:border-darkmode-400">
                    ${units}
                </td>
                <td class="px-5 py-3 w-32 border-b text-right dark:border-darkmode-400">
                    <span class="currency">$</span> <span class="price">${price}</span>
                </td>
                ${vatColumn}
                <td class="px-5 py-3 w-32 border-b text-right font-medium dark:border-darkmode-400">
                    <span class="currency">$</span> <span class="lineTotal">${grandTotal}</span>
                </td>
            </tr>
        `;

        $(".invoiceItemsTable tbody").append(newRow);

        rowCounter++;

        $("#add-invoice-modal textarea[name='add_description']").val("");
        $("#add-invoice-modal input[name='add_units']").val("");
        $("#add-invoice-modal input[name='add_price']").val("");
        $("#add-invoice-modal input[name='add_vat']").val("");

        updateInvoiceTotals();
        addInvoiceModal.hide();
    });

    $("#edit-invoice-modal .updateInvoiceItemBtn").on("click", function () {
        const description = $("#edit-invoice-modal textarea[name='edit_description']").val().trim();
        const units = $("#edit-invoice-modal input[name='edit_units']").val().trim();
        const price = $("#edit-invoice-modal input[name='edit_price']").val().trim();
        let vat = 0;

        if (!description || !units || !price) {
            alert("Description, Units, and Price are required.");
            return;
        }
        if (isNaN(units) || isNaN(price) || (!isNonVatCheck && isNaN(vat))) {
            alert("Units, Price, and VAT must be numeric values.");
            return;
        }

        const totalPrice = (parseFloat(units) * parseFloat(price)).toFixed(2);
        const grandTotal = (parseFloat(totalPrice) + (isNonVatCheck ? 0 : parseFloat(vat))).toFixed(2);

        const dataId = $("#edit-invoice-modal").attr("data-edit-id");
        const row = $(".invoiceItemsTable tbody tr.editInvoiceModal[data-id='" + dataId + "']");

        if(isNonVatCheck){
            vat = row.find("td .vat").text();
        }else{
            vat = $("#edit-invoice-modal input[name='edit_vat']").val().trim();
        }

        row.find("td.description .whitespace-nowrap").text(description);
        row.find("td.units").text(units);
        row.find("td .price").text(price);
        row.find("td .vat").text(vat);
        row.find("td .lineTotal").text(grandTotal);

        updateInvoiceTotals();
        editInvoiceModal.hide();

        $("#edit-invoice-modal textarea[name='edit_description']").val("");
        $("#edit-invoice-modal input[name='edit_units']").val("");
        $("#edit-invoice-modal input[name='edit_price']").val("");
        $("#edit-invoice-modal input[name='edit_vat']").val("");

        $("#edit-invoice-modal").removeAttr("data-edit-id");
    });

    $("#nonVatInvoiceCheck").on("change", function () {
        isNonVatCheck = $(this).is(":checked");
        if (isNonVatCheck) {
            $(".invoiceItemsTable thead tr th.vatField, .invoiceItemsTable tbody tr td.vatField").addClass("hidden");
            $(".calculation .vatTotalField").addClass("hidden");
        } else {
            $(".invoiceItemsTable thead tr th.vatField, .invoiceItemsTable tbody tr td.vatField").removeClass("hidden");
            $(".calculation .vatTotalField").removeClass("hidden");
        }

        updateInvoiceTotals();
    });
})();