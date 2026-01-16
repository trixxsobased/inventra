@extends('layouts.app')

@section('title', 'Detail Activity Log')
@section('page-title', 'Detail Activity Log')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Detail Log #{{ $activityLog->id }}</h3>
        <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="page-content">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Log</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="35%">Waktu</th>
                            <td>{{ $activityLog->created_at->format('d M Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>User</th>
                            <td>
                                @if($activityLog->user)
                                    <strong>{{ $activityLog->user->name }}</strong>
                                    <span class="badge bg-secondary">{{ $activityLog->user->role }}</span>
                                @else
                                    <span class="text-muted">System</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Aksi</th>
                            <td>
                                <span class="badge bg-{{ $activityLog->action_color }}">
                                    {{ $activityLog->action_label }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Model</th>
                            <td>
                                <strong>{{ $activityLog->model_type }}</strong>
                                @if($activityLog->model_id)
                                    <span class="text-muted">#{{ $activityLog->model_id }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Label</th>
                            <td>{{ $activityLog->model_label ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td>{{ $activityLog->description ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>IP Address</th>
                            <td><code>{{ $activityLog->ip_address ?? '-' }}</code></td>
                        </tr>
                        <tr>
                            <th>User Agent</th>
                            <td><small class="text-muted">{{ Str::limit($activityLog->user_agent, 100) ?? '-' }}</small></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            @if($activityLog->old_values || $activityLog->new_values)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Perubahan Data</h5>
                </div>
                <div class="card-body">
                    @if($activityLog->action === 'updated')
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Sebelum</th>
                                    <th>Sesudah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activityLog->new_values ?? [] as $key => $newValue)
                                    <tr>
                                        <td><strong>{{ $key }}</strong></td>
                                        <td class="text-danger">
                                            {{ $activityLog->old_values[$key] ?? '-' }}
                                        </td>
                                        <td class="text-success">
                                            {{ $newValue }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @elseif($activityLog->action === 'created')
                        <h6>Data Baru:</h6>
                        <pre class="bg-light p-3 rounded" style="max-height: 300px; overflow-y: auto;"><code>{{ json_encode($activityLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                    @elseif($activityLog->action === 'deleted')
                        <h6>Data Dihapus:</h6>
                        <pre class="bg-light p-3 rounded" style="max-height: 300px; overflow-y: auto;"><code>{{ json_encode($activityLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
