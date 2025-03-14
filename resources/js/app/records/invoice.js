(function () {
    "use strict";

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));
    const addInvoiceModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#add-invoice-modal"));
    const editInvoiceModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#edit-invoice-modal"));
    const prePaymentInvoiceModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#pre-payment-invoice-modal"));
    const discountInvoiceModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#discount-invoice-modal"));
    
    document.getElementById('successModal').addEventListener('hide.tw.modal', function(event) {
        $('#successModal .agreeWith').attr('data-action', 'NONE').attr('data-redirect', '');
    });

    $('#successModal .agreeWith').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        if($theBtn.attr('data-action') == 'RELOAD'){
            if($theBtn.attr('data-redirect') != ''){
                window.location.href = $theBtn.attr('data-redirect');
            }else{
                window.location.reload();
            }
        }else{
            successModal.hide();
        }
    });

    /* Init Variables */
    let isNonVatCheck = $("#nonVatInvoiceCheck").is(":checked");
    let rowCounter = 2;
    let invoiceItems = [];
    let prePaymentDetails = {};
    let discountAmountDetails = {};

    function initInvoiceItems() {
        invoiceItems = [];

        $(".invoiceItemsTable tbody tr").each(function () {
            const description = $(this).find("td.description").text().trim();
            const units = parseFloat($(this).find("td.units").text().trim()) || 0;
            const price = parseFloat($(this).find("td .price").text().trim().replace("£", "")) || 0;
            const vat = parseFloat($(this).find("td .vat").text().trim().replace("£", "")) || 0;

            invoiceItems.push({
                description: description,
                units: units,
                price: price,
                vat: vat
            });

            if (description === "Discount") {
                discountAmountDetails = {
                    discount_amount: price,
                    discount_vat_rate: vat
                };
            }
        });
    }

    initInvoiceItems();


    function updateInvoiceTotals() {
        let subtotal = 0;
        let vatTotal = 0;
        let grandTotal = 0;
        let discountTotal = 0;
        let duePrice = 0;
    
        $(".invoiceItemsTable tbody tr").each(function () {
            let units = parseFloat($(this).find(".units").text().trim()) || 0;
            let price = parseFloat($(this).find(".price").text().trim().replace("$", "")) || 0;
            let isDiscount = $(this).find("td.description .whitespace-nowrap").text().trim() === "Discount";
    
            let lineTotal = units * price;
            let vat = 0;
    
            if (!isNonVatCheck) {
                let vatPercentage = parseFloat($(this).find(".vat").text().trim().replace("$", "")) || 0;
                vat = (lineTotal * vatPercentage) / 100;
            }
    
            if (isDiscount) {
                discountTotal += lineTotal;
                grandTotal -= (lineTotal + vat);
            } else {
                subtotal += lineTotal;
                vatTotal += vat;
                grandTotal += (lineTotal + vat);
            }

            if(prePaymentDetails.pre_payment_amount){
                duePrice =  grandTotal - prePaymentDetails.pre_payment_amount
            }else{
                duePrice = grandTotal
            }
            
        });
    
        $(".subtotal_price").text(subtotal.toFixed(2));
        $(".vat_total_price").text(vatTotal.toFixed(2));
        $(".total_price").text(grandTotal.toFixed(2));
        $(".due_price").text(duePrice.toFixed(2));
    }

    function sortInvoiceTableRows() {
        const $tbody = $(".invoiceItemsTable tbody");
        const rows = $tbody.find("tr").get();
        
        const discountRows = rows.filter(row => 
            $(row).find("td.description .whitespace-nowrap").text().trim() === "Discount"
        );
        const regularRows = rows.filter(row => 
            $(row).find("td.description .whitespace-nowrap").text().trim() !== "Discount"
        );
        
        $tbody.empty();
        regularRows.forEach(row => $tbody.append(row));
        discountRows.forEach(row => $tbody.append(row));
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
        const description = row.find("td.description").text().trim();
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

        let errors = [];
    
        if (!description) {
            errors.push("Description is required");
        } else if (description.length > 200) {
            errors.push("Description must not exceed 200 characters");
        }
    
        if (!units) {
            errors.push("Units are required");
        } else if (isNaN(units)) {
            errors.push("Units must be a number");
        } else {
            const unitsNum = parseFloat(units);
            if (unitsNum <= 0) {
                errors.push("Units must be greater than 0");
            }
            if (!Number.isInteger(unitsNum)) {
                errors.push("Units must be a whole number");
            }
            if (unitsNum > 10000) {
                errors.push("Units cannot exceed 10,000");
            }
        }
    
        if (!price) {
            errors.push("Price is required");
        } else if (isNaN(price)) {
            errors.push("Price must be a number");
        } else {
            const priceNum = parseFloat(price);
            if (priceNum <= 0) {
                errors.push("Price must be greater than 0");
            }
            if (priceNum > 1000000) {
                errors.push("Price cannot exceed $1,000,000");
            }
            if (!/^\d+(\.\d{1,2})?$/.test(price)) {
                errors.push("Price must have maximum 2 decimal places");
            }
        }
    
        if (!isNonVatCheck) {
            if (!vat) {
                errors.push("VAT is required");
            } else if (isNaN(vat)) {
                errors.push("VAT must be a number");
            } else {
                const vatNum = parseFloat(vat);
                if (vatNum < 0) {
                    errors.push("VAT cannot be negative");
                }
                if (vatNum > 100000) {
                    errors.push("VAT cannot exceed $100,000");
                }
                if (!/^\d+(\.\d{1,2})?$/.test(vat)) {
                    errors.push("VAT must have maximum 2 decimal places");
                }
            }
        }
    
        if (errors.length > 0) {
            alert("Please fix the following errors:\n- " + errors.join("\n- "));
            return;
        }
    
        const unitsNum = parseFloat(units);
        const priceNum = parseFloat(price);
        const vatNum = parseFloat(vat);
        
        const totalPrice = (unitsNum * priceNum).toFixed(2);
        const grandTotal = (parseFloat(totalPrice) + (isNonVatCheck ? 0 : vatNum)).toFixed(2);
    
        let vatColumn = "";
        if (!isNonVatCheck) {
            vatColumn = `
                <td class="px-5 py-3 vatField w-32 border-b text-right font-medium dark:border-darkmode-400">
                    <span class="currency">$</span> <span class="vat">${vatNum.toFixed(2)}</span>
                </td>
            `;
        } else {
            vatColumn = `
                <td class="hidden px-5 py-3 vatField w-32 border-b text-right font-medium dark:border-darkmode-400">
                    <span class="currency">$</span> <span class="vat">${vatNum.toFixed(2)}</span>
                </td>
            `;
        }
        
        const newRow = `
            <tr class="editInvoiceModal" data-id="${rowCounter}">
                <td class="description px-5 py-3 border-b dark:border-darkmode-400 flex gap-2">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="grip-vertical" class="lucide lucide-grip-vertical stroke-1.5 w-5 h-5 mx-auto block"><circle cx="9" cy="12" r="1"></circle><circle cx="9" cy="5" r="1"></circle><circle cx="9" cy="19" r="1"></circle><circle cx="15" cy="12" r="1"></circle><circle cx="15" cy="5" r="1"></circle><circle cx="15" cy="19" r="1"></circle></svg>
                    </div>
                    <div class="font-medium">${description}</div>
                </td>
                <td class="units px-5 py-3 w-32 border-b text-right dark:border-darkmode-400">
                    ${unitsNum}
                </td>
                <td class="px-5 py-3 w-32 border-b text-right dark:border-darkmode-400">
                    <span class="currency">$</span><span class="price">${priceNum.toFixed(2)}</span>
                </td>
                ${vatColumn}
                <td class="px-5 py-3 w-32 border-b text-right font-medium dark:border-darkmode-400">
                    <span class="currency">$</span><span class="lineTotal">${grandTotal}</span>
                </td>
            </tr>
        `;
    
        $(".invoiceItemsTable tbody").append(newRow);
    
        rowCounter++;
    
        $("#add-invoice-modal textarea[name='add_description']").val("");
        $("#add-invoice-modal input[name='add_units']").val("");
        $("#add-invoice-modal input[name='add_price']").val("");
        $("#add-invoice-modal input[name='add_vat']").val("");
    
        sortInvoiceTableRows();
        updateInvoiceTotals();
        initInvoiceItems();
        addInvoiceModal.hide();
    });

    $("#edit-invoice-modal .updateInvoiceItemBtn").on("click", function () {
        const description = $("#edit-invoice-modal textarea[name='edit_description']").val().trim();
        const units = $("#edit-invoice-modal input[name='edit_units']").val().trim();
        const price = $("#edit-invoice-modal input[name='edit_price']").val().trim();
        let vat = $("#edit-invoice-modal input[name='edit_vat']").val().trim();
    
        const errors = [];

        if (!description) {
            errors.push("Description is required.");
        } else if (description.length > 500) {
            errors.push("Description cannot exceed 500 characters.");
        }

        if (!units) {
            errors.push("Units are required.");
        } else if (isNaN(units)) {
            errors.push("Units must be a numeric value.");
        } else {
            const unitsNum = parseFloat(units);
            if (unitsNum <= 0) {
                errors.push("Units must be greater than 0.");
            }
            if (!Number.isInteger(unitsNum)) {
                errors.push("Units must be a whole number.");
            }
            if (unitsNum > 10000) {
                errors.push("Units cannot exceed 10,000.");
            }
        }

        if (!price) {

            errors.push("Price is required.");
        } else if (isNaN(price)) {

            errors.push("Price must be a numeric value.");
        } else {
            const priceNum = parseFloat(price);
            if (priceNum <= 0) {
    
                errors.push("Price must be greater than 0.");
            }
            if (priceNum > 1000000) {
    
                errors.push("Price cannot exceed 1,000,000.");
            }
        }

        if (!isNonVatCheck) {
            if (!vat) {
                vat = "0";
            } else if (isNaN(vat)) {
    
                errors.push("VAT must be a numeric value.");
            } else {
                const vatNum = parseFloat(vat);
                if (vatNum < 0) {
        
                    errors.push("VAT cannot be negative.");
                }
                if (vatNum > 100) {
        
                    errors.push("VAT cannot exceed 100%.");
                }
            }
        }

        if (errors.length > 0) {
            alert("Please fix the following errors:\n- " + errors.join("\n- "));
            return;
        }

        const unitsNum = parseFloat(units);
        const priceNum = parseFloat(price);
        const vatNum = isNonVatCheck ? 0 : parseFloat(vat);
        
        const totalPrice = (unitsNum * priceNum).toFixed(2);
        const grandTotal = (parseFloat(totalPrice) + vatNum).toFixed(2);
    
        const dataId = $("#edit-invoice-modal").attr("data-edit-id");
        const row = $(".invoiceItemsTable tbody tr.editInvoiceModal[data-id='" + dataId + "']");
    
        if (isNonVatCheck) {
            vat = row.find("td .vat").text();
        }

        row.find("td.description").html(`
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="grip-vertical" class="lucide lucide-grip-vertical stroke-1.5 w-5 h-5 mx-auto block"><circle cx="9" cy="12" r="1"></circle><circle cx="9" cy="5" r="1"></circle><circle cx="9" cy="19" r="1"></circle><circle cx="15" cy="12" r="1"></circle><circle cx="15" cy="5" r="1"></circle><circle cx="15" cy="19" r="1"></circle></svg>
            </div>
            <div class="font-medium">${description}</div>
        `);
    
        row.find("td.units").text(unitsNum);
        row.find("td .price").text(priceNum.toFixed(2));
        row.find("td .vat").text(vatNum.toFixed(2));
        row.find("td .lineTotal").text(grandTotal);
    
        updateInvoiceTotals();
        initInvoiceItems();
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
            $(".vatNumberField").addClass("hidden");
        } else {
            $(".invoiceItemsTable thead tr th.vatField, .invoiceItemsTable tbody tr td.vatField").removeClass("hidden");
            $(".calculation .vatTotalField").removeClass("hidden");
            $(".vatNumberField").removeClass("hidden");
        }

        updateInvoiceTotals();
    });

    $("#addPrepaymentBtn").on("click", function () {
        if (prePaymentDetails.pre_payment_amount) {
            $("#pre_payment_amount").val(prePaymentDetails.pre_payment_amount);
        }
        if (prePaymentDetails.pre_payment_method) {
            $("#pre_payment_method").val(prePaymentDetails.pre_payment_method);
        }
        if (prePaymentDetails.pre_payment_date) {
            $("#pre_payment_date").val(prePaymentDetails.pre_payment_date);
        }

        prePaymentInvoiceModal.show();
    });

    $(".prePaymentInvoiceModalHide").on("click", function () {
        prePaymentInvoiceModal.hide();
    });

    $(".prePaymentModalRecordBtn").on("click", function () {
        const pre_payment_amount = parseFloat($("#pre_payment_amount").val()) || 0;
        const pre_payment_method = $("#pre_payment_method").val();
        const pre_payment_date = $("#pre_payment_date").val();

        prePaymentDetails = {
            pre_payment_amount: pre_payment_amount,
            pre_payment_method: pre_payment_method,
            pre_payment_date: pre_payment_date
        };

        $(".paid_to_date").text(prePaymentDetails.pre_payment_amount.toFixed(2));
        $(".paidToDateField").removeClass('hidden')

        updateInvoiceTotals();

        prePaymentInvoiceModal.hide();
    });


    function hasDiscountRow() {
        return $(".invoiceItemsTable tbody tr").filter(function() {
            return $(this).find("td.description").text().trim() === "Discount";
        }).length > 0;
    }


    $("#addDiscountBtn").on("click", function () {
        if (isNonVatCheck) {
            $("#discount-invoice-modal .discountVatField").addClass("hidden");
        } else {
            $("#discount-invoice-modal .discountVatField").removeClass("hidden");
        }

        if (hasDiscountRow()) {
            $("#discount-invoice-modal .discountModalRecordBtn").text("Record Update")
            const $existingDiscount = $(".invoiceItemsTable tbody tr").filter(function() {
                return $(this).find("td.description").text().trim() === "Discount";
            });
            $("#discount-invoice-modal input[name='discount_amount']").val($existingDiscount.find("td .price").text());
            if (!isNonVatCheck) {
                $("#discount-invoice-modal input[name='discount_vat_rate']").val($existingDiscount.find("td .vat").text());
            }
        }


        discountInvoiceModal.show();
    });

    $(".discountInvoiceModalHide").on("click", function () {
        discountInvoiceModal.hide();
    });

    $("#discount-invoice-modal .discountModalRecordBtn").on("click", function () {
        const discount_amount = $("#discount-invoice-modal input[name='discount_amount']").val().trim();
        const discount_vat_rate = isNonVatCheck ? "0" : $("#discount-invoice-modal input[name='discount_vat_rate']").val().trim();
        
        let totalBeforeDiscount = 0;
        $(".invoiceItemsTable tbody tr").each(function () {
            let isDiscount = $(this).find("td.description .whitespace-nowrap").text().trim() === "Discount";
            if (!isDiscount) {
                let units = parseFloat($(this).find(".units").text().trim()) || 0;
                let price = parseFloat($(this).find(".price").text().trim().replace("$", "")) || 0;
                let lineTotal = units * price;
                let vat = 0;
                if (!isNonVatCheck) {
                    vat = parseFloat($(this).find(".vat").text().trim().replace("$", "")) || 0;
                }
                totalBeforeDiscount += (lineTotal + vat);
            }
        });
    
        if (!discount_amount || !discount_vat_rate) {
            alert("Discount Amount and VAT rate fields are required.");
            return;
        }
        
        if (isNaN(discount_amount) || isNaN(discount_vat_rate)) {
            alert("Discount amount and VAT rate must be numeric values.");
            return;
        }
    
        const discountAmountNum = parseFloat(discount_amount);
        const discountVatNum = parseFloat(discount_vat_rate);
        const discountTotal = discountAmountNum + (isNonVatCheck ? 0 : discountVatNum);
    
        if (discountTotal > totalBeforeDiscount) {
            alert("Discount amount cannot exceed the total invoice amount of $" + totalBeforeDiscount.toFixed(2));
            return;
        }
    
        if (discountAmountNum < 0) {
            alert("Discount amount cannot be negative.");
            return;
        }
    
        discountAmountDetails = {
            discount_amount: discountAmountNum,
            discount_vat_rate: discountVatNum
        };
    
        if(hasDiscountRow()){
            const $discountRow = $(".invoiceItemsTable tbody tr").filter(function() {
                return $(this).find("td.description .whitespace-nowrap").text().trim() === "Discount";
            });
            
            $discountRow.find("td .price").text(discount_amount);
            if (!isNonVatCheck) {
                $discountRow.find("td .vat").text(discount_vat_rate);
            }
            $discountRow.find("td .lineTotal").text(discount_amount);
            
            updateInvoiceTotals();
            initInvoiceItems();
        } else {
            let vatColumn = "";
            if (!isNonVatCheck) {
                vatColumn = `
                    <td class="px-5 py-3 vatField w-32 border-b text-right font-medium dark:border-darkmode-400">
                        <span class="currency">$</span> <span class="vat">${discount_vat_rate}</span>
                    </td>
                `;
            } else {
                vatColumn = `
                    <td class="hidden px-5 py-3 vatField w-32 border-b text-right font-medium dark:border-darkmode-400">
                        <span class="currency">$</span> <span class="vat">${discount_vat_rate}</span>
                    </td>
                `;
            }
            
            const newRow = `
                <tr class="discount-row" data-id="${rowCounter}">
                    <td class="description px-5 py-3 border-b dark:border-darkmode-400 flex gap-2 discount">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="grip-vertical" class="lucide lucide-grip-vertical stroke-1.5 w-5 h-5 mx-auto block"><circle cx="9" cy="12" r="1"></circle><circle cx="9" cy="5" r="1"></circle><circle cx="9" cy="19" r="1"></circle><circle cx="15" cy="12" r="1"></circle><circle cx="15" cy="5" r="1"></circle><circle cx="15" cy="19" r="1"></circle></svg>
                        </div>
                        <div class="whitespace-nowrap font-medium">Discount</div>
                    </td>
                    <td class="units px-5 py-3 w-32 border-b text-right dark:border-darkmode-400">
                        1
                    </td>
                    <td class="px-5 py-3 w-32 border-b text-right dark:border-darkmode-400">
                        <span class="currency">$</span><span class="price">${discount_amount}</span>
                    </td>
                    ${vatColumn}
                    <td class="px-5 py-3 w-32 border-b text-right font-medium dark:border-darkmode-400">
                        <span class="currency">- $</span><span class="lineTotal">${discount_amount}</span>
                    </td>
                </tr>
            `;
    
            $(".invoiceItemsTable tbody").append(newRow);
            rowCounter++;
            $("#discount-invoice-modal input[name='discount_amount']").val("");
            $("#discount-invoice-modal input[name='discount_vat_rate']").val("");
        }
    
        sortInvoiceTableRows();
        updateInvoiceTotals();
        initInvoiceItems();
        discountInvoiceModal.hide();
    });

    $(document).on("click", ".discount-row", function () {
        const row = $(this);
        const discount_amount = row.find("td .price").text().trim();
        const discount_vat_rate = row.find("td .vat").text().trim();
    
        if (isNonVatCheck) {
            $("#discount-invoice-modal .discountVatField").addClass("hidden");
        } else {
            $("#discount-invoice-modal .discountVatField").removeClass("hidden");
        }
    
        $("#discount-invoice-modal input[name='discount_amount']").val(discount_amount);
        if (!isNonVatCheck) {
            $("#discount-invoice-modal input[name='discount_vat_rate']").val(discount_vat_rate);
        }
    
        $("#discount-invoice-modal .discountModalRecordBtn").text("Record Update");
    
        $("#discount-invoice-modal").data("discount-row", row);
    
        discountInvoiceModal.show();
    });


    $(".deleteInvoiceItemBtn").on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const dataId = $("#edit-invoice-modal").attr("data-edit-id");
        const row = $(".invoiceItemsTable tbody tr.editInvoiceModal[data-id='" + dataId + "']"); 
        row.remove();
        editInvoiceModal.hide();
        $("#edit-invoice-modal").removeAttr("data-edit-id");
        
        updateInvoiceTotals();
        initInvoiceItems();
    });


    $(".approveAndEmailBtn").on("click", function(){
        const $btn = $(this);
        const $icon = $btn.find('svg').prop('outerHTML');
        const originalText = $btn.html();
        $btn.prop('disabled', true).html(`${$icon} Processing...`);

        const data = {
            date_issued: $("#date_issued").val(),
            jobRefNo: $("#job_ref_no").val(),
            invoiceItems: invoiceItems,
            isNonVatCheck: isNonVatCheck,
            customer_job_id : parseInt($("#customer_job_id").val())
        };

        axios({
            method: "post",
            url: route('invoice.approve.and.send.email'),
            data: data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            // console.log(response.data);
            window.open(response.data.pdf_path, '_blank');
        }).catch(error => {
            console.error(error);
        }).finally(() => {
            $btn.prop('disabled', false).html(originalText);
        });
    });


})();