@extends('layouts.app')

@section('content')
<div class="w-full flex-grow overflow-y-auto" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); min-height:100vh;">
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div style="width:40px;height:40px;background:#6366f1;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                    <i data-lucide="layout-dashboard" style="width:20px;height:20px;color:#fff;"></i>
                </div>
                <h1 class="font-display text-4xl font-extrabold tracking-tight text-slate-900">My Whiteboards</h1>
            </div>
            <p class="text-slate-500 text-base ml-[52px]">{{ $boards->count() }} {{ Str::plural('board', $boards->count()) }} — draw, sketch, and create freely</p>
        </div>
        <a href="{{ route('home') }}"
            style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;background:rgba(255,255,255,0.8);color:#475569;border:1.5px solid #e2e8f0;border-radius:14px;font-size:13px;font-weight:600;cursor:pointer;text-decoration:none;backdrop-filter:blur(8px);transition:all 0.2s;font-family:'Inter',sans-serif;"
            onmouseover="this.style.borderColor='#6366f1';this.style.color='#6366f1';this.style.background='#eef2ff'"
            onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#475569';this.style.background='rgba(255,255,255,0.8)'">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
            </svg>
            Home
        </a>
    </div>

    <!-- Grid Layout -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

        <!-- New Board Dotted Card -->
        <div onclick="openModal('new-board-modal')" class="group" style="min-height:220px;border:2px dashed #cbd5e1;border-radius:20px;padding:24px;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;background:rgba(255,255,255,0.5);cursor:pointer;transition:all 0.2s;"
            onmouseover="this.style.borderColor='#6366f1';this.style.background='rgba(238,242,255,0.8)'"
            onmouseout="this.style.borderColor='#cbd5e1';this.style.background='rgba(255,255,255,0.5)'">
            <div style="width:52px;height:52px;background:#f1f5f9;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:12px;transition:background 0.2s;">
                <i data-lucide="plus" style="width:24px;height:24px;color:#94a3b8;"></i>
            </div>
            <span style="font-size:15px;font-weight:700;color:#64748b;font-family:'Outfit',sans-serif;">Create New Board</span>
            <p style="font-size:12px;color:#94a3b8;margin-top:4px;">Start with a blank canvas</p>
        </div>

        <!-- Saved Boards -->
        @foreach ($boards as $board)
        <div style="min-height:220px;background:#fff;border-radius:20px;border:1px solid rgba(0,0,0,0.06);box-shadow:0 1px 4px rgba(0,0,0,0.05);display:flex;flex-direction:column;justify-content:space-between;overflow:hidden;transition:all 0.2s;"
            onmouseover="this.style.boxShadow='0 8px 24px rgba(0,0,0,0.10)';this.style.transform='translateY(-2px)'"
            onmouseout="this.style.boxShadow='0 1px 4px rgba(0,0,0,0.05)';this.style.transform='translateY(0)'">

            <!-- Preview area (gradient placeholder) -->
            <div style="height:80px;background:linear-gradient(135deg,{{ ['#eef2ff,#ddd6fe','#ecfdf5,#a7f3d0','#fff7ed,#fed7aa','#fdf2f8,#fbcfe8','#f0f9ff,#bae6fd'][($board->id - 1) % 5] }});border-bottom:1px solid rgba(0,0,0,0.04);"></div>

            <div style="padding:16px 20px;">
                <h3 style="font-size:16px;font-weight:700;color:#1e293b;font-family:'Outfit',sans-serif;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:4px;">{{ $board->name }}</h3>
                <p style="font-size:11px;color:#94a3b8;font-weight:500;">
                    Updated {{ $board->updated_at->diffForHumans() }}
                </p>
            </div>

            <!-- Action Footer -->
            <div style="padding:12px 20px;background:#f8fafc;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
                <a href="{{ route('boards.show', $board->id) }}"
                    style="display:inline-flex;align-items:center;gap:5px;font-size:13px;font-weight:600;color:#6366f1;text-decoration:none;padding:6px 12px;border-radius:8px;background:#eef2ff;transition:background 0.15s;"
                    onmouseover="this.style.background='#e0e7ff'" onmouseout="this.style.background='#eef2ff'">
                    <i data-lucide="external-link" style="width:13px;height:13px;"></i> Open
                </a>
                <div style="display:flex;gap:4px;">
                    <button title="Rename" onclick="openRenameModal({{ $board->id }}, '{{ addslashes($board->name) }}')"
                        style="width:32px;height:32px;border:none;background:transparent;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#94a3b8;transition:all 0.15s;"
                        onmouseover="this.style.background='#f1f5f9';this.style.color='#6366f1'"
                        onmouseout="this.style.background='transparent';this.style.color='#94a3b8'">
                        <i data-lucide="pencil" style="width:14px;height:14px;"></i>
                    </button>
                    <button title="Delete" onclick="openDeleteModal({{ $board->id }}, '{{ addslashes($board->name) }}')"
                        style="width:32px;height:32px;border:none;background:transparent;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#94a3b8;transition:all 0.15s;"
                        onmouseover="this.style.background='#fff1f2';this.style.color='#ef4444'"
                        onmouseout="this.style.background='transparent';this.style.color='#94a3b8'">
                        <i data-lucide="trash-2" style="width:14px;height:14px;"></i>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
</div>

<!-- ════════ NEW BOARD MODAL ════════ -->
<div id="new-board-modal" class="modal-overlay" style="display:none;position:fixed;inset:0;z-index:500;background:rgba(15,23,42,0.45);backdrop-filter:blur(6px);align-items:center;justify-content:center;padding:16px;opacity:0;transition:opacity 0.2s;">
    <div class="modal-card" style="background:#fff;border-radius:20px;width:100%;max-width:440px;box-shadow:0 24px 64px rgba(0,0,0,0.16);transform:scale(0.95);transition:transform 0.2s;overflow:hidden;border:1px solid rgba(0,0,0,0.06);">
        <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
            <h2 style="font-size:18px;font-weight:800;color:#1e293b;font-family:'Outfit',sans-serif;">New Whiteboard</h2>
            <button onclick="closeModal('new-board-modal')" style="border:none;background:transparent;cursor:pointer;color:#94a3b8;padding:4px;border-radius:8px;display:flex;">
                <i data-lucide="x" style="width:18px;height:18px;"></i>
            </button>
        </div>
        <div style="padding:24px;">
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:8px;">Board Name</label>
            <input type="text" id="new_board_name" placeholder="Project Ideas, Wireframes…"
                style="width:100%;padding:12px 16px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px;font-size:14px;color:#1e293b;font-weight:500;outline:none;transition:all 0.2s;box-sizing:border-box;"
                onfocus="this.style.borderColor='#6366f1';this.style.background='#fff'"
                onblur="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc'">
            <p id="new-board-error" style="display:none;color:#ef4444;font-size:12px;margin-top:8px;font-weight:500;"></p>
        </div>
        <div style="padding:16px 24px 24px;display:flex;justify-content:flex-end;gap:10px;">
            <button onclick="closeModal('new-board-modal')" style="padding:10px 18px;border:none;background:transparent;color:#64748b;font-weight:600;font-size:13px;cursor:pointer;border-radius:10px;">Cancel</button>
            <button onclick="createBoard()" style="padding:10px 20px;background:#6366f1;color:#fff;border:none;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;transition:background 0.15s;font-family:'Inter',sans-serif;"
                onmouseover="this.style.background='#4f46e5'" onmouseout="this.style.background='#6366f1'">Create Board</button>
        </div>
    </div>
</div>

<!-- ════════ RENAME BOARD MODAL ════════ -->
<div id="rename-board-modal" class="modal-overlay" style="display:none;position:fixed;inset:0;z-index:500;background:rgba(15,23,42,0.45);backdrop-filter:blur(6px);align-items:center;justify-content:center;padding:16px;opacity:0;transition:opacity 0.2s;">
    <div class="modal-card" style="background:#fff;border-radius:20px;width:100%;max-width:440px;box-shadow:0 24px 64px rgba(0,0,0,0.16);transform:scale(0.95);transition:transform 0.2s;overflow:hidden;border:1px solid rgba(0,0,0,0.06);">
        <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
            <h2 style="font-size:18px;font-weight:800;color:#1e293b;font-family:'Outfit',sans-serif;">Rename Whiteboard</h2>
            <button onclick="closeModal('rename-board-modal')" style="border:none;background:transparent;cursor:pointer;color:#94a3b8;padding:4px;border-radius:8px;display:flex;">
                <i data-lucide="x" style="width:18px;height:18px;"></i>
            </button>
        </div>
        <div style="padding:24px;">
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:8px;">New Name</label>
            <input type="text" id="rename_board_name"
                style="width:100%;padding:12px 16px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px;font-size:14px;color:#1e293b;font-weight:500;outline:none;transition:all 0.2s;box-sizing:border-box;"
                onfocus="this.style.borderColor='#6366f1';this.style.background='#fff'"
                onblur="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc'">
            <p id="rename-board-error" style="display:none;color:#ef4444;font-size:12px;margin-top:8px;font-weight:500;"></p>
        </div>
        <div style="padding:16px 24px 24px;display:flex;justify-content:flex-end;gap:10px;">
            <button onclick="closeModal('rename-board-modal')" style="padding:10px 18px;border:none;background:transparent;color:#64748b;font-weight:600;font-size:13px;cursor:pointer;border-radius:10px;">Cancel</button>
            <button onclick="renameBoard()" style="padding:10px 20px;background:#6366f1;color:#fff;border:none;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;transition:background 0.15s;font-family:'Inter',sans-serif;"
                onmouseover="this.style.background='#4f46e5'" onmouseout="this.style.background='#6366f1'">Save Name</button>
        </div>
    </div>
</div>

<!-- ════════ DELETE BOARD MODAL ════════ -->
<div id="delete-board-modal" class="modal-overlay" style="display:none;position:fixed;inset:0;z-index:500;background:rgba(15,23,42,0.45);backdrop-filter:blur(6px);align-items:center;justify-content:center;padding:16px;opacity:0;transition:opacity 0.2s;">
    <div class="modal-card" style="background:#fff;border-radius:20px;width:100%;max-width:440px;box-shadow:0 24px 64px rgba(0,0,0,0.16);transform:scale(0.95);transition:transform 0.2s;overflow:hidden;border:1px solid rgba(0,0,0,0.06);">
        <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
            <h2 style="font-size:18px;font-weight:800;color:#1e293b;font-family:'Outfit',sans-serif;">Delete Whiteboard</h2>
            <button onclick="closeModal('delete-board-modal')" style="border:none;background:transparent;cursor:pointer;color:#94a3b8;padding:4px;border-radius:8px;display:flex;">
                <i data-lucide="x" style="width:18px;height:18px;"></i>
            </button>
        </div>
        <div style="padding:24px;">
            <div style="display:flex;gap:12px;align-items:flex-start;">
                <div style="width:40px;height:40px;min-width:40px;background:#fff1f2;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                    <i data-lucide="triangle-alert" style="width:18px;height:18px;color:#ef4444;"></i>
                </div>
                <p style="color:#475569;font-size:14px;line-height:1.6;">Are you sure you want to delete <strong id="delete-board-name-display" style="color:#1e293b;"></strong>? This will permanently remove all drawing data and cannot be undone.</p>
            </div>
        </div>
        <div style="padding:16px 24px 24px;display:flex;justify-content:flex-end;gap:10px;">
            <button onclick="closeModal('delete-board-modal')" style="padding:10px 18px;border:none;background:transparent;color:#64748b;font-weight:600;font-size:13px;cursor:pointer;border-radius:10px;">Cancel</button>
            <button onclick="deleteBoard()" style="padding:10px 20px;background:#ef4444;color:#fff;border:none;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;transition:background 0.15s;font-family:'Inter',sans-serif;"
                onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='#ef4444'">Delete Board</button>
        </div>
    </div>
</div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
let activeBoardId = null;

// ────────── Modal helpers ──────────
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

// ────────── Create board ──────────
async function createBoard() {
    const nameInput = document.getElementById('new_board_name');
    const errEl     = document.getElementById('new-board-error');
    const name      = nameInput.value.trim();

    errEl.style.display = 'none';
    if (!name) { errEl.textContent = 'Please enter a board name.'; errEl.style.display = 'block'; return; }

    try {
        const res = await fetch('/api/boards', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ name }),
        });
        const data = await res.json();
        if (!res.ok) {
            const msg = data.errors?.name?.[0] || data.message || 'Failed to create board.';
            errEl.textContent = msg;
            errEl.style.display = 'block';
            return;
        }
        window.location.href = `/boards/${data.id}`;
    } catch (e) {
        errEl.textContent = 'An error occurred. Please try again.';
        errEl.style.display = 'block';
    }
}

document.getElementById('new_board_name').addEventListener('keydown', e => {
    if (e.key === 'Enter') createBoard();
});

// ────────── Rename board ──────────
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
        const res = await fetch(`/api/boards/${activeBoardId}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ name }),
        });
        const data = await res.json();
        if (!res.ok) {
            const msg = data.errors?.name?.[0] || data.message || 'Failed to rename board.';
            errEl.textContent = msg;
            errEl.style.display = 'block';
            return;
        }
        closeModal('rename-board-modal');
        showToast('Board renamed successfully!', 'success');
        setTimeout(() => window.location.reload(), 600);
    } catch (e) {
        errEl.textContent = 'An error occurred. Please try again.';
        errEl.style.display = 'block';
    }
}

document.getElementById('rename_board_name').addEventListener('keydown', e => {
    if (e.key === 'Enter') renameBoard();
});

// ────────── Delete board ──────────
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
        if (!res.ok) throw new Error('Delete failed');
        closeModal('delete-board-modal');
        showToast('Board deleted.', 'success');
        setTimeout(() => window.location.reload(), 600);
    } catch (e) {
        showToast('Failed to delete board.', 'error');
    }
}
</script>
@endsection
