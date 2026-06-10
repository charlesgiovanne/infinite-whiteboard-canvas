@extends('layouts.app')

@section('content')
<style>
/* ─── Canvas page base ─── */
#wb-root {
    position: relative; width: 100%; height: 100vh;
    overflow: hidden;
    background: #eef0f6;
}
/* Subtle dot grid background */
#wb-root::before {
    content: '';
    position: absolute; inset: 0;
    pointer-events: none; z-index: 0;
    background-image: radial-gradient(circle, #c7d2fe 1px, transparent 1px);
    background-size: 26px 26px;
    opacity: 0.5;
}

/* ─── TOP BAR ─── */
#top-bar {
    position: absolute; top: 14px; left: 50%; transform: translateX(-50%);
    z-index: 100;
    background: rgba(255,255,255,0.92);
    backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(0,0,0,0.07);
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08), 0 1px 3px rgba(0,0,0,0.04);
    display: flex; align-items: center; gap: 4px;
    padding: 6px 10px;
    white-space: nowrap;
}
.tb-sep { width: 1px; height: 20px; background: #e2e8f0; flex-shrink: 0; margin: 0 2px; }
.tb-icon-btn {
    width: 32px; height: 32px; border: none; background: transparent;
    border-radius: 8px; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #64748b; transition: background 0.15s, color 0.15s; flex-shrink: 0;
}
.tb-icon-btn:hover { background: #f1f5f9; color: #1e293b; }
.tb-icon-btn.active { background: #eef2ff; color: #6366f1; }
.tb-text-btn {
    display: flex; align-items: center; gap: 5px;
    padding: 5px 9px; border: none; background: transparent;
    border-radius: 8px; cursor: pointer;
    color: #64748b; font-size: 12px; font-weight: 600;
    transition: background 0.15s, color 0.15s;
    font-family: 'Inter', sans-serif;
}
.tb-text-btn:hover { background: #f1f5f9; color: #1e293b; }
.tb-back {
    display: flex; align-items: center; gap: 5px;
    padding: 5px 9px; border-radius: 8px;
    color: #64748b; font-size: 12px; font-weight: 600;
    text-decoration: none; transition: background 0.15s, color 0.15s;
}
.tb-back:hover { background: #f1f5f9; color: #1e293b; }
.tb-board-name {
    font-size: 13px; font-weight: 700; color: #1e293b;
    font-family: 'Outfit', sans-serif;
    max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
/* Save status pill */
#save-pill {
    display: flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 100px;
    font-size: 11px; font-weight: 600; font-family: 'Inter', sans-serif;
    background: #f0fdf4; color: #10b981;
    transition: background 0.3s, color 0.3s;
}
/* Save button */
#save-btn {
    display: flex; align-items: center; gap: 5px;
    padding: 6px 13px; background: linear-gradient(135deg,#6366f1,#8b5cf6);
    color: #fff; border: none; border-radius: 9px;
    font-size: 12px; font-weight: 700; cursor: pointer;
    transition: opacity 0.15s, transform 0.1s;
    font-family: 'Inter', sans-serif;
    box-shadow: 0 2px 8px rgba(99,102,241,0.35);
}
#save-btn:hover { opacity: 0.9; }
#save-btn:active { transform: scale(0.97); }

/* ─── TOOLBAR (left) ─── */
#toolbar {
    position: absolute; top: 50%; left: 14px; transform: translateY(-50%);
    z-index: 100;
    background: rgba(255,255,255,0.92);
    backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(0,0,0,0.07);
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08), 0 1px 3px rgba(0,0,0,0.04);
    display: flex; flex-direction: column; align-items: center;
    gap: 2px; padding: 10px 7px;
}
.tool-btn {
    width: 44px; height: 40px;
    border: none; background: transparent;
    border-radius: 10px; cursor: pointer;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center; gap: 3px;
    color: #64748b; transition: all 0.15s;
}
.tool-btn:hover { background: #f1f5f9; color: #1e293b; }
.tool-btn.active { background: #eef2ff; color: #6366f1; }
.tool-key {
    font-size: 8px; font-weight: 700; letter-spacing: 0.04em;
    color: inherit; opacity: 0.5; line-height: 1;
    font-family: 'Inter', sans-serif;
}
.toolbar-sep { width: 26px; height: 1px; background: #e2e8f0; margin: 4px 0; }

/* Color swatch */
#color-wrap {
    width: 34px; height: 34px;
    border-radius: 50%;
    border: 2.5px solid #e2e8f0;
    overflow: hidden; cursor: pointer;
    transition: border-color 0.15s, box-shadow 0.15s;
    position: relative;
}
#color-wrap:hover { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.15); }
#stroke-color {
    position: absolute; inset: -8px;
    width: calc(100% + 16px); height: calc(100% + 16px);
    border: none; padding: 0; cursor: pointer; background: transparent;
}

/* Size btn */
#size-btn {
    width: 44px; height: 40px; border: none; background: transparent;
    border-radius: 10px; cursor: pointer;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 3px; color: #64748b; transition: all 0.15s;
}
#size-btn:hover { background: #f1f5f9; }
#size-badge { font-size: 9px; font-weight: 700; color: #6366f1; font-family: 'Inter', sans-serif; line-height: 1; }

/* Size panel */
#size-panel {
    display: none; position: fixed; z-index: 9000;
    background: rgba(255,255,255,0.98); backdrop-filter: blur(16px);
    border: 1px solid rgba(0,0,0,0.10); border-radius: 16px;
    box-shadow: 0 16px 48px rgba(0,0,0,0.14);
    padding: 18px 20px; min-width: 212px;
}
.size-panel-title {
    font-size: 10px; font-weight: 700; color: #94a3b8;
    text-transform: uppercase; letter-spacing: 0.09em; margin-bottom: 14px;
}
.size-presets { margin-top: 12px; display: flex; gap: 5px; }
.size-preset-btn {
    flex: 1; padding: 5px 0; border: 1.5px solid #e2e8f0;
    border-radius: 7px; font-size: 11px; font-weight: 700; color: #64748b;
    background: transparent; cursor: pointer; transition: all 0.15s;
}
.size-preset-btn:hover { background: #eef2ff; border-color: #6366f1; color: #6366f1; }

/* Export dropdown */
#export-menu {
    display: none; position: fixed; z-index: 9500;
    background: rgba(255,255,255,0.98); backdrop-filter: blur(16px);
    border: 1px solid rgba(0,0,0,0.10); border-radius: 13px;
    box-shadow: 0 10px 36px rgba(0,0,0,0.14); padding: 6px; min-width: 148px;
}
.export-item {
    width: 100%; display: flex; align-items: center; gap: 8px;
    padding: 8px 10px; border: none; background: transparent;
    border-radius: 9px; cursor: pointer; font-size: 13px; font-weight: 500;
    color: #1e293b; text-align: left; transition: background 0.15s;
}
.export-item:hover { background: #f1f5f9; }

/* ─── ZOOM STRIP (bottom centre) ─── */
#zoom-controls {
    position: absolute; bottom: 18px; left: 50%; transform: translateX(-50%);
    z-index: 100;
    background: rgba(255,255,255,0.92);
    backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(0,0,0,0.07);
    border-radius: 14px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    display: flex; align-items: center; gap: 2px; padding: 5px 8px;
}
.zoom-btn {
    width: 30px; height: 30px; border: none; background: transparent;
    border-radius: 7px; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #475569; transition: background 0.15s;
}
.zoom-btn:hover { background: #f1f5f9; }
#zoom-display {
    min-width: 50px; text-align: center;
    font-size: 12px; font-weight: 700; color: #475569;
    font-family: 'Inter', sans-serif; padding: 0 4px;
}
.zoom-sep { width: 1px; height: 16px; background: #e2e8f0; margin: 0 3px; }
.zoom-reset-btn {
    display: flex; align-items: center; gap: 4px;
    padding: 4px 8px; border: none; background: transparent;
    border-radius: 7px; cursor: pointer; color: #475569;
    font-size: 11px; font-weight: 600; transition: background 0.15s;
    font-family: 'Inter', sans-serif;
}
.zoom-reset-btn:hover { background: #f1f5f9; }

/* Text editor */
#text-editor {
    display: none; position: absolute; z-index: 200;
    padding: 6px 8px;
    background: rgba(255,255,255,0.92); backdrop-filter: blur(8px);
    border: 2px solid #6366f1; outline: none; resize: none;
    font-family: 'Inter', sans-serif; font-size: 16px; color: #1e293b;
    line-height: 1.5; min-width: 140px; min-height: 40px;
    overflow: hidden; border-radius: 8px;
    box-shadow: 0 4px 16px rgba(99,102,241,0.18);
}
</style>

<div id="wb-root">

    {{-- ══════════ TOP BAR ══════════ --}}
    <header id="top-bar">
        {{-- Back to boards --}}
        <a href="{{ route('boards.index') }}" class="tb-back" title="All Boards">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
            Boards
        </a>

        <div class="tb-sep"></div>

        {{-- Board name --}}
        <div style="display:flex;align-items:center;gap:7px;">
            <div style="width:22px;height:22px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
            </div>
            <span id="board-name-display" class="tb-board-name">{{ $board->name }}</span>
        </div>

        <div class="tb-sep"></div>

        {{-- Save status pill --}}
        <div id="save-pill">
            <span id="save-status-icon">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M20 6 9 17l-5-5"/></svg>
            </span>
            <span id="save-status-text">Saved</span>
        </div>

        <div class="tb-sep"></div>

        {{-- Undo --}}
        <button id="undo-btn" class="tb-icon-btn" onclick="undoAction()" title="Undo (Ctrl+Z)">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M9 14 4 9l5-5"/><path d="M4 9h10.5a5.5 5.5 0 0 1 0 11H11"/></svg>
        </button>
        {{-- Redo --}}
        <button id="redo-btn" class="tb-icon-btn" onclick="redoAction()" title="Redo (Ctrl+Y)">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="m15 14 5-5-5-5"/><path d="M20 9H9.5a5.5 5.5 0 0 0 0 11H13"/></svg>
        </button>

        <div class="tb-sep"></div>

        {{-- Grid toggle --}}
        <button id="grid-btn" class="tb-icon-btn" onclick="toggleGrid()" title="Toggle Grid (G)">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        </button>

        <div class="tb-sep"></div>

        {{-- Export --}}
        <div style="position:relative;">
            <button class="tb-text-btn" id="export-btn" onclick="toggleExportMenu(event)" title="Export Canvas">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export
            </button>
            <div id="export-menu">
                <button class="export-item" onclick="exportCanvas('png')">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    Save as PNG
                </button>
                <button class="export-item" onclick="exportCanvas('jpeg')">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    Save as JPEG
                </button>
            </div>
        </div>

        {{-- Save --}}
        <button id="save-btn" onclick="saveBoard()">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            Save
        </button>
    </header>

    {{-- ══════════ TOOLBAR (Left) ══════════ --}}
    <div id="toolbar">
        @php
        $tools = [
            ['id'=>'select',  'path'=>'M5 3l14 9-7 1-3 7z',                                      'label'=>'Select',  'key'=>'V'],
            ['id'=>'hand',    'path'=>'M18 11V6a2 2 0 0 0-4 0v0M14 10V4a2 2 0 0 0-4 0v2M10 10.5V6a2 2 0 0 0-4 0v8M6 14v-3.5a2 2 0 0 0-4 0v7a8 8 0 0 0 8 8h2c2.8 0 4.5-.86 5.4-2.4l3.9-7', 'label'=>'Pan', 'key'=>'H'],
            ['id'=>'draw',    'path'=>'M20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75M3 17.25V21h3.75L17.81 9.94l-3.75-3.75z', 'label'=>'Draw', 'key'=>'D'],
            ['id'=>'eraser',  'path'=>'M20 20H7L3 16l10-10 7 7-1.5 1.5M6.5 17.5l5-5',           'label'=>'Erase',   'key'=>'X'],
            ['id'=>'rect',    'path'=>'M3 3h18v18H3z',                                            'label'=>'Rect',    'key'=>'R'],
            ['id'=>'ellipse', 'path'=>'M12 12m-9 0a9 4 0 1 0 18 0a9 4 0 1 0-18 0',              'label'=>'Ellipse', 'key'=>'E'],
            ['id'=>'line',    'path'=>'M5 19L19 5',                                               'label'=>'Line',    'key'=>'L'],
            ['id'=>'arrow',   'path'=>'M5 12h14M12 5l7 7-7 7',                                   'label'=>'Arrow',   'key'=>'A'],
            ['id'=>'text',    'path'=>'M4 7V4h16v3M9 20h6M12 4v16',                             'label'=>'Text',    'key'=>'T'],
        ];
        @endphp

        @foreach($tools as $i => $tool)
            @if($i === 4)<div class="toolbar-sep"></div>@endif
            <button class="tool-btn" id="tool-{{ $tool['id'] }}" data-tool="{{ $tool['id'] }}"
                title="{{ $tool['label'] }} ({{ $tool['key'] }})"
                onclick="setActiveTool('{{ $tool['id'] }}')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" style="pointer-events:none;">
                    <path d="{{ $tool['path'] }}"/>
                </svg>
                <span class="tool-key">{{ $tool['key'] }}</span>
            </button>
        @endforeach

        <div class="toolbar-sep"></div>

        {{-- Color swatch --}}
        <div id="color-wrap" title="Stroke Color">
            <input type="color" id="stroke-color" value="#6366f1">
        </div>

        {{-- Size --}}
        <div style="position:relative;">
            <button id="size-btn" title="Brush / Stroke Size" onclick="toggleSizePanel(event)">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="6" x2="14" y2="6" stroke-width="1"/>
                    <line x1="3" y1="18" x2="17" y2="18" stroke-width="3"/>
                </svg>
                <span id="size-badge">4</span>
            </button>
            <div id="size-panel" onclick="event.stopPropagation()">
                <div class="size-panel-title">Stroke / Brush Size</div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <input type="range" id="stroke-slider" min="1" max="1000" value="4"
                        style="flex:1;accent-color:#6366f1;cursor:pointer;height:4px;">
                    <input type="number" id="stroke-number" min="1" max="1000" value="4"
                        style="width:54px;padding:5px 6px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;font-weight:600;color:#1e293b;text-align:center;outline:none;font-family:'Inter',sans-serif;"
                        onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e2e8f0'">
                </div>
                <div class="size-presets">
                    @foreach([1,2,4,8,16,32] as $preset)
                    <button class="size-preset-btn" onclick="setStrokeWidth({{ $preset }})">{{ $preset }}</button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════ ZOOM STRIP (Bottom centre) ══════════ --}}
    <div id="zoom-controls">
        <button class="zoom-btn" onclick="zoomOut()" title="Zoom Out">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
        </button>
        <span id="zoom-display">100%</span>
        <button class="zoom-btn" onclick="zoomIn()" title="Zoom In">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
        </button>
        <div class="zoom-sep"></div>
        <button class="zoom-reset-btn" onclick="resetView()" title="Reset View">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
            Reset
        </button>
    </div>

    {{-- Canvas container --}}
    <div id="canvas-container" style="width:100%;height:100%;position:relative;z-index:1;"></div>

    {{-- Inline text editor overlay --}}
    <textarea id="text-editor" rows="1"></textarea>
</div>

<!-- ═══════════════════════════════════════ JAVASCRIPT ═══════════════════════════════════════ -->
<script>
const BOARD_ID = {{ $board->id }};
let currentColor   = '#6366f1';
let currentStroke  = 4;
let activeTool     = 'select';
let isDirty        = false;
let historyList    = [];
let historyIndex   = -1;
let gridVisible    = false;
const GRID_SIZE    = 30;

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

    document.querySelectorAll('.tool-btn').forEach(btn => {
        const isActive = btn.dataset.tool === tool;
        btn.classList.toggle('active', isActive);
    });

    const cursors = {
        select: 'default', hand: 'grab',
        draw: 'crosshair', eraser: 'crosshair',
        line: 'crosshair', arrow: 'crosshair',
        rect: 'crosshair', ellipse: 'crosshair',
        text: 'text',
    };
    stage.container().style.cursor = cursors[tool] || 'default';

    if (tool !== 'select') {
        transformer.nodes([]);
        layer.batchDraw();
    }
}

function setStrokeWidth(w) {
    w = Math.max(1, Math.min(1000, parseInt(w) || 1));
    currentStroke = w;
    const slider   = document.getElementById('stroke-slider');
    const numInput = document.getElementById('stroke-number');
    const badge    = document.getElementById('size-badge');
    if (slider)   slider.value    = w;
    if (numInput) numInput.value  = w;
    if (badge)    badge.textContent = w > 999 ? '999+' : w;
}

function toggleSizePanel(e) {
    e.stopPropagation();
    const panel = document.getElementById('size-panel');
    const btn   = document.getElementById('size-btn');
    if (panel.style.display === 'none' || panel.style.display === '') {
        panel.style.visibility = 'hidden';
        panel.style.display    = 'block';
        const panelW = panel.offsetWidth  || 220;
        const panelH = panel.offsetHeight || 160;
        panel.style.display    = 'none';
        panel.style.visibility = '';
        const rect   = btn.getBoundingClientRect();
        const margin = 10;
        let left = rect.right + margin;
        if (left + panelW > window.innerWidth - margin) left = rect.left - panelW - margin;
        let top = rect.top - panelH + 30;
        if (top + panelH > window.innerHeight - margin) top = window.innerHeight - panelH - margin;
        top = Math.max(margin, top);
        panel.style.left    = left + 'px';
        panel.style.top     = top  + 'px';
        panel.style.display = 'block';
    } else {
        panel.style.display = 'none';
    }
}

document.addEventListener('click', () => {
    document.getElementById('size-panel').style.display   = 'none';
    document.getElementById('export-menu').style.display  = 'none';
});

function toggleExportMenu(e) {
    e.stopPropagation();
    const menu = document.getElementById('export-menu');
    const btn  = document.getElementById('export-btn');
    if (menu.style.display === 'none' || menu.style.display === '') {
        menu.style.visibility = 'hidden';
        menu.style.display    = 'block';
        const mW = menu.offsetWidth  || 150;
        const mH = menu.offsetHeight || 100;
        menu.style.display    = 'none';
        menu.style.visibility = '';
        const rect = btn.getBoundingClientRect();
        let left = rect.left;
        if (left + mW > window.innerWidth - 10) left = window.innerWidth - mW - 10;
        let top = rect.bottom + 6;
        if (top + mH > window.innerHeight - 10) top = rect.top - mH - 6;
        menu.style.left    = left + 'px';
        menu.style.top     = top  + 'px';
        menu.style.display = 'block';
    } else {
        menu.style.display = 'none';
    }
}

document.getElementById('stroke-color').addEventListener('input', e => {
    currentColor = e.target.value;
    const selectedNodes = transformer.nodes();
    if (selectedNodes.length > 0) {
        selectedNodes.forEach(node => {
            if (node.className === 'Line' || node.className === 'Arrow') {
                node.stroke(currentColor);
                if (node.className === 'Arrow') node.fill(currentColor);
            } else if (node.className === 'Text') {
                node.fill(currentColor);
            } else {
                node.stroke(currentColor);
                if (node.fill()) node.fill(currentColor + '22');
            }
        });
        layer.batchDraw();
        markDirty();
    }
});

document.getElementById('stroke-slider').addEventListener('input', e => {
    setStrokeWidth(parseInt(e.target.value));
    applyStrokeToSelected(currentStroke);
});

document.getElementById('stroke-number').addEventListener('input', e => {
    const val = Math.max(1, Math.min(1000, parseInt(e.target.value) || 1));
    setStrokeWidth(val);
    applyStrokeToSelected(currentStroke);
});

document.getElementById('stroke-number').addEventListener('keydown', e => {
    e.stopPropagation();
});

function applyStrokeToSelected(val) {
    const selectedNodes = transformer.nodes();
    if (selectedNodes.length > 0) {
        selectedNodes.forEach(node => {
            if (node.className !== 'Text') {
                node.strokeWidth(val);
                if (node.className === 'Arrow') {
                    node.pointerLength(Math.max(10, val * 2.5));
                    node.pointerWidth(Math.max(8, val * 2));
                }
            }
        });
        layer.batchDraw();
        markDirty();
    }
}

setActiveTool('select');
setStrokeWidth(4);

// ── Space-bar temporary pan ───────────────────────────────────────────────────
let spaceHeld       = false;
let toolBeforeSpace = null;

document.addEventListener('keydown', e => {
    if (e.target.tagName === 'TEXTAREA' || e.target.tagName === 'INPUT') return;

    if (e.code === 'Space' && !spaceHeld) {
        e.preventDefault();
        spaceHeld = true;
        toolBeforeSpace = activeTool;
        stage.container().style.cursor = 'grab';
        return;
    }

    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'z' && !e.shiftKey) { e.preventDefault(); undoAction(); return; }
    if ((e.ctrlKey || e.metaKey) && (e.key.toLowerCase() === 'y' || (e.key.toLowerCase() === 'z' && e.shiftKey))) { e.preventDefault(); redoAction(); return; }
    if (e.ctrlKey || e.metaKey) return;

    switch(e.key.toLowerCase()) {
        case 'v': setActiveTool('select');  break;
        case 'h': setActiveTool('hand');    break;
        case 'd': setActiveTool('draw');    break;
        case 'x': setActiveTool('eraser');  break;
        case 'r': setActiveTool('rect');    break;
        case 'e': setActiveTool('ellipse'); break;
        case 'l': setActiveTool('line');    break;
        case 'a': setActiveTool('arrow');   break;
        case 't': setActiveTool('text');    break;
        case 'delete':
        case 'backspace':
            deleteSelected(); break;
    }
});

document.addEventListener('keyup', e => {
    if (e.code === 'Space' && spaceHeld) {
        spaceHeld = false;
        isPanning = false;
        const cursors = { select:'default', hand:'grab', draw:'crosshair', eraser:'crosshair',
                          line:'crosshair', arrow:'crosshair', rect:'crosshair', ellipse:'crosshair',
                          text:'text' };
        stage.container().style.cursor = cursors[toolBeforeSpace] || 'default';
        toolBeforeSpace = null;
    }
});

// ════════════════════════════════ PAN & ZOOM ════════════════════════════════
let isPanning = false;
let panStart  = { x: 0, y: 0 };

function shouldStartPan(e, nativeEvt) {
    if (spaceHeld)                                     return true;
    if (nativeEvt && nativeEvt.button === 1)           return true;
    if (activeTool === 'hand')                         return true;
    if (activeTool === 'select' && e.target === stage) return true;
    return false;
}

stage.on('mousedown', e => {
    if (!shouldStartPan(e, e.evt)) return;
    e.evt.preventDefault();
    isPanning = true;
    panStart  = stage.getPointerPosition();
    stage.container().style.cursor = 'grabbing';
});

stage.on('mousemove', () => {
    if (!isPanning) return;
    const pos = stage.getPointerPosition();
    if (!pos) return;
    stage.x(stage.x() + (pos.x - panStart.x));
    stage.y(stage.y() + (pos.y - panStart.y));
    panStart = pos;
    if (gridVisible) drawGrid();
    stage.batchDraw();
});

stage.on('mouseup mouseout', () => {
    if (!isPanning) return;
    isPanning = false;
    if (spaceHeld) {
        stage.container().style.cursor = 'grab';
    } else {
        const cursors = { select:'default', hand:'grab', draw:'crosshair', eraser:'crosshair',
                          line:'crosshair', arrow:'crosshair', rect:'crosshair', ellipse:'crosshair',
                          text:'text' };
        stage.container().style.cursor = cursors[activeTool] || 'default';
    }
});

stage.container().addEventListener('mousedown', ev => {
    if (ev.button === 1) ev.preventDefault();
});

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
    const s = Math.min(3, stage.scaleX() * 1.15);
    stage.scale({ x: s, y: s }); stage.batchDraw(); updateZoomDisplay();
}
function zoomOut() {
    const s = Math.max(0.2, stage.scaleX() / 1.15);
    stage.scale({ x: s, y: s }); stage.batchDraw(); updateZoomDisplay();
}
function resetView() {
    stage.position({ x: 0, y: 0 }); stage.scale({ x: 1, y: 1 });
    stage.batchDraw(); updateZoomDisplay();
}

// ════════════════════════════════ DRAWING STATE ════════════════════════════════
let isDrawing     = false;
let drawStart     = null;
let currentShape  = null;
let freePoints    = [];
let isErasing     = false;
let erasedSomething = false;
let lastErasePos  = null;

function getCanvasPoint() {
    const pointer = stage.getPointerPosition();
    return {
        x: (pointer.x - stage.x()) / stage.scaleX(),
        y: (pointer.y - stage.y()) / stage.scaleY(),
    };
}

// Helper for circle-segment intersection
function getLineCircleIntersections(p1, p2, c, r) {
    const dx = p2.x - p1.x;
    const dy = p2.y - p1.y;
    const a = dx * dx + dy * dy;
    const b = 2 * (dx * (p1.x - c.x) + dy * (p1.y - c.y));
    const cc = (p1.x - c.x) * (p1.x - c.x) + (p1.y - c.y) * (p1.y - c.y) - r * r;
    const det = b * b - 4 * a * cc;

    if (a <= 0.0000001 || det < 0) return [];
    if (det === 0) {
        const t = -b / (2 * a);
        if (t >= 0 && t <= 1) return [t];
        return [];
    }
    const t1 = (-b + Math.sqrt(det)) / (2 * a);
    const t2 = (-b - Math.sqrt(det)) / (2 * a);
    const tValues = [];
    if (t1 >= 0 && t1 <= 1) tValues.push(t1);
    if (t2 >= 0 && t2 <= 1) tValues.push(t2);
    return tValues.sort((a,b) => a-b);
}

// Slice a polyline exactly at the circle boundaries
function sliceLineByCircle(pts, cx, cy, r) {
    const newLines = [];
    let currentLine = [];
    
    for (let i = 0; i < pts.length - 2; i += 2) {
        const p1 = { x: pts[i], y: pts[i+1] };
        const p2 = { x: pts[i+2], y: pts[i+3] };
        
        const d1Sq = (p1.x - cx)**2 + (p1.y - cy)**2;
        const d2Sq = (p2.x - cx)**2 + (p2.y - cy)**2;
        const rSq = r * r;
        
        const p1In = d1Sq <= rSq;
        const p2In = d2Sq <= rSq;
        
        if (p1In && p2In) {
            if (currentLine.length > 0) { newLines.push(currentLine); currentLine = []; }
        } else if (!p1In && !p2In) {
            const ts = getLineCircleIntersections(p1, p2, {x:cx, y:cy}, r);
            if (ts.length === 2) {
                const ix1 = p1.x + ts[0]*(p2.x-p1.x);
                const iy1 = p1.y + ts[0]*(p2.y-p1.y);
                const ix2 = p1.x + ts[1]*(p2.x-p1.x);
                const iy2 = p1.y + ts[1]*(p2.y-p1.y);
                if (currentLine.length === 0) currentLine.push(p1.x, p1.y);
                currentLine.push(ix1, iy1);
                newLines.push(currentLine);
                currentLine = [ix2, iy2, p2.x, p2.y];
            } else {
                if (currentLine.length === 0) currentLine.push(p1.x, p1.y);
                currentLine.push(p2.x, p2.y);
            }
        } else if (!p1In && p2In) {
            const ts = getLineCircleIntersections(p1, p2, {x:cx, y:cy}, r);
            const t = ts.length > 0 ? ts[0] : 0.5;
            const ix = p1.x + t*(p2.x-p1.x);
            const iy = p1.y + t*(p2.y-p1.y);
            if (currentLine.length === 0) currentLine.push(p1.x, p1.y);
            currentLine.push(ix, iy);
            newLines.push(currentLine);
            currentLine = [];
        } else if (p1In && !p2In) {
            const ts = getLineCircleIntersections(p1, p2, {x:cx, y:cy}, r);
            const t = ts.length > 0 ? ts[ts.length-1] : 0.5;
            const ix = p1.x + t*(p2.x-p1.x);
            const iy = p1.y + t*(p2.y-p1.y);
            currentLine = [ix, iy, p2.x, p2.y];
        }
    }
    if (currentLine.length > 0) newLines.push(currentLine);
    return newLines;
}

function eraseAtExact(cx, cy, r) {
    const children = [...layer.getChildren()];
    let changed = false;

    children.forEach(shape => {
        if (shape === transformer) return;
        
        if (shape.className === 'Line') {
            const oldPts = shape.points();
            if (oldPts.length < 4) return;
            
            const newLines = sliceLineByCircle(oldPts, cx, cy, r);
            
            if (newLines.length === 0) {
                transformer.nodes([]);
                shape.destroy();
                changed = true;
            } else if (newLines.length > 1 || newLines[0].length < oldPts.length) {
                newLines.forEach(pts => {
                    if (pts.length >= 4) {
                        const newLine = shape.clone({ points: pts });
                        layer.add(newLine);
                        makeSelectable(newLine);
                    }
                });
                transformer.nodes([]);
                shape.destroy();
                changed = true;
            }
        } else {
            // Primitive shapes: multiple hit points to simulate area erase
            let hitShape = false;
            const offsets = [
                [0,0], [r,0], [-r,0], [0,r], [0,-r],
                [r*0.7,r*0.7], [-r*0.7,r*0.7], [r*0.7,-r*0.7], [-r*0.7,-r*0.7]
            ];
            for (let [dx, dy] of offsets) {
                const client = {
                    x: (cx + dx) * stage.scaleX() + stage.x(),
                    y: (cy + dy) * stage.scaleY() + stage.y(),
                };
                const hit = layer.getIntersection(client);
                if (hit && hit._id === shape._id && hit !== transformer) {
                    hitShape = true;
                    break;
                }
            }
            if (hitShape) {
                transformer.nodes([]);
                shape.destroy();
                changed = true;
            }
        }
    });
    
    if (changed) erasedSomething = true;
}

// ════════════════════════════════ MOUSEDOWN ════════════════════════════════
stage.on('mousedown touchstart', e => {
    if (activeTool === 'select') return;
    if (activeTool === 'hand')   return;
    if (activeTool === 'text') {
        if (e.target === stage) placeText(getCanvasPoint());
        return;
    }

    if (activeTool === 'eraser') {
        isErasing       = true;
        erasedSomething = false;
        lastErasePos    = getCanvasPoint();
        const r = Math.max(currentStroke * 1.5, 12);
        eraseAtExact(lastErasePos.x, lastErasePos.y, r);
        layer.batchDraw();
        return;
    }

    isDrawing  = true;
    drawStart  = getCanvasPoint();
    freePoints = [drawStart.x, drawStart.y];

    const sharedAttrs = {
        stroke: currentColor, strokeWidth: currentStroke,
        lineCap: 'round', lineJoin: 'round', draggable: false,
    };

    if (activeTool === 'draw') {
        currentShape = new Konva.Line({
            ...sharedAttrs,
            points: freePoints,
        });
    } else if (activeTool === 'rect') {
        currentShape = new Konva.Rect({
            ...sharedAttrs,
            x: drawStart.x, y: drawStart.y,
            width: 0, height: 0,
            fill: currentColor + '22', cornerRadius: 4,
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
            pointerLength: Math.max(10, currentStroke * 2.5),
            pointerWidth:  Math.max(8,  currentStroke * 2),
        });
    }

    if (currentShape) layer.add(currentShape);
});

// ════════════════════════════════ MOUSEMOVE ════════════════════════════════
stage.on('mousemove touchmove', () => {
    if (isErasing) {
        const pos = getCanvasPoint();
        const r = Math.max(currentStroke * 1.5, 12);
        const dist = Math.hypot(pos.x - lastErasePos.x, pos.y - lastErasePos.y);
        const steps = Math.max(1, Math.ceil(dist / (r / 2)));
        for (let i = 1; i <= steps; i++) {
            const t = i / steps;
            const cx = lastErasePos.x + (pos.x - lastErasePos.x) * t;
            const cy = lastErasePos.y + (pos.y - lastErasePos.y) * t;
            eraseAtExact(cx, cy, r);
        }
        lastErasePos = pos;
        layer.batchDraw();
        return;
    }

    if (!isDrawing || !currentShape) return;
    const pos = getCanvasPoint();
    if (activeTool === 'draw') {
        freePoints = freePoints.concat([pos.x, pos.y]);
        currentShape.points(freePoints);
    } else if (activeTool === 'rect') {
        currentShape.x(Math.min(pos.x, drawStart.x));
        currentShape.y(Math.min(pos.y, drawStart.y));
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
    if (isErasing) {
        isErasing = false;
        if (erasedSomething) {
            pushHistory();
            markDirty();
        }
        erasedSomething = false;
        return;
    }

    if (!isDrawing || !currentShape) return;
    isDrawing = false;
    const pts = currentShape.points ? currentShape.points() : [];
    if (activeTool === 'rect' && currentShape.width() < 3 && currentShape.height() < 3) {
        currentShape.destroy(); layer.batchDraw(); currentShape = null; return;
    }
    if ((activeTool === 'line' || activeTool === 'arrow') && pts.length >= 4) {
        const dx = pts[2] - pts[0], dy = pts[3] - pts[1];
        if (Math.abs(dx) < 3 && Math.abs(dy) < 3) {
            currentShape.destroy(); layer.batchDraw(); currentShape = null; return;
        }
    }
    makeSelectable(currentShape);
    currentShape = null;
    pushHistory();
    markDirty();
    layer.batchDraw();
});

// ════════════════════════════════ SELECTION ════════════════════════════════
function makeSelectable(shape) {
    shape.draggable(true);
    shape.on('click tap', e => {
        if (activeTool !== 'select') return;
        e.cancelBubble = true;
        selectShape(shape);
    });
    shape.on('dragend',      () => { markDirty(); pushHistory(); });
    shape.on('transformend', () => { markDirty(); pushHistory(); });
    if (shape.className === 'Text') {
        shape.on('dblclick dbltap', () => editText(shape));
    }
}

function selectShape(shape) {
    transformer.nodes([shape]);
    layer.batchDraw();
}

stage.on('click tap', e => {
    if (e.target === stage && activeTool === 'select') {
        transformer.nodes([]);
        layer.batchDraw();
    }
});

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

    textEditor.style.display  = 'block';
    textEditor.style.left     = (stageBox.left + absPos.x) + 'px';
    textEditor.style.top      = (stageBox.top  + absPos.y) + 'px';
    textEditor.style.fontSize = (node.fontSize() * scale) + 'px';
    textEditor.style.color    = node.fill();
    textEditor.value          = node.text();
    textEditor.style.width    = Math.max(140, node.width() * scale) + 'px';
    textEditor.style.height   = 'auto';
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
        if (editingTextNode) { editingTextNode.visible(true); layer.batchDraw(); }
        textEditor.style.display = 'none';
        editingTextNode = null;
    }
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); commitText(); }
    textEditor.style.height = 'auto';
    textEditor.style.height = textEditor.scrollHeight + 'px';
});

textEditor.addEventListener('blur', commitText);

// ════════════════════════════════ UNDO / REDO ════════════════════════════════
function snapshotLayer() {
    const trNodes = transformer.nodes();
    transformer.nodes([]);
    const json = stage.toJSON();
    transformer.nodes(trNodes);
    return json;
}

function pushHistory() {
    if (historyIndex < historyList.length - 1) {
        historyList = historyList.slice(0, historyIndex + 1);
    }
    historyList.push(snapshotLayer());
    if (historyList.length > 60) historyList.shift();
    historyIndex = historyList.length - 1;
    updateUndoRedoBtns();
}

function updateUndoRedoBtns() {
    const undoBtn = document.getElementById('undo-btn');
    const redoBtn = document.getElementById('redo-btn');
    if (undoBtn) undoBtn.style.opacity = historyIndex > 0 ? '1' : '0.35';
    if (redoBtn) redoBtn.style.opacity = historyIndex < historyList.length - 1 ? '1' : '0.35';
}

function restoreSnapshot(json) {
    const shapes = [...layer.getChildren()];
    shapes.forEach(s => { if (s !== transformer) s.destroy(); });
    const tempDiv = document.createElement('div');
    tempDiv.style.cssText = 'position:absolute;left:-9999px;top:-9999px;width:1px;height:1px;visibility:hidden;';
    document.body.appendChild(tempDiv);
    const restoredStage = Konva.Stage.create(json, tempDiv);
    restoredStage.getLayers().forEach(restoredLayer => {
        [...restoredLayer.getChildren()].forEach(shape => {
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
    if (historyIndex > 0) {
        historyIndex--;
        restoreSnapshot(historyList[historyIndex]);
        updateUndoRedoBtns();
        markDirty();
    }
}

function redoAction() {
    if (historyIndex < historyList.length - 1) {
        historyIndex++;
        restoreSnapshot(historyList[historyIndex]);
        updateUndoRedoBtns();
        markDirty();
    }
}

// ════════════════════════════════ GRID ════════════════════════════════
function drawGrid() {
    gridLayer.destroyChildren();
    const w  = stage.width()  / stage.scaleX();
    const h  = stage.height() / stage.scaleY();
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
        btn.classList.add('active');
    } else {
        gridLayer.destroyChildren();
        gridLayer.batchDraw();
        btn.classList.remove('active');
    }
}

stage.on('dragmove wheel', () => { if (gridVisible) drawGrid(); });

// ════════════════════════════════ EXPORT ════════════════════════════════
function exportCanvas(format) {
    document.getElementById('export-menu').style.display = 'none';
    const trNodes = transformer.nodes();
    transformer.nodes([]);
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
    const pill = document.getElementById('save-pill');
    const icon = document.getElementById('save-status-icon');
    const text = document.getElementById('save-status-text');
    if (state === 'saving') {
        pill.style.background = '#fffbeb'; pill.style.color = '#f59e0b';
        icon.innerHTML = '<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="animation:spin 1s linear infinite"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>';
        text.textContent = 'Saving…';
    } else if (state === 'saved') {
        pill.style.background = '#f0fdf4'; pill.style.color = '#10b981';
        icon.innerHTML = '<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M20 6 9 17l-5-5"/></svg>';
        text.textContent = 'Saved';
    } else if (state === 'unsaved') {
        pill.style.background = '#fffbeb'; pill.style.color = '#f59e0b';
        icon.innerHTML = '<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';
        text.textContent = 'Unsaved';
    } else if (state === 'error') {
        pill.style.background = '#fff1f2'; pill.style.color = '#ef4444';
        icon.innerHTML = '<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';
        text.textContent = 'Error';
    }
}

async function saveBoard() {
    updateSaveStatus('saving');
    const trNodes = transformer.nodes();
    transformer.nodes([]);
    const json = stage.toJSON();
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
        showToast('Board saved!', 'success');
    } catch (err) {
        console.error('Save failed:', err);
        updateSaveStatus('error');
        showToast('Save failed. Please try again.', 'error');
    }
}

setInterval(() => { if (isDirty) saveBoard(); }, 60000);

// ════════════════════════════════ LOAD SAVED STATE (AC-24) ════════════════════════════════
(function loadCanvasState() {
    const canvasData = @json($board->canvas_data);
    if (!canvasData) return;
    try {
        const tempDiv = document.createElement('div');
        tempDiv.style.cssText = 'position:absolute;left:-9999px;top:-9999px;width:1px;height:1px;visibility:hidden;';
        document.body.appendChild(tempDiv);
        const restoredStage = Konva.Stage.create(canvasData, tempDiv);
        restoredStage.getLayers().forEach(restoredLayer => {
            [...restoredLayer.getChildren()].forEach(shape => {
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
        restoredStage.destroy();
        document.body.removeChild(tempDiv);
        layer.add(transformer);
        layer.batchDraw();
        updateSaveStatus('saved');
    } catch (e) {
        console.error('Failed to restore canvas state:', e);
    }
})();

// Capture initial state in history
pushHistory();

// CSS spin animation
const styleEl = document.createElement('style');
styleEl.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
document.head.appendChild(styleEl);
</script>
@endsection
