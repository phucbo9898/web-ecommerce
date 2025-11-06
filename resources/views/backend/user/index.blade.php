@extends('backend.layouts.main')

@section('title', 'Danh sách người dùng')
<?php
use App\Enums\UserType;
use App\Enums\ActiveStatus;
?>
@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3>Danh sách thành viên</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <div class="">
                        <form action="{{ url()->full() }}" method="GET">
                            <div class="row">
                                <div class="col-md-3 mt-3">
                                    <label for="name" class="col-form-label">@lang('Tên thành viên')</label>
                                    <input type="text" autocomplete="off" name="name" class="form-control"
                                        value="{{ $options['name'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label for="name" class="col-form-label">@lang('Email')</label>
                                    <input type="text" autocomplete="off" name="email" class="form-control"
                                        value="{{ $options['email'] ?? '' }}">
                                </div>
                                @if (Auth::user()->isAdmin())
                                    <div class="col-md-3 mt-3">
                                        <label for="entertainment" class="col-form-label">@lang('Phân quyền')</label> <br>
                                        <select name="role" class="form-control">
                                            <option value="">@lang('Chọn quyền')</option>
                                            @foreach (UserType::getValues() as $role)
                                                <option value="{{ $role }}"
                                                    @if (isset($options['role']) && $role == $options['role']) {{ 'selected' }} @endif>
                                                    @lang(UserType::getUserType($role))
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <button class="btn btn-info min-w-100" type="submit">@lang('Tìm kiếm')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <table class="table table-striped table-list">
                    <thead class="thead-dark">
                        <th>STT</th>
                        <th>Ảnh đại diện</th>
                        <th>Họ và tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Phân quyền</th>
                        <th>Trạng thái</th>
                        <th style=" text-align: center">Hành động</th>
                    </thead>
                    <tbody>
                        <?php $stt = 1; ?>
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    {{ $stt++ }}</td>
                                <td>
                                    <img class="avatar-user" src="{{ asset('upload/user/image/' . $user->avatar ?? '') }}" alt="">
                                </td>
                                <td>{{ $user->name ?? '' }}</td>
                                <td>{{ $user->email ?? '' }}</td>
                                <td>{{ $user->phone ?? '' }}</td>
                                <td class="">
                                    @switch($user->role)
                                        @case(UserType::ADMIN)
                                            <span class="badge badge-success">Admin</span>
                                        @break

                                        @case(UserType::SYSTEMADMIN)
                                            <span class="badge badge-info">System Admin</span>
                                        @break

                                        @default
                                            <span class="badge badge-secondary">User</span>
                                    @endswitch
                                </td>
                                <td>
                                    <span
                                        class="badge badge-{{ $user->status == ActiveStatus::ACTIVE ? 'primary' : 'danger' }}">
                                        {{ ActiveStatus::getStatusName($user->status) }}
                                    </span>
                                </td>
                                <td style="width: 16%; text-align: center;">
                                    <a href="{{ route('admin.user.edit', $user->id) }}"
                                        class="btn btn-success btn-circle"><i class="fas fa-edit"></i></a> &nbsp;
                                    <a href="{{ route('admin.user.action', ['delete', $user->id]) }}"
                                        class="btn btn-danger btn-circle" data-id="{{ $user->id }}"><i
                                            class="fas fa-trash-alt"></i></a> &nbsp;
                                    <a href="{{ route('admin.user.change.password', $user->id) }}"
                                        class="button_change_password btn btn-warning btn-circle"
                                        data-email='{{ $user->email }}' data-toggle="modal"
                                        data-target="#exampleModalCenter"><i class="fas fa-key"></i></a>
                                </td>
                            </tr>
                            {{-- custom modal by me --}}
                            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Đổi mật khẩu tài khoản
                                                <span class="model_change_password_email"></span>
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div>
                                                Mật khẩu mới:
                                                <input type="password" name="new_password" minlength="3"
                                                    class="form-control new_password_class">
                                            </div>
                                            <div>
                                                Nhập lại mật khẩu mới:
                                                <input type="password" name="confirm_password" minlength="3"
                                                    class="form-control confirm_password_class">
                                            </div>
                                            <div class="form-group">
                                                <button class="btn btn-success mt-2 button_appect_change_password"
                                                    style="float: right">Lưu mật khẩu
                                                </button>
                                                <div style="clear: both"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- end custom modal by me --}}
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection
@section('javascript')
    <script>
        $(document).ready(function() {
            $("img").bind("error", function() {
                $(this).addClass('d-none');
            });
        });
    </script>
    <script>
        $(".btn_delete_sweet").click(function(e) {
            e.preventDefault();
            url = $(this).attr('href');
            id = $(this).attr('data-id');
            swal({
                title: "Bạn có chắc chắn?",
                text: "Bạn có chắc chắn muốn xóa tài khoản ID=" + id +
                    " không ? Điều này sẽ ảnh hưởng đến liên kết dữ liệu!",
                icon: "info",
                buttons: ["Không", "Có"],
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    swal("Thành công", "Hệ thống chuẩn bị xóa tài khoản mang ID =" + id + " !", 'success')
                        .then(function() {
                            window.location.href = url;
                        });
                }
            });
        });
    </script>
    <script>
        $(function() {
            $(".button_change_password").click(function(e) {
                console.log(123)
                e.preventDefault();
                email = $(this).attr('data-email');
                url = $(this).attr('href');
                $(".model_change_password_email").text(email);

                $(".button_appect_change_password").click(function(e) {
                    e.preventDefault();
                    var new_password = $('.new_password_class').val();
                    var confirm_password = $('.confirm_password_class').val();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "new_password": new_password,
                            "confirm_password": confirm_password
                        }
                    }).done(function(result) {
                        if (result.status == 1) {
                            swal("Thành công", "Đã thay đổi mật khẩu thành công !",
                                "success").then(function() {
                                $("#exampleModalCenter").modal("hide");
                                location.reload();
                            });

                        } else if (result.status == 2) {
                            swal("Thất bại",
                                "Đã xảy ra lỗi kiểm tra lại mật khẩu xem giống nhau không",
                                "error");
                        } else {
                            swal("Thất bại", "Đã xảy ra lỗi lớn", "error");
                        }
                    });
                });
            });
        });
    </script>
@endsection
