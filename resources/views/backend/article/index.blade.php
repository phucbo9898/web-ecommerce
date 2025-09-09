@extends('backend.layouts.main')

@section('title', 'Danh sách bài viết')

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3>Danh sách bài viết</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped table-list">
                    <thead class="thead-dark">
                    <th>Tên bài viết</th>
                    <th>Ảnh</th>
                    <th>Mô tả</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                    </thead>
                    <tbody>
                        @foreach ($articles as $article)
                            <tr>
                                <td style="width:15%">{{ $article->name ?? '' }}</td>
                                <td><img style="width:80px;height:80px" src="{{ asset('upload/article/image/' . $article->image ?? '') }}"
                                         alt="No Avatar"/></td>
                                <td>{{ $article->description ?? '' }}</td>
                                <td style="width: 11%; text-align: center"><a
                                        href="{{ route('admin.article.handle', ['status', $article->id]) }}"
                                        class="badge badge-{{ ($article->publish ?? '') == 1 ? 'success' : 'danger' }}">{{ ($article->publish ?? '') == 1 ? 'Công khai' : 'Riêng tư' }}</a>
                                </td>
                                <td style="width:11%">
                                    <input type="hidden" class="convert-time"
                                           value="{{ date('Y-m-d h:i:s A', strtotime($article->created_at ?? '')) }}">
                                    {{ $article->created_at ?? '' }}
                                </td>
                                <td style="width: 11%">
                                    <a href="{{ route('admin.article.edit', $article->id) }}"
                                       class="btn btn-success btn-circle"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('admin.article.handle', ['delete', $article->id]) }}"
                                       data-id="{{ $article->id }}"
                                       class="btn_delete_sweet btn btn-danger btn-circle"><i
                                            class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
@section('javascript')
    <script>
        $('.table-list').find('.convert-time').each(function () {
            var a = moment.tz($(this).val(), Intl.DateTimeFormat().resolvedOptions().timeZone)
            $(this).parent('td').html(a.format('YYYY-MM-DD HH:mm:ss'))
        });
    </script>
@endsection
