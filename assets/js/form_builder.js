/* assets/js/form-builder.js */

// --- 1. STATE ---
let fields = [
    {
        id: 1,
        type: "input",
        label: "Full Name",
        inputType: "text",
        width: "w-50",
        required: true,
    },
    {
        id: 2,
        type: "input",
        label: "Email Address",
        inputType: "email",
        width: "w-50",
        required: true,
    },
    {
        id: 3,
        type: "textarea",
        label: "Message",
        width: "w-100",
        required: false,
    },
    // isBlock: false = Auto width (allows alignment). isBlock: true = Full width button.
    {
        id: 4,
        type: "button",
        label: "Send Message",
        style: "btn-primary",
        width: "w-50",
        align: "align-end",
        isBlock: false,
    },
    {
        id: 5,
        type: "button",
        label: "Reset",
        style: "btn-danger",
        width: "w-50",
        align: "align-start",
        isBlock: false,
    },
];

let currentTab = "html";
let generatedHtml = "";

// --- 2. RENDERERS ---
function renderUI() {
    const list = document.getElementById("componentList");
    const preview = document.getElementById("formRenderArea");

    list.innerHTML = "";

    // Inject Styles for Preview
    const styleBlock = `
    <style>
        .flex-row { display: flex; flex-wrap: wrap; margin: 0 -10px; }
        .col { padding: 0 10px; margin-bottom: 15px; box-sizing: border-box; }
        .w-100 { width: 100%; } .w-75 { width: 75%; } .w-66 { width: 66.66%; }
        .w-50 { width: 50%; } .w-33 { width: 33.33%; } .w-25 { width: 25%; } .w-auto { width: auto; flex-grow: 1; }
        
        .align-start { display: flex; justify-content: flex-start; width: 100%; }
        .align-center { display: flex; justify-content: center; width: 100%; }
        .align-end { display: flex; justify-content: flex-end; width: 100%; }
    </style>`;

    let formHtml =
        styleBlock +
        `<form id="generatedForm" method="POST">\n<div class="flex-row">`;

    fields.forEach((field, index) => {
        // A. PROPERTIES PANEL
        const item = document.createElement("div");
        item.className = `field-item ${
            field.type === "button" ? "is-btn" : ""
        }`;

        let controls = `
            <div class="field-header">
                <span>${field.type.toUpperCase()}</span>
                <div style="display:flex; gap:5px;">
                    <i class="ri-arrow-up-s-line" style="cursor:pointer" onclick="moveField(${index}, -1)"></i>
                    <i class="ri-arrow-down-s-line" style="cursor:pointer" onclick="moveField(${index}, 1)"></i>
                    <i class="ri-delete-bin-line" style="cursor:pointer; color:#ef4444;" onclick="removeField(${index})"></i>
                </div>
            </div>
            <input type="text" class="form-input-sm" value="${
                field.label
            }" oninput="updateField(${index}, 'label', this.value)" placeholder="Label">
        `;

        const widthOptions = `
            <option value="w-100" ${
                field.width === "w-100" ? "selected" : ""
            }>100%</option>
            <option value="w-75" ${
                field.width === "w-75" ? "selected" : ""
            }>75%</option>
            <option value="w-66" ${
                field.width === "w-66" ? "selected" : ""
            }>66%</option>
            <option value="w-50" ${
                field.width === "w-50" ? "selected" : ""
            }>50%</option>
            <option value="w-33" ${
                field.width === "w-33" ? "selected" : ""
            }>33%</option>
            <option value="w-25" ${
                field.width === "w-25" ? "selected" : ""
            }>25%</option>
            <option value="w-auto" ${
                field.width === "w-auto" ? "selected" : ""
            }>Auto</option>
        `;

        if (field.type === "input") {
            controls += `
            <div class="controls-grid">
                <select class="form-select-sm" onchange="updateField(${index}, 'inputType', this.value)">
                    <option value="text" ${
                        field.inputType === "text" ? "selected" : ""
                    }>Text</option>
                    <option value="email" ${
                        field.inputType === "email" ? "selected" : ""
                    }>Email</option>
                    <option value="password" ${
                        field.inputType === "password" ? "selected" : ""
                    }>Password</option>
                    <option value="number" ${
                        field.inputType === "number" ? "selected" : ""
                    }>Number</option>
                    <option value="date" ${
                        field.inputType === "date" ? "selected" : ""
                    }>Date</option>
                </select>
                <select class="form-select-sm" onchange="updateField(${index}, 'width', this.value)">
                    ${widthOptions}
                </select>
            </div>
            <label style="font-size:11px; display:flex; gap:5px; align-items:center;">
                <input type="checkbox" ${
                    field.required ? "checked" : ""
                } onchange="updateField(${index}, 'required', this.checked)"> Required
            </label>`;
        } else if (field.type === "textarea" || field.type === "select") {
            controls += `
            <div class="controls-grid">
                <div style="font-size:11px; color:#94a3b8; align-self:center;">${field.type}</div>
                <select class="form-select-sm" onchange="updateField(${index}, 'width', this.value)">
                    ${widthOptions}
                </select>
            </div>`;
        } else if (field.type === "button") {
            controls += `
            <div class="controls-grid">
                <select class="form-select-sm" onchange="updateField(${index}, 'style', this.value)">
                    <option value="btn-primary" ${
                        field.style === "btn-primary" ? "selected" : ""
                    }>Primary</option>
                    <option value="btn-secondary" ${
                        field.style === "btn-secondary" ? "selected" : ""
                    }>Secondary</option>
                    <option value="btn-success" ${
                        field.style === "btn-success" ? "selected" : ""
                    }>Success</option>
                    <option value="btn-danger" ${
                        field.style === "btn-danger" ? "selected" : ""
                    }>Danger</option>
                    <option value="btn-outline" ${
                        field.style === "btn-outline" ? "selected" : ""
                    }>Outline</option>
                </select>
                <select class="form-select-sm" onchange="updateField(${index}, 'width', this.value)">
                    ${widthOptions}
                </select>
            </div>
            
            <div class="controls-grid">
                 <select class="form-select-sm" onchange="updateField(${index}, 'align', this.value)">
                    <option value="align-start" ${
                        field.align === "align-start" ? "selected" : ""
                    }>Align Left</option>
                    <option value="align-center" ${
                        field.align === "align-center" ? "selected" : ""
                    }>Align Center</option>
                    <option value="align-end" ${
                        field.align === "align-end" ? "selected" : ""
                    }>Align Right</option>
                </select>
                
                <label style="font-size:11px; display:flex; gap:5px; align-items:center;">
                    <input type="checkbox" ${
                        field.isBlock ? "checked" : ""
                    } onchange="updateField(${index}, 'isBlock', this.checked)"> Fill Width
                </label>
            </div>`;
        }

        item.innerHTML = controls;
        list.appendChild(item);

        // B. RENDER PREVIEW HTML
        let nameAttr = field.label.toLowerCase().replace(/[^a-z0-9]/g, "_");
        let elHtml = "";

        // FIX: 'width' controls the Column Size.
        // 'isBlock' controls if button fills that column.
        let colWidth = field.width;

        elHtml += `    <div class="col ${colWidth}">\n`;

        if (field.type === "input") {
            elHtml += `        <div class="form-group">
            <label class="form-label">${field.label}${
                field.required ? ' <span style="color:red">*</span>' : ""
            }</label>
            <input type="${
                field.inputType
            }" name="${nameAttr}" class="form-control" placeholder="${
                field.label
            }" ${field.required ? "required" : ""}>
        </div>\n`;
        } else if (field.type === "textarea") {
            elHtml += `        <div class="form-group">
            <label class="form-label">${field.label}</label>
            <textarea name="${nameAttr}" class="form-control" rows="3"></textarea>
        </div>\n`;
        } else if (field.type === "select") {
            elHtml += `        <div class="form-group">
            <label class="form-label">${field.label}</label>
            <select name="${nameAttr}" class="form-control">
                <option value="">Select option...</option>
                <option value="1">Option 1</option>
                <option value="2">Option 2</option>
            </select>
        </div>\n`;
        } else if (field.type === "button") {
            // Button width class is either w-100 (if Block checked) or w-auto (default)
            let btnClass = field.isBlock ? "w-100" : "w-auto";

            elHtml += `        <div class="${field.align}">
            <button type="submit" class="btn ${field.style} ${btnClass}">${field.label}</button>
        </div>\n`;
        }

        elHtml += `    </div>`;
        formHtml += elHtml + `\n`;
    });

    formHtml += `</div>\n</form>`;
    preview.innerHTML = formHtml;

    generatedHtml = formHtml.replace(styleBlock, "");
}

// --- 3. CONTROLLERS ---
function addField(type) {
    let newField = {
        id: Date.now(),
        type: type,
        label: "New Field",
        width: "w-100",
    };
    if (type === "input") {
        newField.inputType = "text";
        newField.required = true;
    }
    if (type === "button") {
        newField.style = "btn-primary";
        newField.label = "Submit";
        newField.align = "align-start";
        newField.width = "w-100"; // Default column width
        newField.isBlock = false; // Default button width
    }
    fields.push(newField);
    renderUI();
}

function removeField(i) {
    fields.splice(i, 1);
    renderUI();
}

function moveField(i, dir) {
    let n = i + dir;
    if (n >= 0 && n < fields.length) {
        [fields[i], fields[n]] = [fields[n], fields[i]];
        renderUI();
    }
}

function updateField(i, k, v) {
    fields[i][k] = v;
    renderUI();
}

function resetForm() {
    fields = [];
    renderUI();
}

// --- 4. CODE GENERATORS ---
function generateJS() {
    return `
<script src="https://code.jquery.com/jquery-3.6.0.min.js"><\/script>

<script>
$(document).ready(function() {
    $('#generatedForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        var btn = $(this).find('button[type="submit"]');
        var originalText = btn.text();

        btn.text('Processing...').prop('disabled', true);

        $.ajax({
            url: 'process_form.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    alert('Success: ' + response.message);
                    $('#generatedForm')[0].reset(); 
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert('Server Error');
            },
            complete: function() {
                btn.text(originalText).prop('disabled', false);
            }
        });
    });
});
<\/script>`;
}

function generatePHP() {
    let phpArrayLines = [];
    let inputs = fields.filter(
        (f) =>
            f.type === "input" || f.type === "textarea" || f.type === "select"
    );

    inputs.forEach((f) => {
        let name = f.label.toLowerCase().replace(/[^a-z0-9]/g, "_");
        let line = `    '${name}' => ['label' => '${f.label}', 'type' => '${
            f.inputType || "text"
        }', 'required' => ${f.required ? "true" : "false"}]`;
        phpArrayLines.push(line);
    });

    let fieldsArrayCode = phpArrayLines.join(",\n");

    return `<?php
// process_form.php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid Request']);
    exit;
}

// 1. Validation Rules
$formFields = [
${fieldsArrayCode}
];

// 2. Loop Validation
$errors = [];

foreach ($formFields as $key => $config) {
    $value = trim($_POST[$key] ?? '');
    
    // Check Required
    if ($config['required'] && empty($value)) {
        $errors[] = $config['label'] . " is required.";
        continue;
    }

    // Check Email Format
    if (!empty($value) && $config['type'] === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
        $errors[] = $config['label'] . " format is invalid.";
    }
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode('\\n', $errors)]);
    exit;
}

// 3. Success
echo json_encode(['success' => true, 'message' => 'Form processed successfully!']);
?>`;
}

function switchTab(tab) {
    currentTab = tab;
    document
        .querySelectorAll(".code-tab")
        .forEach((t) => t.classList.remove("active"));
    event.target.classList.add("active");
    updateCodeView();
}

function updateCodeView() {
    const area = document.getElementById("codeContent");
    if (currentTab === "html") area.value = generatedHtml;
    if (currentTab === "js") area.value = generateJS();
    if (currentTab === "php") area.value = generatePHP();
}

function toggleCode(show) {
    document.getElementById("codeModal").style.display = show ? "flex" : "none";
    if (show) updateCodeView();
}

// Init
renderUI();
