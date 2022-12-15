@extends('layouts.panel')
@section('content')
    <!-- Modal -->
    <div class="modal fade" id="add_user" tabindex="-1" role="dialog" aria-labelledby="add_user" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="search"><i class="fa fa-search"></i> افزودن کاربر</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ url('panel/user_store') }}" method="POST" id="addUser">
                        <div class="form-group">
                            <label for="name">نام</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="نام" value="{{ old('name') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">ایمیل</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="ایمیل" value="{{ old('email') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="user_name">نام کاربر (به انگلیسی)</label>
                            <input type="text" name="user_name" id="user_name" class="form-control" placeholder="نام کابری(یونیک)" value="{{ old('user_name') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="password">گذرواژه</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="گذرواژه" value="{{ old('password') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="role_id">سطح دسترسی</label>
                            <select name="role_id" id="role_id" class="form-control select" data-live-search="true" required>
                                <?php
                                $roles=\App\Models\Role::where('id','!=',5)->get();
                                ?>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->description }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            {{ csrf_field() }}
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">بیخیال نمیخوام</button>
                    <button type="button" class="btn btn-primary" onclick="$('#addUser').submit();">ثبت کاربر</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit_user" tabindex="-1" role="dialog" aria-labelledby="edit_user" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="search"><i class="fa fa-search"></i> افزودن کاربر</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ url('panel/user_update') }}" method="POST" id="editUser">
                        <input type="hidden" value="" name="id" id="user_id">
                        <div class="form-group">
                            <label for="name">نام</label>
                            <input type="text" name="name" id="edit_name" class="form-control name" placeholder="نام" required>
                        </div>
                        <div class="form-group">
                            <label for="email">ایمیل</label>
                            <input type="email" name="email" id="edit_email" class="form-control email" placeholder="ایمیل" required>
                        </div>
                        <div class="form-group">
                            <label for="user_name">نام کاربر (به انگلیسی)</label>
                            <input type="text" name="user_name" id="edit_user_name" class="form-control user_name" placeholder="نام کابری(یونیک)" required>
                        </div>
                        <div class="form-group">
                            <label for="password">گذرواژه</label>
                            <input type="password" name="password" id="edit_password" class="form-control password" placeholder="گذرواژه" required>
                        </div>
                        <div class="form-group">
                            <label for="role_id">سطح دسترسی</label>
                            <select name="role_id" id="edit_role_id" class="form-control select role_id" data-live-search="true" required>
                                <?php
                                $roles=\App\Models\Role::where('id','!=',5)->get();
                                ?>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->description }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="suspended">وضعیت کاربر</label>
                            <select name="suspended" id="suspended" class="form-control select suspended" data-live-search="true" required>
                                <option value="0">نرمال</option>
                                <option value="1">معلق</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="draft_permission">دسترسی به ثبت پیشنویس</label>
                            <select name="draft_permission" id="draft_permission" class="form-control select draft_permission" data-live-search="true" required>
                                <option value="0">ندارد</option>
                                <option value="1">دارد</option>
                            </select>
                        </div>
                        <div class="form-group">
                            {{ csrf_field() }}
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">بیخیال نمیخوام</button>
                    <button type="button" class="btn btn-primary" onclick="$('#editUser').submit();">ویرایش کاربر</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-sidebar panel-heading">{{$title}}<span class="pull-left">تعداد : {{ $users->count() }}</span></div>
            <div class="panel-body">
                <button class="btn btn-sm btn-success pull-left" data-toggle="modal" data-target="#add_user" style="margin-right: .5rem"><i class="fa fa-user"></i> افزودن کاربر</button>
                <table class="table datatable-responsive22 table-togglable">
                    <thead>
                    <tr>
                            <th data-hide="phone">#</th>
                            <th data-toggle="true">نام</th>
                            <th data-toggle="true">نام شرکت</th>
                            <th data-hide="phone">ایمیل</th>
                            <th data-hide="phone">تلفن</th>
                            <th data-hide="phone">دسترسی</th>
                            <th data-hide="phone">وضعیت</th>
                            <th data-hide="phone">دستور</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                            <tr class="text-center">
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->company__name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{!! $user->company__phone ? $user->company__phone : '<span class="text-warning">وارد نشده</span>' !!}</td>
                                <td>
                                    <span class="text-success">{{ $user->role->description }}</span>
                                </td>
                                <td>{!! $user->suspended==0 ? '<span class="table-status table-answered">نرمال</span' : '<span class="table-status table-doing">معلق</span' !!}</td>
                                <td><button class="btn btn-sm btn-warning edit" data-id="{{ $user->id }}">ویرایش</button></td>
                            </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    @push('script')
        <script>
            $('.edit').click(function () {
                $.ajax({
                    method: 'GET',
                    url:'{{ route('edit_user') }}',
                    data:{
                        id:$(this).attr('data-id')
                    },
                    success: function (data) {
                        $('#edit_user').modal('show');
                        $('#editUser #user_id').val(data['id']);
                        $('#editUser input.name').val(data['name']);
                        $('#editUser input.email').val(data['email']);
                        $('#editUser input.user_name').val(data['user_name']);
                        $('select[name=role_id]').val(data['role_id']);
                        $('.select').selectpicker('refresh');
                        $('select[name=suspended]').val(data['suspended']);
                        $('.select').selectpicker('refresh');
                        $('select[name=draft_permission]').val(data['draft_permission']);
                        $('.select').selectpicker('refresh');
                    }
                })
            })
        </script>
    @endpush
@endsection
