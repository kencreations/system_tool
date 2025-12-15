<?php $pageTitle = "Kanban Builder"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body { overflow: hidden; height: 100vh; display: flex; }
        .workspace { flex-grow: 1; background: #2563eb; /* Trello Blue style */ display: flex; flex-direction: column; }
        .preview-area { flex-grow: 1; overflow-x: auto; padding: 40px; }
        
        /* KANBAN CSS */
        .kanban-board { display: flex; gap: 20px; align-items: flex-start; height: 100%; }
        .kanban-column {
            background: #f1f5f9; width: 280px; flex-shrink: 0; border-radius: 12px;
            display: flex; flex-direction: column; max-height: 100%;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        .column-header { padding: 15px; font-weight: 700; color: #334155; display: flex; justify-content: space-between; }
        .column-body { padding: 0 10px 10px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; }
        
        .kanban-card {
            background: #fff; padding: 12px; border-radius: 8px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05); cursor: grab; border: 1px solid #e2e8f0;
        }
        .kanban-card:hover { box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .card-tags { display: flex; gap: 5px; margin-bottom: 8px; }
        .card-tag { font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: 700; text-transform: uppercase; }
        .tag-red { background: #fee2e2; color: #ef4444; }
        .tag-blue { background: #dbeafe; color: #3b82f6; }
        .tag-green { background: #dcfce7; color: #166534; }
        
        .card-title { font-size: 14px; font-weight: 500; color: #1e293b; margin-bottom: 5px; }
        .card-meta { display: flex; justify-content: space-between; align-items: center; margin-top: 10px; font-size: 12px; color: #94a3b8; }
        .user-avatar { width: 24px; height: 24px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold; color: #64748b; }

        /* PROPERTIES PANEL */
        .properties-panel { width: 320px; background: #fff; border-left: 1px solid #cbd5e1; padding: 20px; }
        .btn-add { background: #eff6ff; color: var(--primary); border: 1px solid #bfdbfe; width: 100%; padding: 8px; border-radius: 6px; cursor: pointer; }
    </style>
</head>
<body>

    <?php include 'components/sidebar.php'; ?>

    <main class="workspace">
        <div style="height:60px; background:rgba(255,255,255,0.1); display:flex; align-items:center; padding:0 20px; color:#fff; font-weight:bold;">
            Project Board
        </div>
        <div class="preview-area">
            <div class="kanban-board" id="renderArea">
                </div>
        </div>
    </main>

    <aside class="properties-panel">
        <h3>Kanban Config</h3>
        <p style="font-size:13px; color:#64748b; margin-bottom:20px;">Add columns to structure your workflow.</p>
        
        <div id="columnList"></div>
        <button class="btn-add" onclick="addColumn()">+ Add Column</button>
        
        <div style="margin-top:20px;">
            <button class="btn btn-outline w-100" onclick="alert('Copy the HTML inside .kanban-board')">Get HTML</button>
        </div>
    </aside>

    <script>
        let columns = [
            { title: 'To Do', cards: 2 },
            { title: 'In Progress', cards: 1 },
            { title: 'Done', cards: 3 }
        ];

        function render() {
            const area = document.getElementById('renderArea');
            const list = document.getElementById('columnList');
            
            // Render Board
            let html = '';
            columns.forEach(col => {
                let cardsHtml = '';
                for(let i=0; i<col.cards; i++) {
                    cardsHtml += `
                    <div class="kanban-card">
                        <div class="card-tags">
                            <span class="card-tag tag-blue">Design</span>
                        </div>
                        <div class="card-title">Task Item #${i+1}</div>
                        <div class="card-meta">
                            <span><i class="ri-chat-3-line"></i> 2</span>
                            <div class="user-avatar">JD</div>
                        </div>
                    </div>`;
                }

                html += `
                <div class="kanban-column">
                    <div class="column-header">
                        <span>${col.title}</span>
                        <i class="ri-more-fill" style="cursor:pointer; color:#94a3b8;"></i>
                    </div>
                    <div class="column-body">
                        ${cardsHtml}
                        <div style="padding:8px; color:#64748b; font-size:13px; cursor:pointer; border-radius:6px; hover:background:#e2e8f0;">+ Add Card</div>
                    </div>
                </div>`;
            });
            area.innerHTML = html;

            // Render Sidebar Controls
            let controls = '';
            columns.forEach((col, i) => {
                controls += `
                <div style="display:flex; gap:5px; margin-bottom:10px;">
                    <input type="text" class="form-input" style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:4px;" value="${col.title}" oninput="updateCol(${i}, this.value)">
                    <button onclick="removeCol(${i})" style="border:none; background:#fee2e2; color:#ef4444; border-radius:4px; width:30px; cursor:pointer;"><i class="ri-delete-bin-line"></i></button>
                </div>`;
            });
            list.innerHTML = controls;
        }

        function addColumn() { columns.push({title:'New Phase', cards:0}); render(); }
        function removeCol(i) { columns.splice(i,1); render(); }
        function updateCol(i, val) { columns[i].title = val; render(); }

        render();
    </script>
</body>
</html>