@extends('layouts.app')

@section('title', 'Activity Log')
@section('page-title', 'Activity Log')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Activity Log</h3>
    </div>
</div>

<div class="page-content">
    <!-- Filter Card -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Aksi</label>
                    <select name="action" class="form-select">
                        <option value="">Semua</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                {{ ucfirst($action) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tipe Model</label>
                    <select name="model_type" class="form-select">
                        <option value="">Semua</option>
                        @foreach($modelTypes as $type)
                            <option value="{{ $type }}" {{ request('model_type') == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="15%">Waktu</th>
                            <th width="15%">User</th>
                            <th width="10%">Aksi</th>
                            <th width="15%">Model</th>
                            <th>Deskripsi</th>
                            <th width="5%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>
                                    <small class="text-muted">
                                        {{ $log->created_at->format('d M Y') }}<br>
                                        {{ $log->created_at->format('H:i:s') }}
                                    </small>
                                </td>
                                <td>
                                    @if($log->user)
                                        <strong>{{ $log->user->name }}</strong>
                                        <br><small class="text-muted">{{ $log->user->role }}</small>
                                    @else
                                        <span class="text-muted">System</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $log->action_color }}">
                                        {{ $log->action_label }}
                                    </span>
                                </td>
                                <td>
                                    <strong>{{ $log->model_type }}</strong>
                                    @if($log->model_label)
                                        <br><small class="text-muted">{{ Str::limit($log->model_label, 30) }}</small>
                                    @endif
                                </td>
                                <td>
                                    {{ $log->description ?? '-' }}
                                </td>
                                <td>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#detailModal{{ $log->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Detail Modal -->
                            <div class="modal fade" id="detailModal{{ $log->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <i class="bi bi-journal-text me-2"></i>Detail Log #{{ $log->id }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <table class="table table-borderless table-sm">
                                                        <tr>
                                                            <th width="40%">Waktu</th>
                                                            <td>{{ $log->created_at->format('d M Y H:i:s') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>User</th>
                                                            <td>
                                                                @if($log->user)
                                                                    <strong>{{ $log->user->name }}</strong>
                                                                    <span class="badge bg-secondary">{{ $log->user->role }}</span>
                                                                @else
                                                                    <span class="text-muted">System</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Aksi</th>
                                                            <td>
                                                                <span class="badge bg-{{ $log->action_color }}">
                                                                    {{ $log->action_label }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Model</th>
                                                            <td>
                                                                <strong>{{ $log->model_type }}</strong>
                                                                @if($log->model_id)
                                                                    <span class="text-muted">#{{ $log->model_id }}</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Label</th>
                                                            <td>{{ $log->model_label ?? '-' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Deskripsi</th>
                                                            <td>{{ $log->description ?? '-' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>IP Address</th>
                                                            <td><code>{{ $log->ip_address ?? '-' }}</code></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    @if($log->old_values || $log->new_values)
                                                        <h6 class="mb-3">Perubahan Data</h6>
                                                        @if($log->action === 'updated')
                                                            <table class="table table-sm table-bordered">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>Field</th>
                                                                        <th>Sebelum</th>
                                                                        <th>Sesudah</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($log->new_values ?? [] as $key => $newValue)
                                                                        <tr>
                                                                            <td><strong>{{ $key }}</strong></td>
                                                                            <td class="text-danger">
                                                                                {{ $log->old_values[$key] ?? '-' }}
                                                                            </td>
                                                                            <td class="text-success">
                                                                                {{ $newValue }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        @elseif($log->action === 'created')
                                                            <div class="bg-light p-2 rounded" style="max-height: 200px; overflow-y: auto;">
                                                                <small><strong>Data Baru:</strong></small>
                                                                <pre class="mb-0 small"><code>{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                                            </div>
                                                        @elseif($log->action === 'deleted')
                                                            <div class="bg-light p-2 rounded" style="max-height: 200px; overflow-y: auto;">
                                                                <small><strong>Data Dihapus:</strong></small>
                                                                <pre class="mb-0 small"><code>{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                                            </div>
                                                        @else
                                                            <div class="bg-light p-2 rounded" style="max-height: 200px; overflow-y: auto;">
                                                                @if($log->old_values)
                                                                    <small><strong>Old:</strong></small>
                                                                    <pre class="mb-1 small"><code>{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                                                @endif
                                                                @if($log->new_values)
                                                                    <small><strong>New:</strong></small>
                                                                    <pre class="mb-0 small"><code>{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="text-center text-muted py-4">
                                                            <i class="bi bi-inbox fs-2"></i>
                                                            <p class="mb-0 mt-2">Tidak ada data perubahan</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Tutup
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="bi bi-journal-text fs-2 text-muted"></i>
                                    <p class="text-muted mt-2">Belum ada log aktivitas</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
