@extends('layouts.app')

@section('content')
<style>
/* ─── page base ─── */
.boards-page {
    min-height: 100vh;
    background: #f6f7fb;
    font-family: 'Inter', sans-serif;
}

/* ─── topbar ─── */
.boards-topbar {
    position: sticky; top: 0; z-index: 50;
    background: rgba(255,255,255,0.88);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(0,0,0,0.06);
    padding: 0 32px;
    height: 60px;
    display: flex; align-items: center; justify-content: space-between;
}
.topbar-brand {
    display: flex; align-items: center; gap: 10px;
    font-family: 'Outfit', sans-serif;
    font-size: 18px; font-weight: 800; color: #1e293b;
    text-decoration: none;
}
.topbar-brand-icon {
    width: 32px; height: 32px;
    background: linear-gradient(135deg,#6366f1,#8b5cf6);
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
}
.topbar-actions { display: flex; align-items: center; gap: 8px; }
.btn-home {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px;
    background: transparent; color: #64748b;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px; font-size: 13px; font-weight: 600;
    cursor: pointer; text-decoration: none;
    transition: all 0.18s;
}
.btn-home:hover { border-color: #6366f1; color: #6366f1; background: #eef2ff; }

/* ─── hero strip ─── */
.boards-hero {
    padding: 48px 32px 36px;
    max-width: 1200px; margin: 0 auto;
    display: flex; align-items: flex-end; justify-content: space-between; gap: 16px;
    flex-wrap: wrap;
}
.hero-text h1 {
    font-family: 'Outfit', sans-serif;
    font-size: clamp(26px,4vw,38px);
    font-weight: 900; color: #0f172a;
    letter-spacing: -0.03em; margin: 0 0 6px;
}
.hero-text p { font-size: 14px; color: #64748b; margin: 0; }
.hero-count {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 12px;
    background: #eef2ff; border-radius: 100px;
    font-size: 12px; font-weight: 700; color: #6366f1;
    margin-top: 10px;
}

/* ─── grid ─── */
.boards-grid-wrap {
    max-width: 1200px; margin: 0 auto;
    padding: 0 32px 60px;
}
.boards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

/* ─── create card ─── */
.create-card {
    min-height: 230px;
    border: 2px dashed #c7d2fe;
    border-radius: 20px;
    background: rgba(238,242,255,0.35);
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    text-align: center; cursor: pointer;
    transition: all 0.2s;
    padding: 24px;
}
.create-card:hover {
    border-color: #6366f1;
    background: rgba(238,242,255,0.8);
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(99,102,241,0.12);
}
.create-icon-ring {
    width: 56px; height: 56px;
    background: #eef2ff;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 14px;
    transition: background 0.2s;
}
.create-card:hover .create-icon-ring { background: #6366f1; }
.create-card:hover .create-icon-ring svg { color: #fff !important; }
.create-label {
    font-family: 'Outfit', sans-serif;
    font-size: 15px; font-weight: 700; color: #475569;
    margin-bottom: 4px;
}
.create-sub { font-size: 12px; color: #94a3b8; }

/* ─── board card ─── */
.board-card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid rgba(0,0,0,0.06);
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    overflow: hidden;
    display: flex; flex-direction: column;
    transition: all 0.22s;
    cursor: pointer;
}
.board-card:hover {
    box-shadow: 0 12px 36px rgba(0,0,0,0.10);
    transform: translateY(-3px);
    border-color: rgba(99,102,241,0.15);
}
.board-preview {
    height: 96px;
    position: relative;
    overflow: hidden;
}
.board-preview-inner {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
}
.preview-icon {
    opacity: 0.18;
}
.board-body { padding: 16px 18px 12px; flex: 1; }
.board-name {
    font-family: 'Outfit', sans-serif;
    font-size: 16px; font-weight: 700; color: #0f172a;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    margin-bottom: 4px;
}
.board-meta { font-size: 11px; color: #94a3b8; font-weight: 500; }
.board-footer {
    padding: 10px 18px 14px;
    display: flex; align-items: center; justify-content: space-between;
    border-top: 1px solid #f1f5f9;
}
.btn-open {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 12px; font-weight: 700; color: #6366f1;
    text-decoration: none;
    padding: 6px 12px;
    background: #eef2ff; border-radius: 8px;
    transition: all 0.15s;
}
.btn-open:hover { background: #e0e7ff; }
.board-actions { display: flex; gap: 4px; }
.action-btn {
    width: 30px; height: 30px;
    border: none; background: transparent;
    border-radius: 8px; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #94a3b8; transition: all 0.15s;
}
.action-btn:hover { background: #f1f5f9; color: #475569; }
.action-btn.danger:hover { background: #fff1f2; color: #ef4444; }

/* ─── modals ─── */
.modal-overlay {
    display: none; position: fixed; inset: 0; z-index: 500;
    background: rgba(15,23,42,0.5);
    backdrop-filter: blur(6px);
    align-items: center; justify-content: center;
    padding: 16px; opacity: 0;
    transition: opacity 0.2s;
}
.modal-card {
    background: #fff; border-radius: 22px;
    width: 100%; max-width: 440px;
    box-shadow: 0 32px 80px rgba(0,0,0,0.18);
    transform: scale(0.95); transition: transform 0.2s;
    overflow: hidden; border: 1px solid rgba(0,0,0,0.05);
}
.modal-header {
    padding: 20px 24px 16px;
    border-bottom: 1px solid #f1f5f9;
    display: flex; align-items: center; justify-content: space-between;
}
.modal-header h2 {
    font-family: 'Outfit', sans-serif;
    font-size: 18px; font-weight: 800; color: #1e293b; margin: 0;
}
.modal-close {
    border: none; background: transparent; cursor: pointer;
    color: #94a3b8; padding: 4px; border-radius: 8px;
    display: flex; transition: color 0.15s;
}
.modal-close:hover { color: #475569; }
.modal-body { padding: 22px 24px; }
.modal-footer { padding: 0 24px 22px; display: flex; justify-content: flex-end; gap: 8px; }
.form-label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 8px; }
.form-input {
    width: 100%; padding: 11px 14px;
    background: #f8fafc; border: 1.5px solid #e2e8f0;
    border-radius: 12px; font-size: 14px; color: #1e293b; font-weight: 500;
    outline: none; transition: all 0.2s; box-sizing: border-box;
    font-family: 'Inter', sans-serif;
}
.form-input:focus { border-color: #6366f1; background: #fff; }
.form-error { display: none; color: #ef4444; font-size: 12px; margin-top: 6px; font-weight: 500; }
.btn-cancel {
    padding: 10px 18px; border: none; background: #f1f5f9;
    color: #64748b; font-weight: 600; font-size: 13px;
    cursor: pointer; border-radius: 10px; transition: background 0.15s;
}
.btn-cancel:hover { background: #e2e8f0; }
.btn-primary {
    padding: 10px 20px; background: #6366f1; color: #fff;
    border: none; border-radius: 10px; font-size: 13px;
    font-weight: 700; cursor: pointer; transition: background 0.15s;
    font-family: 'Inter', sans-serif;
}
.btn-primary:hover { background: #4f46e5; }
.btn-danger {
    padding: 10px 20px; background: #ef4444; color: #fff;
    border: none; border-radius: 10px; font-size: 13px;
    font-weight: 700; cursor: pointer; transition: background 0.15s;
}
.btn-danger:hover { background: #dc2626; }
</style>

<div class="boards-page">

    {{-- ── Top bar ── --}}
    <nav class="boards-topbar">
        <a href="{{ route('boards.index') }}" class="topbar-brand">
            <div class="topbar-brand-icon">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                    <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                </svg>
            </div>
            Whiteboard Canvas
        </a>
        <div class="topbar-actions">
            <a href="{{ route('home') }}" class="btn-home">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                Home
            </a>
        </div>
    </nav>

    {{-- ── Hero ── --}}
    <div class="boards-hero">
        <div class="hero-text">
            <h1>My Whiteboards</h1>
            <p>Draw, sketch, and create freely on infinite canvases.</p>
            <span class="hero-count">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                {{ $boards->count() }} {{ Str::plural('board', $boards->count()) }}
            </span>
        </div>
    </div>

    {{-- ── Grid ── --}}
    <div class="boards-grid-wrap">
        <div class="boards-grid">

            {{-- Create card --}}
            <div class="create-card" onclick="openModal('new-board-modal')">
                <div class="create-icon-ring">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2.5" stroke-linecap="round">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                </div>
                <div class="create-label">New Whiteboard</div>
                <div class="create-sub">Start with a blank canvas</div>
            </div>

            {{-- Boards --}}
            @php
            $gradients = [
                ['from'=>'#eef2ff','to'=>'#ddd6fe','icon'=>'#6366f1'],
                ['from'=>'#ecfdf5','to'=>'#a7f3d0','icon'=>'#10b981'],
                ['from'=>'#fff7ed','to'=>'#fed7aa','icon'=>'#f59e0b'],
                ['from'=>'#fdf2f8','to'=>'#fbcfe8','icon'=>'#ec4899'],
                ['from'=>'#f0f9ff','to'=>'#bae6fd','icon'=>'#0ea5e9'],
                ['from'=>'#f0fdf4','to'=>'#bbf7d0','icon'=>'#22c55e'],
            ];
            @endphp

            @foreach ($boards as $board)
            @php $g = $gradients[($board->id - 1) % count($gradients)]; @endphp
            <div class="board-card" onclick="window.location='{{ route('boards.show', $board->id) }}'">
                <div class="board-preview" style="background: linear-gradient(135deg, {{ $g['from'] }}, {{ $g['to'] }});">
                    <div class="board-preview-inner">
                        <svg class="preview-icon" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="{{ $g['icon'] }}" stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>
                        </svg>
                    </div>
                </div>
                <div class="board-body">
                    <div class="board-name">{{ $board->name }}</div>
                    <div class="board-meta">Updated {{ $board->updated_at->diffForHumans() }}</div>
                </div>
                <div class="board-footer">
                    <a href="{{ route('boards.show', $board->id) }}" class="btn-open" onclick="event.stopPropagation()">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        Open
                    </a>
                    <div class="board-actions" onclick="event.stopPropagation()">
                        <button class="action-btn" title="Rename" onclick="openRenameModal({{ $board->id }}, '{{ addslashes($board->name) }}')">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <button class="action-btn danger" title="Delete" onclick="openDeleteModal({{ $board->id }}, '{{ addslashes($board->name) }}')">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>

{{-- ── NEW BOARD MODAL ── --}}
<div id="new-board-modal" class="modal-overlay">
    <div class="modal-card">
        <div class="modal-header">
            <h2>New Whiteboard</h2>
            <button class="modal-close" onclick="closeModal('new-board-modal')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <label class="form-label">Board Name</label>
            <input type="text" id="new_board_name" class="form-input" placeholder="Project Ideas, Wireframes…">
            <p id="new-board-error" class="form-error"></p>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeModal('new-board-modal')">Cancel</button>
            <button class="btn-primary" onclick="createBoard()">Create Board</button>
        </div>
    </div>
</div>

{{-- ── RENAME MODAL ── --}}
<div id="rename-board-modal" class="modal-overlay">
    <div class="modal-card">
        <div class="modal-header">
            <h2>Rename Whiteboard</h2>
            <button class="modal-close" onclick="closeModal('rename-board-modal')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <label class="form-label">New Name</label>
            <input type="text" id="rename_board_name" class="form-input">
            <p id="rename-board-error" class="form-error"></p>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeModal('rename-board-modal')">Cancel</button>
            <button class="btn-primary" onclick="renameBoard()">Save Name</button>
        </div>
    </div>
</div>

{{-- ── DELETE MODAL ── --}}
<div id="delete-board-modal" class="modal-overlay">
    <div class="modal-card">
        <div class="modal-header">
            <h2>Delete Whiteboard</h2>
            <button class="modal-close" onclick="closeModal('delete-board-modal')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <div style="display:flex;gap:14px;align-items:flex-start;">
                <div style="width:42px;height:42px;min-width:42px;background:#fff1f2;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </div>
                <p style="color:#475569;font-size:14px;line-height:1.65;margin:0;">
                    Are you sure you want to delete <strong id="delete-board-name-display" style="color:#1e293b;"></strong>?
                    This permanently removes all drawing data.
                </p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeModal('delete-board-modal')">Cancel</button>
            <button class="btn-danger" onclick="deleteBoard()">Delete Board</button>
        </div>
    </div>
</div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
let activeBoardId = null;

function openModal(id) {
    const el = document.getElementById(id);
    el.style.display = 'flex';
    setTimeout(() => {
        el.style.opacity = '1';
        el.querySelector('.modal-card').style.transform = 'scale(1)';
    }, 10);
}

function closeModal(id) {
    const el = document.getElementById(id);
    el.style.opacity = '0';
    el.querySelector('.modal-card').style.transform = 'scale(0.95)';
    setTimeout(() => { el.style.display = 'none'; }, 200);
}

document.querySelectorAll('.modal-overlay').forEach(el => {
    el.addEventListener('click', e => { if (e.target === el) closeModal(el.id); });
});

async function createBoard() {
    const nameInput = document.getElementById('new_board_name');
    const errEl     = document.getElementById('new-board-error');
    const name      = nameInput.value.trim();
    errEl.style.display = 'none';
    if (!name) { errEl.textContent = 'Please enter a board name.'; errEl.style.display = 'block'; return; }
    try {
        const res  = await fetch('/api/boards', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ name }),
        });
        const data = await res.json();
        if (!res.ok) { errEl.textContent = data.errors?.name?.[0] || data.message || 'Failed.'; errEl.style.display = 'block'; return; }
        window.location.href = `/boards/${data.id}`;
    } catch (e) { errEl.textContent = 'An error occurred. Please try again.'; errEl.style.display = 'block'; }
}
document.getElementById('new_board_name').addEventListener('keydown', e => { if (e.key === 'Enter') createBoard(); });

function openRenameModal(id, currentName) {
    activeBoardId = id;
    document.getElementById('rename_board_name').value = currentName;
    document.getElementById('rename-board-error').style.display = 'none';
    openModal('rename-board-modal');
    setTimeout(() => document.getElementById('rename_board_name').focus(), 250);
}

async function renameBoard() {
    const nameInput = document.getElementById('rename_board_name');
    const errEl     = document.getElementById('rename-board-error');
    const name      = nameInput.value.trim();
    errEl.style.display = 'none';
    if (!name) { errEl.textContent = 'Board name cannot be empty.'; errEl.style.display = 'block'; return; }
    try {
        const res  = await fetch(`/api/boards/${activeBoardId}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ name }),
        });
        const data = await res.json();
        if (!res.ok) { errEl.textContent = data.errors?.name?.[0] || data.message || 'Failed.'; errEl.style.display = 'block'; return; }
        closeModal('rename-board-modal');
        showToast('Board renamed!', 'success');
        setTimeout(() => window.location.reload(), 600);
    } catch (e) { errEl.textContent = 'An error occurred.'; errEl.style.display = 'block'; }
}
document.getElementById('rename_board_name').addEventListener('keydown', e => { if (e.key === 'Enter') renameBoard(); });

function openDeleteModal(id, name) {
    activeBoardId = id;
    document.getElementById('delete-board-name-display').textContent = `"${name}"`;
    openModal('delete-board-modal');
}

async function deleteBoard() {
    try {
        const res = await fetch(`/api/boards/${activeBoardId}`, {
            method: 'DELETE',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
        });
        if (!res.ok) throw new Error();
        closeModal('delete-board-modal');
        showToast('Board deleted.', 'success');
        setTimeout(() => window.location.reload(), 600);
    } catch (e) { showToast('Failed to delete board.', 'error'); }
}
</script>
@endsection
