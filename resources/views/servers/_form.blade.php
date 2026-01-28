@csrf
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Name</label>
        <input name="name" class="form-control" value="{{ old('name', $server->name ?? '') }}" required>
        @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Host</label>
        <input name="host" class="form-control" value="{{ old('host', $server->host ?? '') }}" required>
        @error('host')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">SSH User</label>
        <input name="ssh_user" class="form-control" value="{{ old('ssh_user', $server->ssh_user ?? '') }}" required>
        @error('ssh_user')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">SSH Port</label>
        <input name="ssh_port" type="number" class="form-control" value="{{ old('ssh_port', $server->ssh_port ?? 22) }}" required>
        @error('ssh_port')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-9">
        <label class="form-label">Remote Path</label>
        <input name="remote_path" class="form-control" placeholder="/var/www/site" value="{{ old('remote_path', $server->remote_path ?? '') }}" required>
        @error('remote_path')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label">Exclude args (optional)</label>
        <input name="exclude_args" class="form-control" placeholder="--exclude=node_modules --exclude=storage/logs"
               value="{{ old('exclude_args', $server->exclude_args ?? '') }}">
        @error('exclude_args')<div class="text-danger small">{{ $message }}</div>@enderror
        <div class="form-text">This is passed into tar command on remote.</div>
    </div>

    <hr class="my-2">

    <div class="col-md-4">
        <label class="form-label">DB Name (optional)</label>
        <input name="db_name" class="form-control" value="{{ old('db_name', $server->db_name ?? '') }}">
        @error('db_name')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">DB User (optional)</label>
        <input name="db_user" class="form-control" value="{{ old('db_user', $server->db_user ?? '') }}">
        @error('db_user')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">DB Password (optional)</label>
        <input name="db_password" type="password" class="form-control" value="">
        @error('db_password')<div class="text-danger small">{{ $message }}</div>@enderror
        @isset($server)
            <div class="form-text">Leave empty to keep existing password.</div>
        @endisset
    </div>

    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                @checked(old('is_active', $server->is_active ?? true))>
            <label class="form-check-label">Active</label>
        </div>
    </div>
</div>

<div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary">Save</button>
    <a href="{{ route('servers.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>
