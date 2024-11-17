@extends('layouts.admin')

@section('title', $user->id ? 'Chỉnh sửa người dùng' : 'Thêm mới người dùng')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ $user->id ? route('admin.users.update', $user->id) : route('admin.users.store') }}" method="POST">
            @csrf
            @if($user->id)
                @method('PUT')
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group">
                <label for="name">Tên:</label>
                <input type="text" class="form-control" name="name" id="name"
                       value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" id="email"
                       value="{{ old('email', $user->email) }}">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">Số điện thoại:</label>
                <input type="text" class="form-control" name="phone" id="phone"
                       value="{{ old('phone', $user->phone) }}" required>
                @error('phone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            @if(!$user->id)
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="text" class="form-control" name="password" id="password"
                       value="{{ old('password', '') }}" required>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            @endif

            <button type="submit" class="btn btn-primary">Lưu</button>
        </form>
    </div>
</div>
@stop
