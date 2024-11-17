@extends('layouts.admin')

@section('title', $role->id ? 'Chỉnh sửa Role' : 'Thêm mới Role')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ $role->id ? route('admin.roles.update', $role) : route('admin.roles.store') }}" method="POST">
                @csrf
                @if($role->id)
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label for="name">Tên Role:</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                           value="{{ old('name', $role->name) }}" required>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="permissions">Permissions:</label>
                    <select name="permissions[]" id="permissions" class="form-control select2" multiple>
                        @foreach($permissions as $permission)
                            <option value="{{ $permission->id }}"
                                {{ $role->permissions->contains($permission->id) ? 'selected' : '' }}>
                                {{ $permission->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Lưu</button>
            </form>
        </div>
    </div>
@stop
