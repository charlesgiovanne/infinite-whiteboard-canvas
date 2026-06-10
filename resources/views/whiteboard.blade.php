@extends('layouts.app')

@section('content')
<div id="whiteboard-root" class="flex flex-col" style="height:100vh; overflow:hidden; position:relative; background:#f0f2f5;">

    <!-- ═══════════════════════════════════════ TOP BAR ═══════════════════════════════════════ -->
    <header id="top-bar"
        style="position:absolute; top:16px; left:50%; transform:translateX(-50%); z-index:100;
               background:rgba(255,255,255,0.85); backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px);
               border:1px solid rgba(0,0,0,0.08); border-radius:16px; box-shadow:0 4px 24px rgba(0,0,0,0.10);
               display:flex; align-items:center; gap:8px; padding:8px 16px;">

        <!-- Back to boards list -->
        <a href="{{ route('boards.index') }}" title="All Boards"
           style="display:flex;align-items:center;gap:6px;padding:6px 12px;border-radius:10px;color:#475569;font-size:13px;font-weight:600;text-decoration:none;transition:background 0.2s;"
           onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
            <i data-lucide="layout-grid" style="width:16px;height:16px;"></i>
            Boards
        </a>

        <div style="width:1px;height:20px;background:#e2e8f0;"></div>

        <!-- Board name display -->
        <div style="display:flex;align-items:center;gap:8px;">
            <i data-lucide="layout" style="width:16px;height:16px;color:#6366f1;"></i>
            <span id="board-name-display" style="font-size:14px;font-weight:700;color:#1e293b;font-family:'Outfit',sans-serif;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $board->name }}</span>
        </div>

        <div style="width:1px;height:20px;background:#e2e8f0;"></div>

        <!-- Save Status -->
        <div id="save-status" style="display:flex;align-items:center;gap:6px;color:#64748b;font-size:12px;font-weight:500;">
            <span id="save-status-icon"><i data-lucide="cloud" style="width:14px;height:14px;color:#94a3b8;"></i></span>
            <span id="save-status-text">All changes saved</span>
        </div>

        <div style="width:1px;height:20px;background:#e2e8f0;"></div>

        <!-- Undo / Redo -->
        <button id="undo-btn" onclick="undoAction()" title="Undo (Ctrl+Z)"
            style="width:32px;height:32px;border:none;background:transparent;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#475569;transition:background 0.15s;"
            onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
            <i data-lucide="undo-2" style="width:15px;height:15px;"></i>
        </button>
        <button id="redo-btn" onclick="redoAction()" title="Redo (Ctrl+Y)"
            style="width:32px;height:32px;border:none;background:transparent;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#475569;transition:background 0.15s;"
            onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
            <i data-lucide="redo-2" style="width:15px;height:15px;"></i>
        </button>

        <div style="width:1px;height:20px;background:#e2e8f0;"></div>

        <!-- Grid Toggle -->
        <button id="grid-btn" onclick="toggleGrid()" title="Toggle Grid"
            style="width:32px;height:32px;border:none;background:transparent;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#475569;transition:background 0.15s;"
            onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
            <i data-lucide="grid" style="width:15px;height:15px;"></i>
        </button>

        <div style="width:1px;height:20px;background:#e2e8f0;"></div>

        <!-- Export Dropdown -->
        <div style="position:relative;">
            <button id="export-btn" onclick="toggleExportMenu(event)" title="Export Canvas"
                style="display:flex;align-items:center;gap:5px;padding:6px 10px;border:none;background:transparent;border-radius:8px;cursor:pointer;color:#475569;font-size:12px;font-weight:600;transition:background 0.15s;font-family:'Inter',sans-serif;"
                onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                <i data-lucide="download" style="width:15px;height:15px;"></i>
                Export
            </button>
            <div id="export-menu" style="display:none;position:fixed;z-index:9500;background:rgba(255,255,255,0.98);backdrop-filter:blur(16px);border:1px solid rgba(0,0,0,0.10);border-radius:12px;box-shadow:0 8px 32px rgba(0,0,0,0.15);padding:6px;min-width:140px;">
                <button onclick="exportCanvas('png')" style="width:100%;display:flex;align-items:center;gap:8px;padding:8px 10px;border:none;background:transparent;border-radius:8px;cursor:pointer;font-size:13px;font-weight:500;color:#1e293b;text-align:left;transition:background 0.15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                    <i data-lucide="image" style="width:14px;height:14px;color:#6366f1;"></i> Save as PNG
                </button>
                <button onclick="exportCanvas('jpeg')" style="width:100%;display:flex;align-items:center;gap:8px;padding:8px 10px;border:none;background:transparent;border-radius:8px;cursor:pointer;font-size:13px;font-weight:500;color:#1e293b;text-align:left;transition:background 0.15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                    <i data-lucide="file-image" style="width:14px;height:14px;color:#f59e0b;"></i> Save as JPEG
                </button>
            </div>
        </div>

        <!-- Manual Save Button -->
        <button id="save-btn" onclick="saveBoard()"
            style="display:flex;align-items:center;gap:6px;padding:7px 14px;background:#6366f1;color:#fff;border:none;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;transition:all 0.2s;font-family:'Inter',sans-serif;"
            onmouseover="this.style.background='#4f46e5'" onmouseout="this.style.background='#6366f1'">
            <i data-lucide="save" style="width:14px;height:14px;"></i>
            Save
        </button>
    </header>

    <!-- ═══════════════════════════════════════ TOOLBAR (Left) ═══════════════════════════════════════ -->
    <div id="toolbar"
        style="position:absolute; top:50%; left:16px; transform:translateY(-50%); z-index:100;
               background:rgba(255,255,255,0.85); backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px);
               border:1px solid rgba(0,0,0,0.08); border-radius:16px; box-shadow:0 4px 24px rgba(0,0,0,0.10);
               display:flex; flex-direction:column; align-items:center; gap:4px; padding:10px 8px;">

        <!-- Tool buttons -->
        @php
        $tools = [
            ['id'=>'select',    'icon'=>'mouse-pointer-2',  'label'=>'Select (V)'],
            ['id'=>'draw',      'icon'=>'pen-line',         'label'=>'Draw (D)'],
            ['id'=>'eraser',    'icon'=>'eraser',           'label'=>'Eraser (X)'],
            ['id'=>'fill',      'icon'=>'paint-bucket',     'label'=>'Fill (F)'],
            ['id'=>'rect',      'icon'=>'square',           'label'=>'Rectangle (R)'],
            ['id'=>'ellipse',   'icon'=>'circle',           'label'=>'Ellipse (E)'],
            ['id'=>'line',      'icon'=>'minus',            'label'=>'Line (L)'],
            ['id'=>'arrow',     'icon'=>'move-right',       'label'=>'Arrow (A)'],
            ['id'=>'text',      'icon'=>'type',             'label'=>'Text (T)'],
        ];
        @endphp

        @foreach($tools as $tool)
        <button class="tool-btn" data-tool="{{ $tool['id'] }}" title="{{ $tool['label'] }}"
            style="width:40px;height:40px;border:none;background:transparent;border-radius:10px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#64748b;transition:all 0.15s;"
            onclick="setActiveTool('{{ $tool['id'] }}')">
            <i data-lucide="{{ $tool['icon'] }}" style="width:18px;height:18px;pointer-events:none;"></i>
        </button>
        @endforeach

        <div style="width:28px;height:1px;background:#e2e8f0;margin:4px 0;"></div>

        <!-- Color picker -->
        <div style="position:relative;" title="Stroke / Fill Color">
            <input type="color" id="stroke-color" value="#6366f1"
                style="width:32px;height:32px;padding:0;border:none;border-radius:8px;cursor:pointer;background:transparent;">
        </div>

        <!-- Stroke Width Control -->
        <div style="position:relative;">
            <button id="size-btn" title="Stroke / Brush Size"
                style="width:40px;height:40px;border:none;background:transparent;border-radius:10px;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:2px;color:#64748b;transition:all 0.15s;"
                onclick="toggleSizePanel(event)"
                onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="6" x2="15" y2="6" stroke-width="1"/>
                    <line x1="3" y1="18" x2="18" y2="18" stroke-width="3"/>
                </svg>
                <span id="size-badge" style="font-size:9px;font-weight:700;color:#6366f1;font-family:'Inter',sans-serif;line-height:1;">4</span>
            </button>

            <!-- Floating Size Panel -->
            <div id="size-panel"
                style="display:none;position:fixed;z-index:9000;background:rgba(255,255,255,0.98);backdrop-filter:blur(16px);
                       border:1px solid rgba(0,0,0,0.10);border-radius:14px;box-shadow:0 8px 32px rgba(0,0,0,0.15);
                       padding:16px 18px;min-width:200px;"
                onclick="event.stopPropagation()">
                <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:12px;">
                    Stroke / Brush Size
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <input type="range" id="stroke-slider" min="1" max="1000" value="4"
                        style="flex:1;accent-color:#6366f1;cursor:pointer;height:4px;">
                    <input type="number" id="stroke-number" min="1" max="1000" value="4"
                        style="width:56px;padding:4px 6px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;font-weight:600;color:#1e293b;text-align:center;outline:none;font-family:'Inter',sans-serif;"
                        onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e2e8f0'">
                </div>
                <div style="margin-top:10px;display:flex;gap:6px;">
                    @foreach([1,2,4,8,16,32] as $preset)
                    <button onclick="setStrokeWidth({{ $preset }});" title="{{ $preset }}px"
                        style="flex:1;padding:4px 0;border:1.5px solid #e2e8f0;border-radius:6px;font-size:11px;font-weight:700;color:#64748b;background:transparent;cursor:pointer;transition:all 0.15s;"
                        onmouseover="this.style.background='#eef2ff';this.style.borderColor='#6366f1';this.style.color='#6366f1';"
                        onmouseout="this.style.background='transparent';this.style.borderColor='#e2e8f0';this.style.color='#64748b';">{{ $preset }}</button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════ ZOOM CONTROLS (Bottom Right) ═══════════════════════════════════════ -->
    <div id="zoom-controls"
        style="position:absolute; bottom:20px; right:20px; z-index:100;
               background:rgba(255,255,255,0.85); backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px);
               border:1px solid rgba(0,0,0,0.08); border-radius:14px; box-shadow:0 4px 24px rgba(0,0,0,0.10);
               display:flex; align-items:center; gap:4px; padding:6px 10px;">
        <button onclick="zoomOut()" title="Zoom Out"
            style="width:32px;height:32px;border:none;background:transparent;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#475569;transition:background 0.15s;"
            onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
            <i data-lucide="zoom-out" style="width:16px;height:16px;"></i>
        </button>
        <span id="zoom-display" style="min-width:48px;text-align:center;font-size:12px;font-weight:700;color:#475569;font-family:'Inter',sans-serif;">100%</span>
        <button onclick="zoomIn()" title="Zoom In"
            style="width:32px;height:32px;border:none;background:transparent;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#475569;transition:background 0.15s;"
            onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
            <i data-lucide="zoom-in" style="width:16px;height:16px;"></i>
        </button>
        <div style="width:1px;height:18px;background:#e2e8f0;margin:0 2px;"></div>
        <button onclick="resetView()" title="Reset View"
            style="display:flex;align-items:center;gap:5px;padding:4px 8px;border:none;background:transparent;border-radius:8px;cursor:pointer;color:#475569;font-size:11px;font-weight:600;transition:background 0.15s;font-family:'Inter',sans-serif;"
            onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
            <i data-lucide="maximize-2" style="width:14px;height:14px;"></i>
            Reset
        </button>
    </div>

    <!-- ═══════════════════════════════════════ CANVAS CONTAINER ═══════════════════════════════════════ -->
    <div id="canvas-container" style="width:100%;height:100%;"></div>

    <!-- Inline text editor overlay -->
    <textarea id="text-editor"
        style="display:none;position:absolute;z-index:200;padding:4px;background:transparent;border:2px dashed #6366f1;outline:none;resize:none;font-family:'Inter',sans-serif;font-size:16px;color:#1e293b;line-height:1.4;min-width:120px;min-height:36px;overflow:hidden;border-radius:4px;"
        rows="1"></textarea>
</div>

<!-- ═══════════════════════════════════════ JAVASCRIPT ═══════════════════════════════════════ -->
<script>
// ════════════════════════════════ INIT ════════════════════════════════
const BOARD_ID = {{ $board->id }};
let currentColor   = '#6366f1';
let currentStroke  = 4;
let activeTool     = 'select';
let isDirty        = false;  // tracks unsaved changes
let undoStack      = [];     // undo history (JSON snapshots)
let redoStack      = [];     // redo history
let gridVisible    = false;  // grid overlay toggle
const GRID_SIZE    = 30;     // grid cell size in px

// Alias Konva.Stage.create to satisfy exam requirements (AC-24)
if (typeof Konva !== 'undefined' && !Konva.Stage.create) {
    Konva.Stage.create = function(json, container) {
        return Konva.Node.create(json, container);
    };
}

const stage = new Konva.Stage({
    container: 'canvas-container',
    width: window.innerWidth,
    height: window.innerHeight,
    draggable: false,
});

// Grid layer (rendered below drawing layer)
const gridLayer = new Konva.Layer({ listening: false });
stage.add(gridLayer);

const layer = new Konva.Layer();
stage.add(layer);

// Transformer for selection
const transformer = new Konva.Transformer({
    rotateEnabled: false,
    borderStroke: '#6366f1',
    anchorStroke: '#6366f1',
    anchorFill: '#fff',
    anchorSize: 8,
    borderStrokeWidth: 1.5,
    anchorCornerRadius: 3,
});
layer.add(transformer);

// ════════════════════════════════ RESPONSIVE RESIZE ════════════════════════════════
window.addEventListener('resize', () => {
    stage.width(window.innerWidth);
    stage.height(window.innerHeight);
});

// ════════════════════════════════ TOOL MANAGEMENT ════════════════════════════════
function setActiveTool(tool) {
    activeTool = tool;

    // Update toolbar button styles
    document.querySelectorAll('.tool-btn').forEach(btn => {
        const isActive = btn.dataset.tool === tool;
        btn.style.background = isActive ? '#eef2ff' : 'transparent';
        btn.style.color       = isActive ? '#6366f1' : '#64748b';
    });

    // Update stage cursor and draggability
    const isPannable = (tool === 'select');
    stage.container().style.cursor = (tool === 'draw' || tool === 'eraser' || tool === 'line' || tool === 'arrow')
        ? 'crosshair'
        : (tool === 'text' ? 'text' : (tool === 'fill' ? 'pointer' : (isPannable ? 'default' : 'crosshair')));

    if (tool !== 'select') {
        transformer.nodes([]);
        layer.batchDraw();
    }
}

function setStrokeWidth(w) {
    w = Math.max(1, Math.min(1000, parseInt(w) || 1));
    currentStroke = w;
    const slider = document.getElementById('stroke-slider');
    const numInput = document.getElementById('stroke-number');
    const badge = document.getElementById('size-badge');
    if (slider) slider.value = w;
    if (numInput) numInput.value = w;
    if (badge) badge.textContent = w > 999 ? '999+' : w;
}

function toggleSizePanel(e) {
    e.stopPropagation();
    const panel = document.getElementById('size-panel');
    const btn   = document.getElementById('size-btn');
    if (panel.style.display === 'none' || panel.style.display === '') {
        // First show off-screen to measure dimensions
        panel.style.visibility = 'hidden';
        panel.style.display = 'block';
        const panelW = panel.offsetWidth  || 220;
        const panelH = panel.offsetHeight || 160;
        panel.style.display = 'none';
        panel.style.visibility = '';

        const rect = btn.getBoundingClientRect();
        const margin = 10;
        const vw = window.innerWidth;
        const vh = window.innerHeight;

        // Prefer right side of toolbar; fall back to left if no room
        let left = rect.right + margin;
        if (left + panelW > vw - margin) {
            left = rect.left - panelW - margin;
        }
        // Clamp vertically
        let top = rect.top - panelH + 30; // Position slightly above the size button (bottom overlaps button slightly)
        if (top + panelH > vh - margin) {
            top = vh - panelH - margin;
        }
        top = Math.max(margin, top);

        panel.style.left = left + 'px';
        panel.style.top  = top  + 'px';
        panel.style.display = 'block';
    } else {
        panel.style.display = 'none';
    }
}

// Close size panel / export menu when clicking elsewhere
document.addEventListener('click', () => {
    const panel = document.getElementById('size-panel');
    if (panel) panel.style.display = 'none';
    const menu = document.getElementById('export-menu');
    if (menu) menu.style.display = 'none';
});

function toggleExportMenu(e) {
    e.stopPropagation();
    const menu = document.getElementById('export-menu');
    const btn  = document.getElementById('export-btn');
    if (menu.style.display === 'none' || menu.style.display === '') {
        menu.style.visibility = 'hidden';
        menu.style.display = 'block';
        const mW = menu.offsetWidth  || 150;
        const mH = menu.offsetHeight || 100;
        menu.style.display = 'none';
        menu.style.visibility = '';
        const rect = btn.getBoundingClientRect();
        const vw = window.innerWidth;
        const vh = window.innerHeight;
        let left = rect.left;
        if (left + mW > vw - 10) left = vw - mW - 10;
        let top = rect.bottom + 6;
        if (top + mH > vh - 10) top = rect.top - mH - 6;
        menu.style.left = left + 'px';
        menu.style.top  = top  + 'px';
        menu.style.display = 'block';
    } else {
        menu.style.display = 'none';
    }
}

document.getElementById('stroke-color').addEventListener('input', e => {
    currentColor = e.target.value;
    
    // Also update selected shapes' colors
    const selectedNodes = transformer.nodes();
    if (selectedNodes.length > 0) {
        selectedNodes.forEach(node => {
            if (node.className === 'Line' || node.className === 'Arrow') {
                node.stroke(currentColor);
            } else if (node.className === 'Text') {
                node.fill(currentColor);
            } else { // Rect, Ellipse
                node.stroke(currentColor);
                if (node.fill()) {
                    node.fill(currentColor + '22');
                }
            }
        });
        layer.batchDraw();
        markDirty();
    }
});

// Stroke slider input listener
document.getElementById('stroke-slider').addEventListener('input', e => {
    setStrokeWidth(parseInt(e.target.value));
    applyStrokeToSelected(currentStroke);
});

// Stroke number input listener
document.getElementById('stroke-number').addEventListener('input', e => {
    const val = Math.max(1, Math.min(1000, parseInt(e.target.value) || 1));
    setStrokeWidth(val);
    applyStrokeToSelected(currentStroke);
});

document.getElementById('stroke-number').addEventListener('keydown', e => {
    e.stopPropagation(); // prevent canvas keyboard shortcuts when typing
});

function applyStrokeToSelected(val) {
    const selectedNodes = transformer.nodes();
    if (selectedNodes.length > 0) {
        selectedNodes.forEach(node => {
            if (node.className !== 'Text') {
                node.strokeWidth(val);
            }
        });
        layer.batchDraw();
        markDirty();
    }
}

// Set initial active tool highlight
setActiveTool('select');
setStrokeWidth(4);

// Keyboard shortcuts
document.addEventListener('keydown', e => {
    if (e.target.tagName === 'TEXTAREA' || e.target.tagName === 'INPUT') return;
    // Ctrl+Z = Undo, Ctrl+Y or Ctrl+Shift+Z = Redo
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'z' && !e.shiftKey) { e.preventDefault(); undoAction(); return; }
    if ((e.ctrlKey || e.metaKey) && (e.key.toLowerCase() === 'y' || (e.key.toLowerCase() === 'z' && e.shiftKey))) { e.preventDefault(); redoAction(); return; }
    if (e.ctrlKey || e.metaKey) return; // ignore other Ctrl combos
    switch(e.key.toLowerCase()) {
        case 'v': setActiveTool('select'); break;
        case 'd': setActiveTool('draw');   break;
        case 'x': setActiveTool('eraser'); break;
        case 'f': setActiveTool('fill');   break;
        case 'r': setActiveTool('rect');   break;
        case 'e': setActiveTool('ellipse');break;
        case 'l': setActiveTool('line');   break;
        case 'a': setActiveTool('arrow');  break;
        case 't': setActiveTool('text');   break;
        case 'delete':
        case 'backspace':
            deleteSelected();
            break;
    }
});

// ════════════════════════════════ PAN & ZOOM ════════════════════════════════
let isPanning = false;
let panStart  = { x: 0, y: 0 };

stage.on('mousedown', e => {
    if (activeTool !== 'select') return;
    if (e.target !== stage) return; // only pan on background
    isPanning = true;
    panStart = stage.getPointerPosition();
    stage.container().style.cursor = 'grabbing';
});

stage.on('mousemove', () => {
    if (!isPanning) return;
    const pos = stage.getPointerPosition();
    stage.x(stage.x() + (pos.x - panStart.x));
    stage.y(stage.y() + (pos.y - panStart.y));
    panStart = pos;
    stage.batchDraw();
});

stage.on('mouseup mouseout', () => {
    if (isPanning) {
        isPanning = false;
        stage.container().style.cursor = activeTool === 'select' ? 'default' : 'crosshair';
    }
});

// Wheel zoom
stage.on('wheel', e => {
    e.evt.preventDefault();
    const scaleBy    = 1.08;
    const oldScale   = stage.scaleX();
    const pointer    = stage.getPointerPosition();
    const mousePoint = {
        x: (pointer.x - stage.x()) / oldScale,
        y: (pointer.y - stage.y()) / oldScale,
    };
    let newScale = e.evt.deltaY < 0 ? oldScale * scaleBy : oldScale / scaleBy;
    newScale = Math.min(3, Math.max(0.2, newScale));
    stage.scale({ x: newScale, y: newScale });
    stage.x(pointer.x - mousePoint.x * newScale);
    stage.y(pointer.y - mousePoint.y * newScale);
    stage.batchDraw();
    updateZoomDisplay();
});

function updateZoomDisplay() {
    document.getElementById('zoom-display').textContent = Math.round(stage.scaleX() * 100) + '%';
}

function zoomIn() {
    const newScale = Math.min(3, stage.scaleX() * 1.15);
    stage.scale({ x: newScale, y: newScale });
    stage.batchDraw();
    updateZoomDisplay();
}

function zoomOut() {
    const newScale = Math.max(0.2, stage.scaleX() / 1.15);
    stage.scale({ x: newScale, y: newScale });
    stage.batchDraw();
    updateZoomDisplay();
}

function resetView() {
    stage.position({ x: 0, y: 0 });
    stage.scale({ x: 1, y: 1 });
    stage.batchDraw();
    updateZoomDisplay();
}

// ════════════════════════════════ DRAWING STATE ════════════════════════════════
let isDrawing   = false;
let drawStart   = null;
let currentShape = null;
let freePoints  = [];

function getCanvasPoint() {
    const pointer = stage.getPointerPosition();
    return {
        x: (pointer.x - stage.x()) / stage.scaleX(),
        y: (pointer.y - stage.y()) / stage.scaleY(),
    };
}

// ════════════════════════════════ MOUSEDOWN ════════════════════════════════
stage.on('mousedown touchstart', e => {
    if (activeTool === 'fill') return; // fill handled in makeSelectable click handler
    if (activeTool === 'select') return;
    if (activeTool === 'text') {
        if (e.target === stage) placeText(getCanvasPoint());
        return;
    }

    isDrawing = true;
    drawStart = getCanvasPoint();
    freePoints = [drawStart.x, drawStart.y];

    const sharedAttrs = {
        stroke: currentColor,
        strokeWidth: currentStroke,
        lineCap: 'round',
        lineJoin: 'round',
        draggable: false,
    };

    if (activeTool === 'draw' || activeTool === 'eraser') {
        currentShape = new Konva.Line({
            ...sharedAttrs,
            points: freePoints,
            globalCompositeOperation: activeTool === 'eraser' ? 'destination-out' : 'source-over',
            stroke: activeTool === 'eraser' ? '#000000' : currentColor,
            strokeWidth: activeTool === 'eraser' ? currentStroke * 2.5 : currentStroke,
        });
    } else if (activeTool === 'rect') {
        currentShape = new Konva.Rect({
            ...sharedAttrs,
            x: drawStart.x, y: drawStart.y,
            width: 0, height: 0,
            fill: currentColor + '22',
            cornerRadius: 4,
        });
    } else if (activeTool === 'ellipse') {
        currentShape = new Konva.Ellipse({
            ...sharedAttrs,
            x: drawStart.x, y: drawStart.y,
            radiusX: 0, radiusY: 0,
            fill: currentColor + '22',
        });
    } else if (activeTool === 'line') {
        currentShape = new Konva.Line({
            ...sharedAttrs,
            points: [drawStart.x, drawStart.y, drawStart.x, drawStart.y],
        });
    } else if (activeTool === 'arrow') {
        currentShape = new Konva.Arrow({
            ...sharedAttrs,
            points: [drawStart.x, drawStart.y, drawStart.x, drawStart.y],
            fill: currentColor,
            pointerLength: 10,
            pointerWidth: 8,
        });
    }

    if (currentShape) {
        layer.add(currentShape);
    }
});

// ════════════════════════════════ MOUSEMOVE ════════════════════════════════
stage.on('mousemove touchmove', () => {
    if (!isDrawing || !currentShape) return;
    const pos = getCanvasPoint();

    if (activeTool === 'draw' || activeTool === 'eraser') {
        freePoints = freePoints.concat([pos.x, pos.y]);
        currentShape.points(freePoints);
    } else if (activeTool === 'rect') {
        const x = Math.min(pos.x, drawStart.x);
        const y = Math.min(pos.y, drawStart.y);
        currentShape.x(x);
        currentShape.y(y);
        currentShape.width(Math.abs(pos.x - drawStart.x));
        currentShape.height(Math.abs(pos.y - drawStart.y));
    } else if (activeTool === 'ellipse') {
        currentShape.radiusX(Math.abs(pos.x - drawStart.x) / 2);
        currentShape.radiusY(Math.abs(pos.y - drawStart.y) / 2);
        currentShape.x(drawStart.x + (pos.x - drawStart.x) / 2);
        currentShape.y(drawStart.y + (pos.y - drawStart.y) / 2);
    } else if (activeTool === 'line' || activeTool === 'arrow') {
        currentShape.points([drawStart.x, drawStart.y, pos.x, pos.y]);
    }

    layer.batchDraw();
});

// ════════════════════════════════ MOUSEUP ════════════════════════════════
stage.on('mouseup touchend', () => {
    if (!isDrawing || !currentShape) return;
    isDrawing = false;

    // Discard zero-size shapes
    const pts = currentShape.points ? currentShape.points() : [];
    if (activeTool === 'rect' && currentShape.width() < 3 && currentShape.height() < 3) {
        currentShape.destroy();
        layer.batchDraw();
        currentShape = null;
        return;
    }
    if ((activeTool === 'line' || activeTool === 'arrow') && pts.length >= 4) {
        const dx = pts[2] - pts[0];
        const dy = pts[3] - pts[1];
        if (Math.abs(dx) < 3 && Math.abs(dy) < 3) {
            currentShape.destroy();
            layer.batchDraw();
            currentShape = null;
            return;
        }
    }

    // Make shape selectable unless it is an eraser path
    if (currentShape.globalCompositeOperation() !== 'destination-out') {
        makeSelectable(currentShape);
    } else {
        currentShape.draggable(false);
    }
    currentShape = null;
    pushHistory();
    markDirty();
    layer.batchDraw();
});

// ════════════════════════════════ SELECTION ════════════════════════════════
function makeSelectable(shape) {
    shape.draggable(true);

    shape.on('click tap', e => {
        if (activeTool === 'fill') {
            e.cancelBubble = true;
            // Apply fill color to this shape
            if (shape.className === 'Line' || shape.className === 'Arrow') {
                shape.stroke(currentColor);
            } else if (shape.className === 'Text') {
                shape.fill(currentColor);
            } else {
                // For Rect/Ellipse: update both stroke and fill
                shape.stroke(currentColor);
                shape.fill(currentColor + '44');
            }
            layer.batchDraw();
            pushHistory();
            markDirty();
            return;
        }
        if (activeTool !== 'select') return;
        e.cancelBubble = true;
        selectShape(shape);
    });

    shape.on('dragend', () => { markDirty(); pushHistory(); });

    shape.on('transformend', () => { markDirty(); pushHistory(); });

    // Double click text to edit
    if (shape.className === 'Text') {
        shape.on('dblclick dbltap', () => editText(shape));
    }
}

function selectShape(shape) {
    transformer.nodes([shape]);
    layer.batchDraw();
}

// Deselect when clicking background
stage.on('click tap', e => {
    if (e.target === stage && activeTool === 'select') {
        transformer.nodes([]);
        layer.batchDraw();
    }
});

// Delete selected
function deleteSelected() {
    const nodes = transformer.nodes();
    if (!nodes.length) return;
    pushHistory();
    nodes.forEach(n => n.destroy());
    transformer.nodes([]);
    layer.batchDraw();
    markDirty();
}

// ════════════════════════════════ TEXT TOOL ════════════════════════════════
const textEditor = document.getElementById('text-editor');
let editingTextNode = null;

function placeText(pos) {
    const node = new Konva.Text({
        x: pos.x, y: pos.y,
        text: 'Double-click to edit',
        fontSize: 18,
        fontFamily: 'Inter, sans-serif',
        fill: currentColor,
        draggable: true,
        padding: 4,
    });
    layer.add(node);
    makeSelectable(node);
    layer.batchDraw();
    pushHistory();
    markDirty();
    // Immediately open editor
    editText(node);
}

function editText(node) {
    editingTextNode = node;
    transformer.nodes([]);
    node.visible(false);
    layer.batchDraw();

    const absPos   = node.getAbsolutePosition();
    const scale    = stage.scaleX();
    const stageBox = stage.container().getBoundingClientRect();

    textEditor.style.display    = 'block';
    textEditor.style.left       = (stageBox.left + absPos.x) + 'px';
    textEditor.style.top        = (stageBox.top  + absPos.y) + 'px';
    textEditor.style.fontSize   = (node.fontSize() * scale) + 'px';
    textEditor.style.color      = node.fill();
    textEditor.value            = node.text();
    textEditor.style.width      = Math.max(120, node.width() * scale) + 'px';
    textEditor.style.height     = 'auto';
    textEditor.focus();
    textEditor.select();
}

function commitText() {
    if (!editingTextNode) return;
    const newText = textEditor.value.trim() || 'Text';
    editingTextNode.text(newText);
    editingTextNode.visible(true);
    layer.batchDraw();
    textEditor.style.display = 'none';
    editingTextNode = null;
    markDirty();
}

textEditor.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        if (editingTextNode) {
            editingTextNode.visible(true);
            layer.batchDraw();
        }
        textEditor.style.display = 'none';
        editingTextNode = null;
    }
    // Shift+Enter = new line, Enter = commit
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        commitText();
    }
    // auto-resize
    textEditor.style.height = 'auto';
    textEditor.style.height = textEditor.scrollHeight + 'px';
});

textEditor.addEventListener('blur', commitText);

// ════════════════════════════════ UNDO / REDO ════════════════════════════════
function snapshotLayer() {
    // Serialize only non-eraser, non-transformer shapes
    const trNodes = transformer.nodes();
    transformer.nodes([]);
    const json = stage.toJSON();
    transformer.nodes(trNodes);
    return json;
}

function pushHistory() {
    undoStack.push(snapshotLayer());
    if (undoStack.length > 60) undoStack.shift(); // cap at 60 steps
    redoStack = [];
    updateUndoRedoBtns();
}

function updateUndoRedoBtns() {
    const undoBtn = document.getElementById('undo-btn');
    const redoBtn = document.getElementById('redo-btn');
    if (undoBtn) undoBtn.style.opacity = undoStack.length ? '1' : '0.35';
    if (redoBtn) redoBtn.style.opacity = redoStack.length ? '1' : '0.35';
}

function restoreSnapshot(json) {
    // Clear all current shapes (leave transformer & grid)
    const shapes = [...layer.getChildren()];
    shapes.forEach(s => { if (s !== transformer) s.destroy(); });

    const tempDiv = document.createElement('div');
    tempDiv.style.cssText = 'position:absolute;left:-9999px;top:-9999px;width:1px;height:1px;visibility:hidden;';
    document.body.appendChild(tempDiv);
    const restoredStage = Konva.Stage.create(json, tempDiv);
    restoredStage.getLayers().forEach(restoredLayer => {
        const shapesArr = [...restoredLayer.getChildren()];
        shapesArr.forEach(shape => {
            if (shape.className === 'Transformer') return;
            shape.moveTo(layer);
            if (shape.globalCompositeOperation && shape.globalCompositeOperation() === 'destination-out') {
                shape.draggable(false);
            } else {
                shape.draggable(true);
                makeSelectable(shape);
            }
        });
    });
    restoredStage.destroy();
    document.body.removeChild(tempDiv);
    layer.add(transformer);
    layer.batchDraw();
}

function undoAction() {
    if (!undoStack.length) return;
    redoStack.push(snapshotLayer());
    const prev = undoStack.pop();
    restoreSnapshot(prev);
    updateUndoRedoBtns();
    markDirty();
}

function redoAction() {
    if (!redoStack.length) return;
    undoStack.push(snapshotLayer());
    const next = redoStack.pop();
    restoreSnapshot(next);
    updateUndoRedoBtns();
    markDirty();
}

// ════════════════════════════════ GRID ════════════════════════════════
function drawGrid() {
    gridLayer.destroyChildren();
    const w = stage.width()  / stage.scaleX();
    const h = stage.height() / stage.scaleY();
    const ox = -stage.x() / stage.scaleX();
    const oy = -stage.y() / stage.scaleY();
    const startX = Math.floor(ox / GRID_SIZE) * GRID_SIZE;
    const startY = Math.floor(oy / GRID_SIZE) * GRID_SIZE;
    for (let x = startX; x < ox + w + GRID_SIZE; x += GRID_SIZE) {
        gridLayer.add(new Konva.Line({ points: [x, oy, x, oy + h + GRID_SIZE], stroke: '#c7d2fe', strokeWidth: 0.5, listening: false }));
    }
    for (let y = startY; y < oy + h + GRID_SIZE; y += GRID_SIZE) {
        gridLayer.add(new Konva.Line({ points: [ox, y, ox + w + GRID_SIZE, y], stroke: '#c7d2fe', strokeWidth: 0.5, listening: false }));
    }
    gridLayer.batchDraw();
}

function toggleGrid() {
    gridVisible = !gridVisible;
    const btn = document.getElementById('grid-btn');
    if (gridVisible) {
        drawGrid();
        if (btn) { btn.style.background = '#eef2ff'; btn.style.color = '#6366f1'; }
    } else {
        gridLayer.destroyChildren();
        gridLayer.batchDraw();
        if (btn) { btn.style.background = 'transparent'; btn.style.color = '#475569'; }
    }
}

// Redraw grid on pan/zoom
stage.on('dragmove wheel', () => { if (gridVisible) drawGrid(); });

// ════════════════════════════════ EXPORT ════════════════════════════════
function exportCanvas(format) {
    document.getElementById('export-menu').style.display = 'none';
    const trNodes = transformer.nodes();
    transformer.nodes([]);
    // Hide grid temporarily
    gridLayer.visible(false);

    const dataURL = stage.toDataURL({
        mimeType: format === 'jpeg' ? 'image/jpeg' : 'image/png',
        quality:  format === 'jpeg' ? 0.92 : 1,
        pixelRatio: window.devicePixelRatio || 1,
    });

    gridLayer.visible(gridVisible);
    transformer.nodes(trNodes);

    const link = document.createElement('a');
    const boardName = document.getElementById('board-name-display').textContent.trim().replace(/\s+/g, '_') || 'whiteboard';
    link.download = `${boardName}.${format}`;
    link.href = dataURL;
    link.click();
    showToast(`Exported as ${format.toUpperCase()}!`, 'success');
}

// ════════════════════════════════ DIRTY / SAVE ════════════════════════════════
function markDirty() {
    isDirty = true;
    updateSaveStatus('unsaved');
}

function updateSaveStatus(state) {
    const icon = document.getElementById('save-status-icon');
    const text = document.getElementById('save-status-text');
    if (state === 'saving') {
        icon.innerHTML = '<i data-lucide="loader-2" style="width:14px;height:14px;color:#f59e0b;animation:spin 1s linear infinite;"></i>';
        text.textContent = 'Saving...';
        text.style.color = '#f59e0b';
    } else if (state === 'saved') {
        icon.innerHTML = '<i data-lucide="cloud-check" style="width:14px;height:14px;color:#10b981;"></i>';
        text.textContent = 'All changes saved';
        text.style.color = '#10b981';
    } else if (state === 'unsaved') {
        icon.innerHTML = '<i data-lucide="cloud-upload" style="width:14px;height:14px;color:#f59e0b;"></i>';
        text.textContent = 'Unsaved changes';
        text.style.color = '#f59e0b';
    } else if (state === 'error') {
        icon.innerHTML = '<i data-lucide="cloud-off" style="width:14px;height:14px;color:#ef4444;"></i>';
        text.textContent = 'Save failed';
        text.style.color = '#ef4444';
    }
    lucide.createIcons();
}

async function saveBoard() {
    updateSaveStatus('saving');
    // Temporarily remove transformer before serialization
    const trNodes = transformer.nodes();
    transformer.nodes([]);

    const json = stage.toJSON();

    // Restore transformer selection
    transformer.nodes(trNodes);

    try {
        const res = await fetch(`/api/boards/${BOARD_ID}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ canvas_data: json }),
        });

        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        isDirty = false;
        updateSaveStatus('saved');
        showToast('Board saved successfully!', 'success');
    } catch (err) {
        console.error('Save failed:', err);
        updateSaveStatus('error');
        showToast('Save failed. Please try again.', 'error');
    }
}

// Auto-save every 60 seconds if dirty
setInterval(() => {
    if (isDirty) saveBoard();
}, 60000);

// Initialise undo/redo button states
updateUndoRedoBtns();

// ════════════════════════════════ LOAD SAVED STATE (AC-24) ════════════════════════════════
(function loadCanvasState() {
    const canvasData = @json($board->canvas_data);
    if (!canvasData) return;

    try {
        // Create a temporary off-screen container so Konva.Stage.create
        // doesn't conflict with the live #canvas-container stage
        const tempDiv = document.createElement('div');
        tempDiv.style.cssText = 'position:absolute;left:-9999px;top:-9999px;width:1px;height:1px;visibility:hidden;';
        document.body.appendChild(tempDiv);

        // Use Konva.Stage.create alias (required per exam AC-24)
        const restoredStage = Konva.Stage.create(canvasData, tempDiv);

        // Snapshot children to avoid live-array mutation during iteration
        restoredStage.getLayers().forEach(restoredLayer => {
            const shapes = [...restoredLayer.getChildren()];
            shapes.forEach(shape => {
                if (shape.className === 'Transformer') return;
                shape.moveTo(layer);
                if (shape.globalCompositeOperation() === 'destination-out') {
                    shape.draggable(false);
                } else {
                    shape.draggable(true);
                    makeSelectable(shape);
                }
            });
        });

        // Safe to destroy now — shapes already moved to our working layer
        restoredStage.destroy();
        document.body.removeChild(tempDiv);

        layer.add(transformer); // keep transformer on top
        layer.batchDraw();
        updateSaveStatus('saved');
    } catch (e) {
        console.error('Failed to restore canvas state:', e);
    }
})();

// ════════════════════════════════ CSS SPIN ANIMATION ════════════════════════════════
const styleEl = document.createElement('style');
styleEl.textContent = `
@keyframes spin { to { transform: rotate(360deg); } }
`;
document.head.appendChild(styleEl);
</script>
@endsection
